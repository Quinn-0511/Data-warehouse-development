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
                        <?php
                            $year = $_GET['year'];
                            $month = $_GET['month'];
                            $category_name = $_GET['category_name'];
                            $state = $_GET['state'];
                            echo '<div class="subtitle">Store Details in State ' . $state . ' for Category ' . $category_name . ' (' . $year . '-' . $month . ')</div>';

                            $query = "SELECT
                                        s.store_number,
                                        s.street_address,
                                        s.city_name,
                                        CONCAT(m.first_name, ' ', m.last_name) AS manager_name,
                                        m.email_address
                                    FROM
                                        store s,
                                        activemanager a,
                                        manager m
                                    WHERE
                                        s.store_number = a.store_number AND a.email_address = m.email_address AND s.state = '$state' AND s.store_number IN(
                                        SELECT DISTINCT
                                            t.store_number
                                        FROM TRANSACTION
                                            t,
                                            category c
                                        WHERE
                                            t.product_ID = c.product_ID AND c.category_name = '$category_name' AND YEAR(t.transaction_date) = $year AND MONTH(t.transaction_date) = $month
                                    )
                                    ORDER BY
                                        s.store_number";
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
                    </div>
                </div>
            </div>
            <?php include("lib/error.php"); ?>
            <div class="clear"></div>
        </div>
    </div>
</body>
</html>