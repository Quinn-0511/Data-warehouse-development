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
                        <div class="subtitle">General Statistics</div>
                        <table>
                            <tr>
                                <td class="item_label">Item</td>
                                <td class="item_label">Number</td>
                            </tr>
                            <tr>
                                <td class="item_label">Stores</td>
                                <td>
                                    <?php 
                                        $query = "SELECT COUNT(*) AS count FROM Store";
                                        $result = mysqli_query($db, $query);
                                        $row = mysqli_fetch_assoc($result);

                                        include("lib/show_queries.php");

                                        print($row['count']);
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="item_label">Manufacturers</td>
                                <td>
                                    <?php 
                                        $query = "SELECT COUNT(*) AS count FROM Manufacturer";
                                        $result = mysqli_query($db, $query);
                                        $row = mysqli_fetch_assoc($result);

                                        include("lib/show_queries.php");

                                        print($row['count']);
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="item_label">Products</td>
                                <td>
                                    <?php 
                                        $query = "SELECT COUNT(*) AS count FROM Product";
                                        $result = mysqli_query($db, $query);
                                        $row = mysqli_fetch_assoc($result);

                                        include("lib/show_queries.php");

                                        print($row['count']);
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="item_label">Managers</td>
                                <td>
                                    <?php 
                                        $query = "SELECT COUNT(*) AS count FROM Manager";
                                        $result = mysqli_query($db, $query);
                                        $row = mysqli_fetch_assoc($result);

                                        include("lib/show_queries.php");

                                        print($row['count']);
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <!-- add button for view reports -->
            <div class="center_right">
            <form action="view_report1.php">
                <input type="submit" value="Manufacture's Product Report"/>
            </form>
            <form action="view_report2.php">
                <input type="submit" value="Category Report"/>
            </form>
            <form action="view_report3.php">
                <input type="submit" value="Actual versus Predicted Revenue for GPS units"/>
            </form>
            <form action="view_report4.php">
                <input type="submit" value="Store Revenue by Year by State"/>
            </form>
            <form action="view_report5.php">
                <input type="submit" value="Air Conditioners on Groundhog Day"/>
            </form>
            <form action="view_report6.php">
                <input type="submit" value="State with Highest Volume for each Category"/>
            </form>
            <form action="view_report7.php">
                <input type="submit" value="Revenue by Population"/>
            </form>
            </div>
            <?php include("lib/error.php"); ?>
            <div class="clear"></div>
        </div>
    </div>
</body>
</html>