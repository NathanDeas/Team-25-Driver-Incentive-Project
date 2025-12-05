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
                <input type="submit" value="View Transactions" name="trans" class="button">
                <input type="submit" value="Apply!" name="apply" class="button">
                <input type="submit" value="Catalog" name="catalog" class="button">
                <?php if($_SESSION['driver_view'] == 1)
                {?>
                    <input type="submit" value="Exit Driver View" name="e_dview" class="button"><?php
                }?>
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
            if(isset($_POST['button3'])){
                if($_SESSION['driver_view'] == 1)
                {
                    echo '<div id="edit-profile"><h2 id="message">Cannot edit profile in driver view</h2></div>';
                }
                else{
                    include('editProfile.php');
                }
            }
            if(isset($_POST['apply']) || isset($_POST['org_submit'])){

                include('driverApply.php');
            }
            if(isset($_POST['trans']))
            {
                include('viewTransactions.php');
            }
            if($_SESSION['driver_view'] == 1)
            {
                if(isset($_POST['e_dview']))
                {
                    $_SESSION['driver_view'] = 0;
                    if( $_SESSION['user_type'] == 'admin')
                    {
                        header("location:adminHome.php");
                    }
                    if( $_SESSION['user_type'] == 'sponsor')
                    {
                        header("location:sponsorHome.php");
                    }
                }
            }
            


        }
        //to change username//directed from button3
        //Sends username in $POST with variable userID
        if(isset($_POST['change_username']) || isset($_POST['username_change_submit']) 
        || isset($_POST['change_password']) || isset($_POST['password_change_submit']) 
        || isset($_POST['change_name']) || isset($_POST['change_name_submit'])
        ){
            include("changeUserPass.php");
        } 

        if(isset($_POST['catalog']) || isset($_POST['viewCart']) || isset($_POST['purchaseCart'])) {
            include("catalog.php");
        }

        ?>
    </body>
</html>