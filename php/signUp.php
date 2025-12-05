<?php
    session_start();
    
    $username = test_input($_POST['username']);
    $password = test_input($_POST['password']);
    $fullname = test_input($_POST['fullname']);

    function test_input($data) {
        // Strip HTML Tags
        $data = strip_tags($data);
        // Clean up things like &amp;
        $data = html_entity_decode($data);
        // Strip out any url-encoded stuff
        $data = urldecode($data);
        // Replace non-AlNum characters with space
        $data = preg_replace('/[^A-Za-z0-9]/', ' ', $data);
        // Replace Multiple spaces with single space
        $data = preg_replace('/ +/', ' ', $data);
        // Trim the string of leading/trailing space
        $data = trim($data);

        return $data;
    }
    echo "Username: " . $username . "<br>";
    echo "Fullname: " . $fullname . "<br>";

    $servername = "team25-rds.cobd8enwsupz.us-east-1.rds.amazonaws.com";
    $dbusername = "admin";
    $dbpassword = "performancepineapple25";
    $dbname = "team_25_database";

    if(isset($_POST['sign_up'])) {
        //Check if all fields are filled out
        if(empty($username))
            echo "<h2>Error: Username empty</h2>";
        else if(empty($fullname))
            echo "<h2>Error: Full Name empty</h2>";
        else if(empty($password))
            echo "<h2>Error: Password empty</h2>";
        else {
            //Connect to database
            $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            //Query database for matching usernames
            $sql = "SELECT * FROM users WHERE username = '" . $username . "'";
            $result = $conn->query($sql);
            
            //Check if username already exists
            if($result->num_rows == 0) {
                //Check the password is more than 4 characters
                if(strlen($password) >= 4) {
                    //Check that username and password aren't the same
                    if(strcmp($username, $password) != 0) {
                        //Create password hash
                        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                        //Store values into user table
                        $accountValues = "VALUES ('driver', '". $username. "', '". $hashedPassword. "', '". $fullname. "');";
                        $sqlInsertAccount = "INSERT INTO users (user_type,username,hashed_password,fullname) " . $accountValues;
                        $result = $conn->query($sqlInsertAccount);
                        //Check for success
                        if($result){
                            echo "<h2>Account Creation Successful</h2>";
                            //for logging
                            $sqlLog = "SELECT * FROM users WHERE username='" . $username . "';";
                            $sqlLog_result = $conn->query($sqlLog);
                            $rowLog = $sqlLog_result->fetch_assoc();
                            $sqlLog = "INSERT INTO log (user_id, message, audit_type) VALUES (" . $rowLog['userID'] . ", " . "'account_created', " . 0 . ");";
                            $conn->query($sqlLog);
                        } else {
                            echo "<h2>Error: " . $conn->error . "</h2>";
                        }  
                    } else {
                        echo "<h2>Error: Username and Password cannot be the same</h2>";
                    }
                } else {
                    echo "<h2>Error: Password must be longer than 3 characters</h2>";
                }
            } else {
                echo "<h2>Error: Username already exists</h2>";
            }
        }
    }

?>