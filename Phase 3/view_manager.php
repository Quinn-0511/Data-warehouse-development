<?php
include("lib/common.php");
// written by sliao33, add or drop manager
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



                        <div class="subtitle">Add Manager</div>
                        <!-- show dropdown box -->
                        <form action="view_manager.php" method="post">
                            <!-- <input type="date"/> -->
                            <input type="text" name="email_address" placeholder="Email Address"/><br>
                            <input type="text" name="first_name" placeholder="First Name"/><br>
                            <input type="text" name="last_name" placeholder="Last Name"/><br>

                            <input type="submit" name="add_manager" value="Add Manager"/>
                        </form>
                        <?php
                            if ($_SERVER['REQUEST_METHOD']=='POST' and isset($_POST['add_manager'])) {
                                $email = $_POST['email_address'];
                                $first_name = $_POST['first_name'];
                                $last_name = $_POST['last_name'];
                                echo "Sucessfully added: ".$email." ".$first_name." ".$last_name;
                                // guaranteed to be valid
                                // insert into database
                                $query = "INSERT INTO Manager (email_address, first_name, last_name) VALUES ('$email', '$first_name', '$last_name');";
                                include("lib/show_queries.php");
                                $result = mysqli_query($db, $query);
                            }
                        ?>


                        <div class="subtitle">Assign Manager</div>
                        <!-- show dropdown box -->
                        <form action="view_manager.php" method="post">
                            <!-- <input type="date"/> -->
                            <input type="text" name="email_address" placeholder="Email Address"/><br>
                            <input type="text" name="store_number" placeholder="Store"/><br>

                            <input type="submit" name="assign_manager" value="Assign Manager"/>
                        </form>
                        <?php
                            if ($_SERVER['REQUEST_METHOD']=='POST' and isset($_POST['assign_manager'])) {
                                $email = $_POST['email_address'];
                                $store = $_POST['store_number'];
                                echo "Sucessfully assigned: ".$email." to store: ".$store;
                                // guaranteed to be valid
                                // insert into database
                                $query = "INSERT INTO ActiveManager (email_address, store_number) VALUES ('$email', '$store');";
                                include("lib/show_queries.php");
                                $result = mysqli_query($db, $query);
                            }
                        ?>


                        <div class="subtitle">Unassign Manager</div>
                        <!-- show dropdown box -->
                        <form action="view_manager.php" method="post">
                            <!-- <input type="date"/> -->
                            <input type="text" name="email_address" placeholder="Email Address"/><br>
                            <input type="text" name="store_number" placeholder="Store"/><br>
                            <input type="submit" name="unassign_manager" value="Unassign Manager"/>
                        </form>
                        <?php
                            if ($_SERVER['REQUEST_METHOD']=='POST' and isset($_POST['unassign_manager'])) {
                                $email = $_POST['email_address'];
                                $store = $_POST['store_number'];
                                echo "Sucessfully unassigned: ".$email." from store: ".$store;
                                // guaranteed to be valid
                                // insert into database
                                $query = "DELETE FROM ActiveManager WHERE email_address='$email' AND store_number='$store';";
                                include("lib/show_queries.php");
                                $result = mysqli_query($db, $query);
                            }
                        ?>


                        <div class="subtitle">Drop Manager</div>
                        <!-- show dropdown box -->
                        <form action="view_manager.php" method="post">
                            <!-- <input type="date"/> -->
                            <input type="text" name="email_address" placeholder="Email Address"/><br>

                            <input type="submit" name="drop_manager" value="Drop Manager"/>
                        </form>
                        <?php
                            if ($_SERVER['REQUEST_METHOD']=='POST' and isset($_POST['drop_manager'])) {
                                $email = $_POST['email_address'];
                                // $store = $_POST['store_number'];
                                echo "Sucessfully deleted: ".$email;
                                // guaranteed to be valid
                                // insert into database
                                $query = "DELETE FROM Manager WHERE Manager.email_address='$email';";
                                include("lib/show_queries.php");
                                $result = mysqli_query($db, $query);
                            }
                        ?>
                                
                    <?php
                        if (isset($_POST['add_manager'])||isset($_POST['assign_manager'])||isset($_POST['unassign_manager'])||isset($_POST['drop_manager']) ) {
                            $query2 = "SELECT m.email_address, m.first_name, m.last_name, am.store_number FROM manager m LEFT JOIN activemanager am ON m.email_address = am.email_address";

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
                                    // echo '<td><a href="Manufacturer_detail.php?id=' . $row['manufacturer_name'] . '">Manufacturer Details</a></td>';
                                    // echo '<td><a href="Manufacturer_product.php?id=' . $row['manufacturer_name'] . '">Product Details</a></td>';
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
