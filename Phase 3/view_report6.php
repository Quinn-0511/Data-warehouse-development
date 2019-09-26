<?php

include("lib/common.php");
// written by bxu93 and ewang
////update sql by sliao33, merge city and store

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
                        <div class="subtitle">Year and Month</div>
                        <form action="view_report6.php" method="get">
                            <select name="year">
                                <?php
                                $query = "SELECT DISTINCT year(transaction_date) AS year FROM Transaction ORDER BY year DESC;";
                                include("lib/show_queries.php");
                                $result = mysqli_query($db, $query);
                                    foreach($result as $year) {
                                ?>
                                <option value="<?php echo $year['year']; ?>"><?php echo $year['year']; ?></option>
                                <?php
                                    }
                                ?>
                            </select>
                            <select name="month">
                                <?php
                                $query = "SELECT DISTINCT month(transaction_date) AS month FROM Transaction ORDER BY month;";
                                include("lib/show_queries.php");
                                $result = mysqli_query($db, $query);
                                    foreach($result as $month) {
                                ?>
                                <option value="<?php echo $month['month']; ?>"><?php echo $month['month']; ?></option>
                                <?php
                                    }
                                ?>
                            </select>
                            <input type="submit" name="submit" value="View Report">
                        </form>
                        <?php
                            if ($_SERVER['REQUEST_METHOD'] == "GET" and isset($_GET['submit'])) {
                                $year = $_GET['year'];
                                $month = $_GET['month'];
                                echo '<div class="subtitle">' . "State with Highest Volume for each Category (" . $year . "-" . $month . ")" . '</div>';
                                $query = "SELECT a.category_name,b.state,a.max_sold FROM
                                        (SELECT category_name,  max(total_sold) as max_sold
                                        FROM (SELECT sum(t.quantity) AS total_sold,  s.state, c.category_name
                                        FROM transaction t, store s, category c
                                        WHERE t.store_number=s.store_number
                                        AND t.product_ID=c.product_ID
                                        AND year(t.transaction_date)=$year
                                        AND month(t.transaction_date)=$month
                                        GROUP BY c.category_name, s.state
                                        order by c.category_name,total_sold desc, s.state) temp1
                                        GROUP BY category_name) a
                                        INNER JOIN (SELECT sum(t.quantity) AS total_sold,  s.state, c.category_name
                                        FROM transaction t, store s,  category c
                                        WHERE t.store_number=s.store_number
                                        AND t.product_ID=c.product_ID
                                        AND year(t.transaction_date)=$year
                                        AND month(t.transaction_date)=$month
                                        GROUP BY c.category_name, s.state
                                        order by c.category_name,total_sold desc, s.state) b
                                        ON a.category_name=b.category_name
                                        AND a.max_sold=b.total_sold";
                                include("lib/show_queries.php");
                                $result = mysqli_query($db, $query);

                                $all_property = array();
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
                                        echo '<td><a href="store_detail.php?year=' . $year . '&month=' . $month . '&category_name=' . $row['category_name'] . '&state=' . $row['state'] . '">Store Details</a></td>';
                                        echo '</tr>';
                                }
                                echo "</table>";
                            } else {
                                echo '<div class="subtitle">' . "State With Highest Volume for each Category" . '</div>';
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
