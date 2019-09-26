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
                        <div class="subtitle">Manufacturer detail</div>
                           
                        <table>
                            <tr>
                                <?php 
                                    $manufacturer_name = $_GET['id'];
                                    // print "select state: $manufacturer_name";
                                    // echo $manufacturer_name;
                                    $query = "SELECT p.product_ID, GROUP_CONCAT(c.category_name SEPARATOR ',') AS category, p.product_name, p.retail_price
                                    FROM Product p, Category c
                                    WHERE p.product_ID=c.product_ID AND p.manufacturer_name = '$manufacturer_name'
                                    GROUP BY p.product_ID 
                                    ORDER BY p.retail_price DESC;";

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