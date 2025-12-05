<?php
//NEEDED ANOTHER CONNECTION I BELIEVE... SHOULD MAKE A CONNECTION FILE AND JUST CALL INCLUDE
    $servername = "team25-rds.cobd8enwsupz.us-east-1.rds.amazonaws.com";
    $dbusername = "admin";
    $dbpassword = "performancepineapple25";
    $dbname = "team_25_database";
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $var = $_POST['points'];
    $var2 = $_POST['name'];
    $inp = file_get_contents('catCart/cart.json');
    $temp = json_decode($inp);
    if($temp) 
    {
        array_push($temp, [$var2, $var]);
        $data = json_encode($temp);
    }
    else{
        $data = json_encode(array($temp));
    }
    file_put_contents('catCart/cart.json', $data);
    $inp = file_get_contents('catCart/cart.json');
    $temp = json_decode($inp);
    print_r($temp);



    //Insert Catalog transaction into table
    // $catPurchase = "VALUES ('" . $_SESSION['user_id'] . "', 'Withdrawal', '-" . $var . " pts', 'Purchase: " . $var2 . "');";
    // $sqlInsertTrans = "INSERT INTO transactions (driver_id, action, amount, message)" . $catPurchase;
    // $result = $conn->query($sqlInsertTrans);
    // if (!$result) {
    //     echo "Error: " . $conn->error;
    // }
    // if($result)
    // {
    //     echo "Purchase Successfull!";
    // }
    // else
    // {
    //     echo "Error! Please try again.";
    // }
    // $sql = "SELECT * FROM user_org WHERE userID='" . $_SESSION['user_id'] . "'";
    // $result = $conn->query($sql);
    // $row = $result->fetch_assoc();
    // $points = $row['point_total'] - $var;

    // $withdrawal = "UPDATE user_org SET point_total = " . $points . " WHERE userID='" . $_SESSION['user_id'] . "'";
    // $withdrawalResult = $conn->query($withdrawal);

?>