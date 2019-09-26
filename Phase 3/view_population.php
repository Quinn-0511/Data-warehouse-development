<?php
include("lib/common.php");
// add by sliao33, change population
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
                      <div class="subtitle">Change the population of a city</div>
                    <div class="general_info">Please select a city to query.

                    <?php
                        print "select state: ";
                        if(isset($_POST['city']) && !empty($_POST['population'])) {
                            echo $_POST['city'];
                            $city= $_POST['city'];
                            $pop=$_POST['population'];
                            $query = "UPDATE Store SET population=$pop WHERE city_name=SUBSTRING_INDEX('$city', ', ', 1) AND state=SUBSTRING_INDEX('$city', ', ', -1)";
                            $result = mysqli_query($db, $query);
                            include("lib/show_queries.php");
                        } else{
                                print "It's empty";
                        }
                    ?>

                    <form action="view_population.php" method="post">
                        <?php
                            $query="Select DISTINCT CONCAT(city_name,', ',population,', ',state) as city, state from Store ORDER BY state, city";
                            $result = mysqli_query($db, $query);
                            include("lib/show_queries.php");
                            $opt .="<select name='city'>";
                            while ($row = mysqli_fetch_assoc($result)){
                                $opt .= "<option value='{$row['city']}'>{$row['city']}</option>";
                            }

                            $opt .= "</select>";

                            echo $opt;
                        ?>
                        <input type="text" name="population" placeholder="Population"/><br>
                        <input type="submit" value="submit">
                    </form> 
                    <?php
                        // print "select state: ";
                        // if(isset($_POST['city']) && !empty($_POST['population'])) {
                        //     echo $_POST['city'];
                        //     $city= $_POST['city'];
                        //     $pop=$_POST['population'];
                        //     $query = "UPDATE City SET population=$pop WHERE city_name=SUBSTRING_INDEX('$city', ', ', 1) AND state=SUBSTRING_INDEX('$city', ', ', -1)";
                        //     $result = mysqli_query($db, $query);
                        //     include("lib/show_queries.php");
                        // } else{
                        //         print "It's empty";
                        // }
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
