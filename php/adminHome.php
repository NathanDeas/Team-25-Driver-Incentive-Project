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
                <input type="submit" value="Create Accounts" name="button4" class="button">
                <input type="submit" value="View Applications" name="view_apps" class="button">
                <input type="submit" value="View Transactions" name="trans" class="button">
                <input type="submit" value="Logs" name="view_log" class="button">
                <input type="submit" value="Generate Reports" name="reports" class="button">
                <input type="submit" value="Driver View" name="d_view" class="button">
                <input type="submit" value="Sponsor View" name="s_view" class="button">
            </form>
            <br>
        </header>
        <?php 

        //THIS PROBABLY IS NOT ALLOWED
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
            if($row['user_type'] == 'admin')
            {
                //account page
                if(isset($_POST['button1']))
                {
                    include("userProfile.php");
                }
                if(isset($_POST['button2']))
                {
                    include('logout.php');
                }
                //edit profile
                if(isset($_POST['button3']))
                {
                    include('editProfile.php');
                }            
                //create new driver
                if(isset($_POST['button4']) || isset($_POST['create_admin']) || isset($_POST['create_sponsor']) || isset($_POST['create_driver']))
                {
                    include('createAccount.php'); 
                }
                if(isset($_POST['view_apps']) || isset($_POST['pend_apps']) || isset($_POST['acc_apps']))
                {
                    include('viewAllApps.php');
                }
                if(isset($_POST['view_orders'])){
                    include('orders.php');
                }
                if(isset($_POST['trans']) || isset($_POST['driver_trans']) || isset($_POST['view_all']) || isset($_POST['search']) || isset($_POST['add_trans']) || isset($_POST['add_deposit']) || isset($_POST['add_Withdrawal'])){

                    include('viewTransactions.php');
                }
                if(isset($_POST['view_log'])){
                    include('viewLogs.php');
                }
                if(isset($_POST['reports'])){
                    include('reports.php');
                }
                if(isset($_POST['d_view']))
                {
                    $_SESSION['driver_view'] = 1;
                    header("location:driverHome.php");
                }
                if(isset($_POST['s_view']))
                {
                    $_SESSION['driver_view'] = 1;
                    header("location:sponsorHome.php");
                }
                
            }
            else
            {
                header("location:../index.php");
            }

        }
        //to change username
        //directed from button3
        //Sends username in $POST with variable userID
        if(isset($_POST['change_username']) || isset($_POST['username_change_submit']) 
        || isset($_POST['change_password']) || isset($_POST['password_change_submit']) 
        || isset($_POST['change_name']) || isset($_POST['change_name_submit'])
        || isset($_POST['delete_user']) || isset($_POST['delete_user_submit'])
        || isset($_POST['change_org']) || isset($_POST['change_org_submit'])
        || isset($_POST['remove_driver']) || isset($_POST['remove_driver_submit'])){
            include("changeUserPass.php");
        }      
        if(isset($_POST["add_order"]) || isset($_POST["add_order_submit"])){
            include("ordersHelpers/addOrders.php");
        }
        if(isset($_POST['log_names']) || isset($_POST['log_org'])){
            include('viewLogs.php');
        }
        if(isset($_POST['driver_points_report']) || isset($_POST['report_names'])){
            include('reportsHelper/driverPointsReport.php');
        }
        if(isset($_POST['sales_report']) || isset($_POST['report_type'])){
            include('reportsHelper/salesReport.php');
        }

        ?>
    </body>
</html>