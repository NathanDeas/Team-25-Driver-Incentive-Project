<?php

    session_start(); 
    $servername = "team25-rds.cobd8enwsupz.us-east-1.rds.amazonaws.com";
    $username = "admin";
    $password = "performancepineapple25";
    $dbname = "team_25_database";

    $conn = new mysqli($servername, $username, $password, $dbname);
   /* echo $_SESSION['report_type'];
    echo $_SESSION['report_name'];
    echo $_SESSION['report_org'];
    echo $_SESSION['date_from'];
    echo $_SESSION['date_to'];*/

    //for report on all drivers
    if($_SESSION['report_type'] == "user" && $_SESSION['user_type'] == 'admin'){

        if($_SESSION['report_name'] == "All"){
            

        }
        else{
            $driverData = "SELECT * FROM transactions t 
                    WHERE message LIKE 'Purchase:%' 
                    AND driver_id = " . $_SESSION['report_name'] . " 
                    AND t.trans_date >= '" . $_SESSION['date_from'] . "' 
                    AND t.trans_date < '" . $_SESSION['date_to'] . "'";
            $result = $conn->query($driverData);

            // Fetch data from the database
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = array(
                    'Date' => $row['trans_date'],
                    'Purchase' => $row['message'],
                    'Price' => $row['amount']
                );
            }
        
            $headers = array('Date', 'Purchase', 'Price');
        
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

        
       /* $allDrivers = "SELECT * FROM users u JOIN user_org uo ON u.userID = uo.userID WHERE u.user_type='driver' ORDER BY u.username;";
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
        }*/
    }


?>