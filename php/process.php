<?php
    session_start();
    
    $username = test_input($_POST['username']);
    $password = test_input($_POST['password']);

    function test_input($data) {
        // Strip HTML Tags
        $data = strip_tags($data);
        // Clean up things like &amp;
        $data = html_entity_decode($data);
        // Strip out any url-encoded stuff
        $data = urldecode($data);
        // // Replace non-AlNum characters with space
        // $data = preg_replace('/[^A-Za-z0-9]/', ' ', $data);
        // Replace Multiple spaces with single space
        $data = preg_replace('/ +/', ' ', $data);
        // Trim the string of leading/trailing space
        $data = trim($data);

        return $data;
    }

    $servername = "team25-rds.cobd8enwsupz.us-east-1.rds.amazonaws.com";
    $dbusername = "admin";
    $dbpassword = "performancepineapple25";
    $dbname = "team_25_database";

    if(isset($_POST['log_in']))
    {
        if(empty($username) || empty($password))
        {
            echo "<h2>Error: Username or password empty</h2>";
            header("location:index.php");
        }
        else
        {
            $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM users WHERE username = '" . $username . "'";
            $result = $conn->query($sql);

            if($result->num_rows > 0)
            {
                $row = $result->fetch_assoc();
                $hash = $row["hashed_password"];
                if(password_verify($password, $hash))
                {
                    $sqlLog = "INSERT INTO log (user_id, message, audit_type) VALUES (" . $row['userID'] . ", " . "'login_success', " . 1 . ");";
                    $conn->query($sqlLog);
                    $_SESSION['User'] = $username;
                    $_SESSION['user_id'] = $row['userID'];
                    $_SESSION['user_type'] = $row['user_type'];
                    $_SESSION['driver_view'] = 0;
                    if($row['user_type'] == 'admin')
                    {
                        echo $row['user_type'];
                        header("location:php/adminHome.php");
                    }
                    if($row['user_type'] == 'driver')
                    {
                        echo $row['user_type'];
                        header("location:php/driverHome.php");
                    }
                    if($row['user_type'] == 'sponsor')
                    {
                        echo $row['user_type'];
                        header("location:php/sponsorHome.php");
                    }
                }
                else{
                    $sqlLog = "INSERT INTO log (user_id, message, audit_type) VALUES (" . $row['userID'] . ", " . "'login_fail', " . 1 . ");";
                    $conn->query($sqlLog);
                    echo "<div id='message'><h2>Error: Passwords do not match</h2></div>";
                }
            }
            else{
                echo "<div id='message'><h2>Error: Username does not exist</h2></div>";
            }
        }
    }

?>