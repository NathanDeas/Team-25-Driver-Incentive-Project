<!DOCTYPE html>
<html>
    <head>
        <title>Team 25 Webpage</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <header>
        <h1>Driver Incentive Service Webpage</h1>
            <form method="post">
                <input type="submit" value="Home" name="button1" class="button">
                <input type="submit" value="Add New Sprint" name="button2" class="button">
                <input type="submit" value="Edit Sprint" name="button3" class="button">
                <input type="submit" value="Log In" name="button6" class="login_button">
                <input type="submit" value="Driver Sign Up" name="button7" class="button">
                <br>
                <br>
            </form>
    </header>
    <body>
        <div id="main-content">
        <?php
            $message = "";
            $versions = array();

            $servername = "team25-rds.cobd8enwsupz.us-east-1.rds.amazonaws.com";
            $username = "admin";
            $password = "performancepineapple25";
            $dbname = "team_25_database";

            if(isset($_POST['button1'])) 
            {
                // Create database connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check database connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Query the database
                $sql = "SELECT * FROM about ORDER BY version DESC";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $sprNum = $row["version"];
                $teamNum = $row["team_num"];
                $releaseDate = $row["release_date"];
                $product = $row["product_name"];
                $prodDesc = $row["product_description"];?>
                <div id="homepage">
                    <h2><?php echo $product?></h2>
                    <h3><?php echo $prodDesc?></h3>
                    <h3>Team Members:</h3>
                    <ul style="list-style-type:none;padding:0;margin:0;">
                        <lr>Michael Harris</lr><br>
                        <lr>Thomas Personett</lr><br>
                        <lr>Nathan Deas</lr><br>
                        <lr>Tirth Patel</lr><br>
                    </ul>
                    <h3>Team Number: <?php echo $teamNum?></h3>
                    <h3>Current Sprint Number: <?php echo $sprNum?></h3><?php
                
                    // Display results as a table
                    if ($result->num_rows > 0) {
                        ?>
                        <h2 id="title">About Page Table</h2>
                        <br>
                        <?php
                        $sql = "SELECT * FROM about ORDER BY version DESC";
                        $result = $conn->query($sql);
                        ?>
                        <table id="about-table">
                            <tr>
                                <th> Version Number </th>
                                <th> Release Date </th>
                                <th> Product Name </th>
                                <th> Description </th>
                            </tr><?php
                            while($row = $result->fetch_assoc())
                            {?>
                            <tr>
                                <td><?php echo $row["version"] ?></td>
                                <td><?php echo $row["release_date"] ?></td>
                                <td><?php echo $row["product_name"] ?></td>
                                <td><?php echo $row["product_description"] ?></td>
                            </tr>
                            <?php
                            }
                        // while($row = $result->fetch_assoc()) {
                        // echo "<tr>";
                        // foreach ($row as $key => $value) {
                        //     echo "<td>" . $value. "</td>";
                        // }
                        // echo "</tr>";
                    }
                    echo "</table>";
                    // else {
                    //     echo "No Result Returned";
                    // }

                    $conn->close();
                    ?></div><?php
            }

            if(isset($_POST['button2']) || isset($_POST['submit_form'])) {
                if (isset($_POST['submit_form'])) {
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Check database connection
                    if ($conn->connect_error) {
                        $message = "Connection failed: " . $conn->connect_error;
                    } else {
                        $sqlvalues = "VALUES ('". $_POST['version']. "', '". $_POST['teamNum']. "', '". $_POST['releaseDate']. "', '". $_POST['productName']. "', '". $_POST['prodDesc']. "');";

                        $sql = "INSERT INTO about (version, team_num, release_date, product_name, product_description) " . $sqlvalues;
                       
                        try {
                            $result = $conn->query($sql);
                            $message = "New Sprint Added";
                        } catch (mysqli_sql_exception $e) {
                            $message = "Error: <br>". $e->getMessage();
                        }
                    }

                    $conn->close();

                }
                // Insert Form here (Text fields for each table entry, if empty do not update that field)?>
                    <div id="add_sprint_entry">
                    <h2>Add Sprint Entry</h2>
                    <form action="" method="POST" target="_blank">
                        <label for="version">Version:</label>
                        <input type="text" required="required" id="version" name="version" value="3"><br>
                        <label for="teamNum">Team number:</label>
                        <input type="text" required="required" id="teamNum" name="teamNum" value="25"><br>
                        <label for="releaseDate">Release date:</label>
                        <input type="text" required="required" id="releaseDate" name="releaseDate" value="2023-09-26"><br>
                        <label for="productName">Product name:</label>
                        <input type="text" required="required" id="productName" name="productName" value="Driver Incentive Service Webpage"><br>
                        <label for="prodDesc">Product description:</label>
                        <input type="text" required="required" id="prodDesc" name="prodDesc" value="Product Description"><br>
                        <div class="form-bottom">
                            <p class="message" name="message" style="width: 100%"><?php echo $message?></p>
                            <input type="submit" name="submit_form" class="button">
                        </div>
                    </form>
                </div><?php

            }
            
            if (isset($_POST['button3']) || isset($_POST['submit_form_edit'])) {
                $e_teamNum = "";
                $e_version = "";
                $e_releaseDate = "";
                $e_productName = "";
                $e_prodDesc = "";

                if (isset($_POST['submit_form_edit'])) {
                    $conn = new mysqli($servername, $username, $password, $dbname);
                    
                    // Check database connection
                    if ($conn->connect_error) {
                        $message = "Connection failed: " . $conn->connect_error;
                    } else {
                        $update = "UPDATE about ";
                        $ver = $_POST['version'];
                        if ($_POST['teamNum']) $e_teamNum = "team_num='".$_POST['teamNum']."'";
                        if ($_POST['releaseDate']) $e_releaseDate = "release_date='".$_POST['releaseDate']."'";
                        if ($_POST['productName']) $e_productName = "product_name='".$_POST['productName']."'";
                        if ($_POST['prodDesc']) $e_prodDesc = "product_description='".$_POST['prodDesc']."'";
                        if ($e_teamNum!="" && ($e_productName!="" || $e_prodDesc!="" || $e_releaseDate)) $e_teamNum=$e_teamNum.", ";
                        if ($e_releaseDate!="" && ($e_productName!="" || $e_prodDesc!="")) $e_releaseDate=$e_releaseDate.", ";
                        if ($e_productName!="" && $e_prodDesc!="") $e_productName=$e_productName.", ";

                        $set = "SET ".$e_teamNum.$e_releaseDate.$e_productName.$e_prodDesc;
                        
                        $where = " WHERE version='".$_POST['version']."';";

                        $sql = $update.$set.$where;

                        try {
                            $conn->query($sql);
                            $message = "Sprint Updated";
                        } catch (mysqli_sql_exception $e) {
                            $message = "Error: <br>". $e->getMessage();
                        }
                    }

                    $conn->close();
                }
                // Insert Form here (Text fields for each table entry, if empty do not update that field)?>
                <div id="sprint_edit">
                    <h2>Edit Sprint Entry</h2>
                    <form action="$_SERVER['PHP_SELF']" method="POST" target="_blank">
                        <label for="version">Version:</label>
                        <select name="version">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                        </select><br>
                        <label for="teamNum">Team number:</label>
                        <input type="text" id="teamNum" name="teamNum" placeholder="25"><br>
                        <label for="releaseDate">Release date:</label>
                        <input type="text" id="releaseDate" name="releaseDate" placeholder="2023-09-26"><br>
                        <label for="productName">Product name:</label>
                        <input type="text" id="productName" name="productName" placeholder="Driver Incentive Service Webpage"><br>
                        <label for="productDesc">Product description:</label>
                        <input type="text" id="prodDesc" name="prodDesc" placeholder="Product Description"><br>
                        <p><?php echo $message?></p>
                        <input type="submit" value="Submit" name="submit_form_edit" class="button">
                    </form>
                <?php
            }


            //Find all driver orders
            if(isset($_POST['button4']) || isset($_POST['find_order'])){
                //if find order button has been pressed
                if (isset($_POST['find_order'])) {
                    include("php/driverSearchOrders.php");
                }
                //takes input
                else{
                    echo "<h2>Find Order</h2>";
                    //Enter driver ID and get list of all orders?>
                    <form action="" method="POST" target="_blank">
                        <label for="DriverID">DriverID:</label>
                        <input type="text" id="driverID" name="driverID" placeholder="69"><br>
                        <br>
                        <input type="submit" name="find_order" class="button" value="Find Order">
                    </form><?php
                }
            }

            if(isset($_POST['button6']) || isset($_POST['log_in'])){
                if (isset($_POST['log_in'])) {
                    include("php/process.php");
                }
                else{
                ?>
                <div id="login">
                    <h1>Account Login</h1>
                    <form action="" method="post">
                        <input type="text" name="username" placeholder="Username" id="username" required><br>
                        <input type="password" name="password" placeholder="Password" id="password" required><br><br>
                        <input type="submit" name="log_in" class="button" value="Login" id="log_button">
                        <input type="submit" value="Sign Up" name="button7" class="button">
                    </form>
                </div>
                <?php
                }
            }

            if(isset($_POST['button7']) || isset($_POST['sign_up'])){
                if (isset($_POST['sign_up'])) {
                    include("php/signUp.php");
                }
                else{
                ?>
                <div id="create_account">
                    <h1>Driver Account Sign Up</h1>
                    <h4>Please enter the fields below to create your driver account</h4>
                    <form action="" method="post">
                        <input type="text" name="fullname" placeholder="Type Your Full Name" id="fullname"><br>
                        <input type="text" name="username" placeholder="Type A Username" id="username"><br>
                        <input type="password" name="password" placeholder="Type A Password" id="password"><br><br>
                        <input type="submit" name="sign_up" class="button" value="Sign Up"><br>
                    </form>
                </div>
                <?php
                }
            }
            
        ?>
        </div>
        <div id="footer">
            <p>Team 25 Driver Incentive Website</p>
        </div>
    </body>
</html>
