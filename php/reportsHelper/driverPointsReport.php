

<!DOCTYPE html>
<html>
    <body>
    <div id="reports">
    <h3>Generate Driver Points Report</h3>

        <?php

        if($_SESSION['user_type'] == 'admin' && isset($_POST['driver_points_report'])){
            //conditional vairable
            $driver_transactions = "SELECT * FROM log ORDER BY audit_id DESC";

            //specialed based on user type
            $findDrivers = "SELECT * FROM users WHERE user_type='driver';";
            $allDrivers = $conn->query($findDrivers);

            while($driverArray[] = $allDrivers->fetch_object());
            array_pop($driverArray);
            ?>
            <form method="POST"> 
                User: 
                <select name="report_names">
                    <option>All</option> 
                    <?php foreach($driverArray as $option) : ?>
                        <option value="<?php echo $option->userID; ?>"><?php echo $option->userID . " " . $option->username; ?></option>
                    <?php endforeach; ?>
                </select>
                <br><br>
                <b>Optional Parameter for Specific Users:<b><br><br>
                From:
                <input type="date" name="date_from" value="2023-08-27" />
                To:
                <input type="date" name="date_to" value="<?php  echo date('Y-m-d'); ?>" /><br><br>
                <input type="submit" value="Generate CSV"><br><br>

            </form>
        <?php
        }
        else if($_SESSION['user_type'] == 'admin' && isset($_POST['report_names'])){
            
            $_SESSION['report_names'] = $_POST['report_names'];
            $_SESSION['date_from'] = $_POST['date_from'];
            //adjust date to to actually include the date 
            $dateFrom = $_POST['date_to'];
            $dateFrom = strtotime($dateFrom . ' +1 day');
            $_SESSION['date_to'] =  date('Y-m-d', $dateFrom);
            
        
            header("Location: reportsHelper/driverPointsReport2.php");

            $dateFrom = $_POST['date_from'];
            $dateTo = $_POST['date_to'];
            echo $_POST['report_names'] . "<br><br>";


            if($_POST['report_names'] == "All"){
                
                
                /*if(strcasecmp($dateFrom, $dateTo) == 0){
                    
                    $newDateTo = new DateTime($dateTo);
                    $newDateTo = $newDateTo->modify('+1 day');
                    echo $newDateTo->format('Y-m-d');*/
                    //print  
                    $allDrivers = "SELECT * FROM users u JOIN user_org uo ON u.userID = uo.userID WHERE u.user_type='driver';";
                    $result = $conn->query($allDrivers);
                    if($result->num_rows == 0)
                    {
                        echo '<h3>No drivers found</h3><br>';
                    }
                    else{
                        
                        echo "REACH";
                        $result = $conn->query($allDrivers);

                        if($result->num_rows == 0)
                        {
                            echo 'No logs';
                        }
                        else
                        {
                            ?>
                            <table style="margin-left:auto;margin-right:auto;">
                                <tr>
                                <th>Username</th>
                                <th>OrgID</th>
                                <th>Point Total</th>
                                </tr>
                            <?php
                            while($row2 = $result->fetch_assoc())
                            {
                                ?>
                                <tr>
                                    <td><?php echo $row2['username'] ?></td>
                                    <td><?php echo $row2['orgID'] ?></td>
                                    <td><?php echo $row2['point_total'] ?></td>
                                <?php
                            }
                        }
                    }


               // }
            }
            else{

            }

        }




        ?>
    </div>
    </body>
</html>