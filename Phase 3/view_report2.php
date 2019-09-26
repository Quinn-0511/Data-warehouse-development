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
                        <div class="subtitle">Category Report</div>
                        <table>
                            <tr>
                                <?php 
                                    $query = "SELECT c.category_name, COUNT(c.product_ID) AS cnt_product, 
                                    COUNT(DISTINCT p.manufacturer_name) AS cnt_manufacturer, ROUND(AVG(p.retail_price),2) AS avg_price
                                    FROM Category c, Product p
                                    WHERE c.product_ID=p.product_ID 
                                    GROUP BY c.category_name
                                    ORDER BY c.category_name ";
                                    $result = mysqli_query($db, $query);
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