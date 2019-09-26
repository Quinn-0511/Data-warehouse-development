<?php

include("lib/common.php");
// written by bxu93

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
                        <div class="subtitle">Add Holiday</div>
                        <!-- show dropdown box -->
                        <form action="view_holidays.php" method="post">
                            <!-- <input type="date"/> -->
                            <input placeholder="Date" class="textbox-n" type="text" onfocus="(this.type='date')" onblur="(this.type='text')" id="date" name="date"/>
                            <input type="text" name="holiday_name" placeholder="Holiday Name"/><br>
                            <input type="submit" name="holiday" value="Add Holiday"/>
                        </form>
                        <?php
                            if ($_SERVER['REQUEST_METHOD']=='POST' and isset($_POST['holiday'])) {
                                $date = $_POST['date'];
                                $holiday = mysqli_real_escape_string($db, $_POST['holiday_name']);
                                // guaranteed to be valid
                                // insert into database
                                $query = "INSERT INTO Holiday (holiday_date, holiday_name) VALUES ('$date', '$holiday');";
                                include("lib/show_queries.php");
                                $result = mysqli_query($db, $query);
                            }
                        ?>
                        <div class="subtitle">Store Holidays</div>
                        <?php
                            // fetch store holiday
                            $query = "SELECT * FROM Holiday";
                            include("lib/show_queries.php");

                            $result = mysqli_query($db, $query);
                            $all_property = array();
                            echo '<table class="data-table">
                                <tr class="data-heading">';  //initialize table tag
                            while ($property = mysqli_fetch_field($result)) {
                                echo '<td>' . $property->name . '</td>';  //get field name for header
                                array_push($all_property, $property->name);  //save those to array
                            }
                            echo '</tr>'; //end tr tag
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