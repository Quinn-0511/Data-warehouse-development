<?php
include("lib/common.php");
// created by bxu93
//update sql by sliao33, merge city and store
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
                    <div class="subtitle">Store Revenue by Year by State</div>
                    <div class="general_info">Please select a state to query.
                    <form action="<?php echo $_SERVER['php_self']; ?>" method="post">
                        <select name="state">
                                <option value="0">--Select State--</option>
                                <option value="AL">Alabama</option>
                                <option value="AK">Alaska</option>
                                <option value="AZ">Arizona</option>
                                <option value="AR">Arkansas</option>
                                <option value="CA">California</option>
                                <option value="CO">Colorado</option>
                                <option value="CT">Connecticut</option>
                                <option value="DE">Delaware</option>
                                <option value="FL">Florida</option>
                                <option value="GA">Georgia</option>
                                <option value="HI">Hawaii</option>
                                <option value="ID">Idaho</option>
                                <option value="IL">Illinois</option>
                                <option value="IN">Indiana</option>
                                <option value="IA">Iowa</option>
                                <option value="KS">Kansas</option>
                                <option value="KY">Kentucky</option>
                                <option value="LA">Louisiana</option>
                                <option value="ME">Maine</option>
                                <option value="MD">Maryland</option>
                                <option value="MA">Massachusetts</option>
                                <option value="MI">Michigan</option>
                                <option value="MN">Minnesota</option>
                                <option value="MS">Mississippi</option>
                                <option value="MO">Missouri</option>
                                <option value="MT">Montana</option>
                                <option value="NE">Nebraska</option>
                                <option value="NV">Nevada</option>
                                <option value="NH">New Hampshire</option>
                                <option value="NJ">New Jersey</option>
                                <option value="NM">New Mexico</option>
                                <option value="NY">New York</option>
                                <option value="NC">North Carolina</option>
                                <option value="ND">North Dakota</option>
                                <option value="OH">Ohio</option>
                                <option value="OK">Oklahoma</option>
                                <option value="OR">Oregon</option>
                                <option value="PA">Pennsylvania</option>
                                <option value="RI">Rhode Island</option>
                                <option value="SC">South Carolina</option>
                                <option value="SD">South Dakota</option>
                                <option value="TN">Tennessee</option>
                                <option value="TX">Texas</option>
                                <option value="UT">Utah</option>
                                <option value="VT">Vermont</option>
                                <option value="VA">Virginia</option>
                                <option value="WA">Washington</option>
                                <option value="WV">West Virginia</option>
                                <option value="WI">Wisconsin</option>
                                <option value="WY">Wyoming</option>
                        </select>
                        <input type="submit" value="submit">
                    </form>
                    <?php
                        print "select state: ";
                        if(isset( $_POST['state'])){
                            echo $_POST['state'];}
                        else{
                                print "It's empty";
                        }
                        $state = $_POST['state'];
                        // $state = 'NY';
                        // $state = mysqli_real_escape_string($state);
                        $query = "SELECT n.store_number, n.street_address, n.city_name, n.sale_year,
                                        FORMAT(SUM(n.quantity*(case when s.sales_price IS NULL THEN p.retail_price
                                                                ELSE s.sales_price END)),0) as revenue
                                        FROM (SELECT t.*, o.street_address, o.city_name, o.state, YEAR(t.transaction_date) AS sale_year
                                        FROM Store o,  Transaction t
                                        WHERE t.store_number=o.store_number) as n
                                        INNER JOIN Product p
                                        ON n.product_ID=p.product_ID
                                        LEFT JOIN Sales s
                                        ON n.transaction_date=s.sales_date
                                        AND n.product_ID =s.product_ID
                                        WHERE n.state='$state'
                                        GROUP BY n.store_number, n.sale_year
                                        ORDER BY n.sale_year ASC, revenue DESC;";
                        $result = mysqli_query($db, $query);
                        include("lib/show_queries.php");

                        $all_property = array();  //declare an array for saving property
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
