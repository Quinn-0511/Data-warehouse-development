<?php

include("lib/common.php");
// created by bxu93

?>

<?php include("lib/header.php"); ?>
    <title>S&E Warehouse</title>
</head>

<body>

    <div id="main_container">
    <?php include "lib/menu.php"; ?>

        <div class="center_content">
            <div class="center_left">
                <div class="features">
                    <div class="general_info">
                        <div class="subtitle">Actual versus Predicted Revenue for GPS units</div>
                        <table>
                            <tr>
                                <?php 
                                    $query = "SELECT
                                        report3.product_ID,
                                        report3.product_name,
                                        report3.retail_price,
                                        report3.Total_sold,
                                        report3.discount_sold,
                                        report3.retail_sold,
                                        report3.actual_revenue,
                                        report3.predict_revenue,
                                        (
                                            report3.actual_revenue - report3.predict_revenue
                                        ) AS diff_revenue
                                    FROM
                                        (
                                        SELECT
                                            tps.product_ID,
                                            tps.product_name,
                                            tps.retail_price,
                                            SUM(tps.quantity) AS Total_sold,
                                            SUM(tps.quantity * tps.is_sale) AS discount_sold,
                                            SUM(tps.quantity *(1 - tps.is_sale)) AS retail_sold,
                                            ROUND(SUM(
                                                tps.transaction_price * tps.quantity
                                            ), 2) AS actual_revenue,
                                            ROUND(SUM(
                                                tps.retail_price *(
                                                    CASE WHEN tps.is_sale = 1 THEN 0.75 * tps.quantity ELSE tps.quantity
                                                END
                                            )
                                    ),2) AS predict_revenue
                                    FROM
                                        (
                                        SELECT
                                            t.transaction_date AS transaction_date,
                                            t.quantity AS quantity,
                                            p.retail_price AS retail_price,
                                            t.product_ID AS product_ID,
                                            p.product_name AS product_name,
                                            CASE WHEN t.transaction_date = s.sales_date THEN 1 ELSE 0
                                    END AS is_sale,
                                    CASE WHEN t.transaction_date = s.sales_date THEN s.sales_price ELSE p.retail_price
                                    END AS transaction_price
                                    FROM TRANSACTION t
                                    INNER JOIN Product p ON
                                        t.product_ID = p.product_ID
                                    LEFT JOIN Sales s ON
                                        t.product_ID = s.product_ID AND t.transaction_date = s.sales_date
                                    WHERE
                                        t.product_ID IN(
                                        SELECT
                                            product_ID
                                        FROM
                                            category
                                        WHERE
                                            category_name = 'GPS'
                                    )
                                    ) tps
                                    GROUP BY
                                        tps.product_ID
                                    ) report3
                                    HAVING
                                        diff_revenue > 5000 OR diff_revenue < -5000
                                    ORDER BY
                                        diff_revenue
                                    DESC";
                                    $result = mysqli_query($db, $query);
                                    include("lib/show_queries.php");
                                    $all_property = array();  //declare an array for saving property

                                    
                                    //showing property
                                    echo '<table class="data-table">
                                        <tr class="data-heading">';  //initialize table tag
                                    while ($property = mysqli_fetch_field($result)) {
                                        echo '<td>' . $property->name . '</td>';  //get field name for header
                                        array_push($all_property, $property->name);  //save those to array
                                    }
                                    echo '</tr>'; //end tr tag

                                    //showing all data
                                    while ($row = mysqli_fetch_array($result)) {
                                            echo "<tr>";
                                            foreach ($all_property as $item) {
                                            echo '<td>' . $row[$item] . '</td>'; //get items using property value
                                            }
                                            echo '</tr>';
                                    }
                                    echo "</table>";
                                    ?>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
            <?php include("lib/error.php"); ?>
            <div class="clear"></div>
        </div>
    </div>
</body>
</html>
