<?php

include("lib/common.php");
// created by bxu93
//update sql sliao33
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
                        <div class="subtitle">Revenue by Population</div>
                        <form action="view_report7.php" method="post">
                            <input type="submit" name="population" value="By Population Group" />
                            <input type="submit" name="year" value="By Year" />
                        <?php
                        $query = "SELECT
                                    n.city_size,
                                    n.sale_year,
                                    FORMAT(
                                        SUM(
                                            n.quantity *(
                                                CASE WHEN s.sales_price IS NULL THEN p.retail_price ELSE s.sales_price
                                            END
                                        )
                                    ) / COUNT(DISTINCT city_name),
                                    0
                                ) AS avg_revenue
                                FROM
                                    (
                                      SELECT
                                          (
                                              CASE WHEN s.population < 3700000 THEN 'Small' WHEN s.population >= 3700000 AND s.population < 6700000 THEN 'Medium' WHEN s.population >= 6700000 AND s.population < 9000000 THEN 'Large' WHEN s.population >= 9000000 THEN 'Extra Large'
                                          END
                                      ) AS city_size,
                                      (
                                      CASE WHEN s.population < 3700000 THEN '1' WHEN s.population >= 3700000 AND s.population < 6700000 THEN '2' WHEN s.population >= 6700000 AND s.population < 9000000 THEN '3' WHEN s.population >= 9000000 THEN '4'
                                      END
                                      ) AS city_size1,
                                      s.city_name,
                                      t.*,
                                      YEAR(t.transaction_date) AS sale_year
                                      FROM
                                      Store s,
                                      TRANSACTION t
                                      WHERE
                                      t.store_number = s.store_number
                                ) AS n
                                INNER JOIN Product p ON
                                    n.product_ID = p.product_ID
                                LEFT JOIN Sales s ON
                                    n.transaction_date = s.sales_date AND n.product_ID = s.product_ID
                                GROUP BY
                                    n.city_size,
                                    n.city_size1,
                                    n.sale_year
                                ORDER BY
                                    n.city_size1,
                                    n.sale_year;  ";
                        include("lib/show_queries.php");

                        $result = mysqli_query($db, $query);

                        $all_property = array();
                        $city_size = array();
                        $sale_year = array();
                        $revenue = array();
                        // get data
                        while ($property = mysqli_fetch_field($result)) {
                            array_push($all_property, $property->name);
                        }
                        while ($row = mysqli_fetch_array($result)) {
                            if (!in_array($row[$all_property[0]], $city_size)) {
                                // echo $row[$all_property[0]], "\r\n";
                                array_push($city_size, $row[$all_property[0]]);
                            }
                            if (!in_array($row[$all_property[1]], $sale_year)) {
                                // echo $row[$all_property[1]], "\r\n";
                                array_push($sale_year, $row[$all_property[1]]);
                            }
                            array_push($revenue, $row[$all_property[2]]);
                        }
                        // show property
                        if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['year'])) {
                            echo "By Year\r\n";
                            echo '<table class="data-table>
                                <tr class="data-heading">';
                            // show header
                            echo '<td>' . $all_property[1] . '</td>';
                            foreach ($city_size as $size) {
                                echo '<td>' . $size . '</td>';
                            }
                            echo "</tr>";
                            for ($i = 0; $i < count($sale_year); $i++) {
                                echo '</tr>';
                                echo '<td>' . $sale_year[$i] . '</td>';
                                for ($j = $i * count($city_size); $j < count($revenue) && $j < ($i+1) * count($city_size); $j++) {
                                    echo '<td>' . $revenue[$j] . '</td>';
                                }
                                echo '</tr>';
                            }
                            echo "</table>";
                        } else {
                            echo "By population\r\n";
                            echo '<table class="data-table>
                                <tr class="data-heading">';
                            // show header
                            echo '<td>' . $all_property[0] . '</td>';
                            foreach ($sale_year as $year) {
                                echo '<td>' . $year . '</td>';
                            }
                            echo "</tr>";
                            for ($i = 0; $i < count($city_size); $i++) {
                                echo '</tr>';
                                echo '<td>' . $city_size[$i] . '</td>';
                                for ($j = $i * count($sale_year); $j < count($revenue) && $j < ($i+1) * count($sale_year); $j++) {
                                    echo '<td>' . $revenue[$j] . '</td>';
                                }
                                echo '</tr>';
                            }
                            echo "</table>";
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php include("lib/error.php"); ?>
            <div class="clear"></div>
        </div>
    </div>
</body>
</html>
