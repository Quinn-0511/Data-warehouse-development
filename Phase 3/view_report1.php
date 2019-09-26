<?php

include("lib/common.php");
// created by bxu93
//written by sliao33

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
                        <div class="subtitle">Manufacturer's Product Report</div>
                        <table>
                            <tr>
                                <?php 
                                    $query2 = "SELECT manufacturer_name, COUNT(product_ID) AS num_product,
                                    ROUND(AVG(retail_price), 2) AS avg_price,
                                    MIN(retail_price) AS min_price, MAX(retail_price) AS max_price
                                    FROM Product
                                    GROUP BY manufacturer_name
                                    ORDER BY avg_price DESC
                                    LIMIT 100;";

                                    $result2 = mysqli_query($db, $query2);
                                    $all_property = array();  //declare an array for saving property
                                    
                                    //showing property
                                    echo '<table class="data-table">
                                        <tr class="data-heading">';  //initialize table tag
                                    while ($property = mysqli_fetch_field($result2)) {
                                        echo '<td>' . $property->name . '</td>';  //get field name for header
                                        array_push($all_property, $property->name);  //save those to array
                                    }
                                    echo '</tr>'; //end tr tag
                                    //showing all data
                                    while ($row = mysqli_fetch_array($result2)) {
                                            echo "<tr>";
                                            foreach ($all_property as $item) {
                                            echo '<td>' . $row[$item] . '</td>'; //get items using property value
                                            }
                                            echo '<td><a href="Manufacturer_detail.php?id=' . $row['manufacturer_name'] . '">Manufacturer Details</a></td>';
                                            // echo '<td><a href="Manufacturer_product.php?id=' . $row['manufacturer_name'] . '">Product Details</a></td>';
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
