<!DOCTYPE html>
<html>
    <body>
    <div id='logs'>
    

    <?php

        if($_SESSION['user_type'] == 'admin'){
            //for deafult log page 
            $driver_transactions = "SELECT * FROM log ORDER BY audit_id DESC";

            //specialed based on user type
            $findDrivers = "SELECT * FROM users;";
            $allDrivers = $conn->query($findDrivers);
            $findOrgs = "SELECT * FROM organizations;";
            $allOrgs = $conn->query($findOrgs);

            while($driverArray[] = $allDrivers->fetch_object());
            while($orgArray[] = $allOrgs->fetch_object());
            array_pop($driverArray);
            array_pop($orgArray);

            //janky php to filter logs by user or org
            echo "<h3>Choose Logs By:</h3>"
            ?>
            <form method="POST"> 
                User: 
                <select name="log_names">
                    <option>All</option> 
                    <?php foreach($driverArray as $option) : ?>
                        <option value="<?php echo $option->userID; ?>"><?php echo $option->userID . " " . $option->username; ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="submit" value="Submit"><br><br>
                Org :
                <select name="log_org">
                    <option>All</option> 
                    <?php foreach($orgArray as $option) : ?>
                        <option value="<?php echo $option->orgID; ?>"><?php echo $option->orgID . " " . $option->org_name; ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="submit" value="Submit"><br><br>

            </form>
            <?php
        }
        else if ($_SESSION['user_type'] == 'sponsor'){
            //default find all logs of users in org
            $logOrgID= "SELECT * FROM user_org WHERE userID =" . $_SESSION['user_id'] . ";";
            $logOrgID = $conn->query($logOrgID);
            $logOrgID = $logOrgID->fetch_assoc();
            $logOrgID = $logOrgID["orgID"];
            
            //default log page for sponsor. everyone in their org
            $driver_transactions = "SELECT l.*
            FROM log l
            JOIN user_org uo ON l.user_id = uo.userID
            WHERE uo.orgID=" . $logOrgID . " ORDER BY audit_id DESC" .";" ;

            //all driver options
            $allDrivers = "SELECT u.*
            FROM users u
            JOIN user_org uo ON u.userID = uo.userID
            WHERE uo.orgID=" . $logOrgID . ";";

            $allDrivers = $conn->query($allDrivers);
            while($driverArray[] = $allDrivers->fetch_object());
            array_pop($driverArray);

            echo "<h3>Choose Logs By:</h3>"
            ?>
            <form method="POST"> 
                User: 
                <select name="log_names">
                    <option>All</option> 
                    <?php foreach($driverArray as $option) : ?>
                        <option value="<?php echo $option->userID; ?>"><?php echo $option->userID . " " . $option->username; ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="submit" value="Submit"><br><br>

            </form>
            <?php


        }


        echo "<h2> Logs </h2><br>";    
        //todo: fix some variable names. code harvested from transactions
        if(isset($_POST['log_names']) && $_POST['log_names'] != "All"){
            $logUserID = $_POST['log_names'];
            $driver_transactions = "SELECT * FROM log WHERE user_id= ". $_POST['log_names'] . " ORDER BY audit_id DESC";
            $result = $conn->query($driver_transactions);
            if($result->num_rows == 0)
            {
                echo '<h3>No logs of user</h3><br>';
            }
            else
            {
                ?>
                <table style="margin-left:auto;margin-right:auto;">
                    <tr>
                    <th>Audit ID</th>
                    <th>User ID</th>
                    <th>Message</th>
                    <th>Date/Time</th>
                    <th>Administrator ID</th>
                    <th>Audit Type</th>
                    </tr>
                <?php
                while($row2 = $result->fetch_assoc())
                {
                    ?>
                    <tr>
                        <td><?php echo $row2['audit_id'] ?></td>
                        <td><?php echo $row2['user_id'] ?></td>
                        <td><?php echo $row2['message'] ?></td>
                        <td><?php echo $row2['dt_stamp'] ?></td>
                        <td><?php echo $row2['superior_id'] ?></td>
                        <td><?php echo $row2['audit_type'] ?></td>
                    <?php
                }
            }
            
        }
        else if(isset($_POST['log_org']) && $_POST['log_org'] != "All"){
            $logOrgID = $_POST['log_org'];

            //joins logs, users and user_org and finds all logs where userOrg is $logOrgID

            $orgUsers = "SELECT l.*
            FROM log l
            JOIN users u ON l.user_id = u.userID
            JOIN user_org uo ON u.userID = uo.userID
            WHERE uo.orgID=" . $logOrgID . " ORDER BY audit_id DESC" .";" ;



            $result = $conn->query($orgUsers);
            if($result->num_rows == 0)
            {
                echo 'No logs';
            }
            else
            {
                ?>
                <table style="margin-left:auto;margin-right:auto;">
                    <tr>
                    <th>Audit ID</th>
                    <th>User ID</th>
                    <th>Message</th>
                    <th>Date/Time</th>
                    <th>Administrator ID</th>
                    <th>Audit Type</th>
                    </tr>
                <?php
                while($row2 = $result->fetch_assoc())
                {
                    ?>
                    <tr>
                        <td><?php echo $row2['audit_id'] ?></td>
                        <td><?php echo $row2['user_id'] ?></td>
                        <td><?php echo $row2['message'] ?></td>
                        <td><?php echo $row2['dt_stamp'] ?></td>
                        <td><?php echo $row2['superior_id'] ?></td>
                        <td><?php echo $row2['audit_type'] ?></td>
                    <?php
                }
            }
            
        }
        else{

            $result = $conn->query($driver_transactions);

            if($result->num_rows == 0)
            {
                echo 'No logs';
            }
            else
            {
                ?>
                <table style="margin-left:auto;margin-right:auto;">
                    <tr>
                    <th>Audit ID</th>
                    <th>User ID</th>
                    <th>Message</th>
                    <th>Date/Time</th>
                    <th>Administrator ID</th>
                    <th>Audit Type</th>
                    </tr>
                <?php
                while($row2 = $result->fetch_assoc())
                {
                    ?>
                    <tr>
                        <td><?php echo $row2['audit_id'] ?></td>
                        <td><?php echo $row2['user_id'] ?></td>
                        <td><?php echo $row2['message'] ?></td>
                        <td><?php echo $row2['dt_stamp'] ?></td>
                        <td><?php echo $row2['superior_id'] ?></td>
                        <td><?php echo $row2['audit_type'] ?></td>
                    <?php
                }
            }
        }

    ?>
    </div>
    </body>
</html>