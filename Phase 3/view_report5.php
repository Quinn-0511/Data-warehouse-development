<?php

include("lib/common.php");
// created by bxu93

    $query = "SELECT SUM(quantity) AS total_quantity, SUM(quantity)/365 AS avg_quantity, " .
             "SUM(quantity*(CASE WHEN month(transaction_date)=2 AND day(transaction_date)=2 " .
             "THEN 1 ELSE 0 END)) AS groundhog_quantity, sale_year " .
             "FROM (SELECT year(t.transaction_date) AS sale_year, quantity, transaction_date " .
             "FROM Transaction t INNER JOIN Category c ON t.product_ID=c.product_ID " .
             "WHERE c.category_name='Air Conditioner') AS ac " .
             "GROUP BY sale_year " .
             "ORDER BY sale_year ASC;";

    $result = mysqli_query($db, $query);
    include("lib/show_queries.php");

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
                        <div class="subtitle">Air Conditioners on Groundhog Day</div>
                        <table>
                            <tr>
                                <td class="item_label">sale year</td>
                                <td class="item_label">total quantity</td>
                                <td class="item_label">average quantity per day</td>
                                <td class="item_label">total quantity on Groundhog Day</td>
                            </tr>
                            <?php
                                while($row = mysqli_fetch_array($result)) {
                                    print "<tr>";
                                    print "<td>" . $row['sale_year'] . "</td>";
                                    print "<td>" . $row['total_quantity'] . "</td>";
                                    print "<td>" . round($row['avg_quantity'], 2) . "</td>";
                                    print "<td>" . $row['groundhog_quantity'] . "</td>";
                                    print "</tr>";
                                }
                            ?>
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
