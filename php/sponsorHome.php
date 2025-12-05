<!DOCTYPE html>
<html>
    <head>
        <title>Team 25 Webpage</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../style.css">
    </head> 
    <?php session_start(); ?>
    <body>
        <header>
            <?php echo "<h1>Welcome " . $_SESSION['User'] . "</h1>"; ?>
            <form method="post">
                <input type="submit" value="Profile" name="button1" class="button">
                <input type="submit" value="Logout" name="button2" class="button">
                <input type="submit" value="Edit Profile" name="button3" class="button">
                <input type="submit" value="View Applications" name="apps" class="button">
                <input type="submit" value="View Transactions" name="trans" class="button">
                <!-- <input type="submit" value="Orders" name="view_orders" class="button">-->
                <!-- <input type="submit" value="Manage Points" name="points" class="button"> -->
				<input type="submit" value="View Catalogs" name="catalogs" class="button">
                <input type="submit" value="Logs" name="view_log" class="button">
                <input type="submit" value="Driver view" name="d_view" class="button">
                <?php if($_SESSION['driver_view'] == 1)
                {?>
                    <input type="submit" value="Exit Sponsor View" name="e_sview" class="button"><?php
                }?>
            </form>
            <br>
        </header>
        <?php 

        $servername = "team25-rds.cobd8enwsupz.us-east-1.rds.amazonaws.com";
        $username = "admin";
        $password = "performancepineapple25";
        $dbname = "team_25_database";
        $conn = new mysqli($servername, $username, "performancepineapple25", $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        //user session established
        if(isset($_SESSION['User']))
        {
            $sql = "SELECT * FROM users WHERE username='" . $_SESSION['User'] . "'";;
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            if($row['user_type'] == 'sponsor' || $row['user_type'] == 'admin')
            {
                //account page
                if(isset($_POST['button1']))
                {
                    include("userProfile.php");
                }
                //logout page
                if(isset($_POST['button2']))
                {
                    include('logout.php');
                }
                //edit profile
                if(isset($_POST['button3']))
                {
                    include('editProfile.php');
                }   
                //for editing orders
                if(isset($_POST['view_orders'])){

                    include('orders.php');
                }
                //for viewing logs
                if(isset($_POST['view_log']) || isset($_POST['log_names']) || isset($_POST['log_org'])){
                    include('viewLogs.php');
                }
                //sponsor view all driver applications 
                if(isset($_POST['apps']) || isset($_POST['pending']) || isset($_POST['accepted'])  || isset($_POST['submit']) || isset($_POST['accept']))
                {
                    ?>
                    <div id='sponsor-apps'>
                    <h2 id='title'>Search For:</h2>
                    <form method="POST">
                        <br>
                        <input type="submit" value="Pending Applications" name="pending" class="button">
                        <input type="submit" value="Accepted Drivers" name="accepted" class="button">
                        <br>
                        <br>
                    </form>
                </div>
                <br>
                    <?php
                    if(isset($_POST['pending']) || isset($_POST['accepted']) || isset($_POST['submit']) || isset($_POST['accept']))
                    {
                        include('sponsorApps.php');
                    }
                }
                if(isset($_POST['trans']) || isset($_POST['driver_trans'])
                 || isset($_POST['view_all']) || isset($_POST['search'])
                 || isset($_POST['add_trans']) || isset($_POST['add_deposit'])
                 || isset($_POST['add_Withdrawal'])){

                    include('viewTransactions.php');
                }
                if(isset($_POST['d_view']))
                {
                    $_SESSION['driver_view'] = 1;
                    header("location:driverHome.php");
                }
                if($_SESSION['driver_view'] == 1)
                {
                    if(isset($_POST['e_sview']))
                    {
                        $_SESSION['driver_view'] = 0;
                        if( $_SESSION['user_type'] == 'admin')
                        {
                            header("location:adminHome.php");
                        }
                    }
                }

            }     
            else
            {
                header("location:../index.php");
            }   
        }
        //to change username//directed from button3
        //Sends username in $POST with variable userID
        if(isset($_POST['change_username']) || isset($_POST['username_change_submit']) 
        || isset($_POST['change_password']) || isset($_POST['password_change_submit']) 
        || isset($_POST['change_name']) || isset($_POST['change_name_submit'])
        || isset($_POST['remove_driver']) || isset($_POST['remove_driver_submit'])){
            include("changeUserPass.php");
        }    
        
		if(isset($_POST['catalogs'])) {
			include("catalogs.php");
		}        
        
        if(isset($_POST["add_order"]) || isset($_POST["add_order_submit"])){
            include("ordersHelpers/addOrders.php");
        }

        ?>
    </body>
</html>