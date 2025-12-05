<!DOCTYPE html>
<html>
    <body>
    <div id="reports">
    <h3>Generate Sales Report</h3>

        <?php
        if($_SESSION['user_type'] == 'admin' && isset($_POST['sales_report'])){
            //conditional vairable
            $driver_transactions = "SELECT * FROM log ORDER BY audit_id DESC";

            //specialed based on user type
            $findDrivers = "SELECT * FROM users WHERE user_type='driver';";
            $allDrivers = $conn->query($findDrivers);
            $findSponsors = "SELECT * FROM users WHERE user_type='sponsor';";
            $allSponsors = $conn->query($findSponsors);
            $findOrgs = "SELECT * FROM organizations;";
            $allOrgs = $conn->query($findOrgs);

            while($driverArray[] = $allDrivers->fetch_object());
            array_pop($driverArray);
            while($sponsorArray[] = $allSponsors->fetch_object());
            array_pop($sponsorArray);
            while($orgArray[] = $allOrgs->fetch_object());
            array_pop($orgArray);


            ?>
            <form method="POST"> 

                <input type="radio" name="report_type" value="user" checked>
                <label for="user">Driver:</label>
                <select name="report_name">
                    <?php foreach($driverArray as $option) : ?>
                        <option value="<?php echo $option->userID; ?>"><?php echo $option->userID . " " . $option->username; ?></option>
                    <?php endforeach; ?>


                </select>
                    

                <br><br>
                <b>Optional Parameter:<b><br><br>
                From:
                <input type="date" name="date_from" value="2023-08-27" />
                To:
                <input type="date" name="date_to" value="<?php  echo date('Y-m-d'); ?>" /><br><br>
                <input type="submit" value="Generate CSV"><br><br>
                
            </form>
            <?php
        }
        if(isset($_POST['report_type'])){
            $_SESSION['report_type'] = $_POST['report_type'];
            $_SESSION['report_org']= $_POST['report_org'];
            $_SESSION['report_name']= $_POST['report_name'];
            
            $_SESSION['date_from'] = $_POST['date_from'];
            //adjust date to to actually include the date 
            $dateFrom = $_POST['date_to'];
            $dateFrom = strtotime($dateFrom . ' +1 day');
            $_SESSION['date_to'] =  date('Y-m-d', $dateFrom);

            header("Location: reportsHelper/salesReport2.php");
           
        }

        ?>
    </div>
    </body>
</html>