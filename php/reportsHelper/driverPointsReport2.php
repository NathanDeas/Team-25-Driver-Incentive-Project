<?php
    session_start(); 
    $servername = "team25-rds.cobd8enwsupz.us-east-1.rds.amazonaws.com";
    $username = "admin";
    $password = "performancepineapple25";
    $dbname = "team_25_database";

    $conn = new mysqli($servername, $username, $password, $dbname);

   
    //for report on all drivers
    if($_SESSION['report_names'] == "All"){

        $allDrivers = "SELECT * FROM users u JOIN user_org uo ON u.userID = uo.userID WHERE u.user_type='driver' ORDER BY u.username;";
        $result = $conn->query($allDrivers);
        
        if ($result->num_rows == 0) {
            echo '<h3>No drivers found</h3><br>';
        } else {
            // Fetch data from the database
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = array(
                    'Username' => $row['username'],
                    'OrgID' => $row['orgID'],
                    'Point Total' => $row['point_total']
                );
            }
        
            $headers = array('Username', 'OrgID', 'Point Total');
        
            $csvFileName = "driverPoints.csv";
        
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $csvFileName . '"');
        
            $output = fopen('php://output', 'wb');
        
            fputcsv($output, $headers);
        
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
        
            fclose($output);
        
            $conn->close();
        }
    }
    //report on points of specific drivers
    else{

        //lol
        //combines logs, transactions and users
        //accounts for difference in ~1 second between transaction time and log time of action
        $driverPointsHistory = "SELECT t.driver_id, u.username, t.action, t.amount, t.message, t.trans_date, l.superior_id 
                                FROM transactions t JOIN 
                                users u ON t.driver_id = u.userID JOIN 
                                log l ON l.user_id = t.driver_id AND 
                                    t.trans_date >= l.dt_stamp - INTERVAL 1 SECOND AND 
                                    t.trans_date <= l.dt_stamp + INTERVAL 1 SECOND
                                WHERE t.driver_id = " . $_SESSION['report_names'] . " AND   #date has to be in quotes
                                    t.trans_date >= '" . $_SESSION['date_from'] . "' AND
                                    t.trans_date < '" . $_SESSION['date_to'] . "'".
                                    "ORDER BY t.trans_date DESC; ";


        $result = $conn->query($driverPointsHistory);

        if ($result->num_rows == 0) {
            echo '<h3>No data found</h3><br>';
        } else {
            //get all transactions for user
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = array(
                    'DriverID' => $row['driver_id'],
                    'Username' => $row['username'],
                    'Action' => $row['action'],
                    'Amount' => $row['amount'],
                    'Message' => $row['message'],
                    'Date/Time' => $row['trans_date'],
                    'SuperiorID' => $row['superior_id']
                );
            }

            //get total points in all orgs for user
            $driverTotal = "SELECT * FROM users u JOIN user_org uo ON u.userID = uo.userID WHERE u.user_type='driver' AND u.userID=" . $_SESSION['report_names'];
           
            $result = $conn->query($driverTotal);
            $data2 = array();
            while ($row = $result->fetch_assoc()) {
                $data2[] = array(
                    '',
                    '',
                    '',
                    '',
                    'Username' => $row['username'],
                    'OrgID' => $row['orgID'],
                    'Point Total' => $row['point_total']
                );
            }
        
        
            $headers = array('DriverID', 'Username', 'Action', 'Amount', 'Message', 'Date/Time', 'SuperiorID');
            $headers2 = array('', '', '', '', 'Username', 'OrgID', 'Point Total');
            $csvFileName = "driverPoints.csv";
        
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $csvFileName . '"');
        
            $output = fopen('php://output', 'wb');
        
            fputcsv($output, $headers);
            foreach ($data as $row) {
                fputcsv($output, $row);
            }

            fputcsv($output, $headers2);
            foreach ($data2 as $row){
                fputcsv($output, $row);
            }


        
            fclose($output);
        
            $conn->close();
        }

    }



?>