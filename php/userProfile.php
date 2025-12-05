<?php
    echo "<div id='profile'>";
    echo "<h1>User Profile</h1>";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        $message = "Connection failed: " . $conn->connect_error;
    } else {
        $sql = "SELECT * FROM users WHERE username='" . $_SESSION['User'] . "'";;
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $userType = $row["user_type"];
        $userType[0] = strtoupper($userType[0]);
        echo "<p><strong>Full Name:</strong> ". $row["fullname"] ."</p>";
        echo "<p><strong>Username: </strong>". $_SESSION['User'] ."</p>";
        echo "<p><strong>User Type: </strong>". $userType ."</p>";
        echo "<p><strong>Organizations:</strong></p>";
        $userID = $row["userID"];
        $sql = "SELECT * FROM user_org WHERE userID='" . $userID . "'";;
        $result = $conn->query($sql);
        if( $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $sql = "SELECT * FROM organizations WHERE orgID='" . $row["orgID"] . "'";;
                $currentResult = $conn->query($sql);
                $currentRow = $currentResult->fetch_assoc();
                echo "<p>Organization ID: " . $currentRow["orgID"] ." - ". $currentRow["org_name"] ."</p>";
            }
        }

        echo "<p><strong>Point Totals:</strong></p>";
        $sql = "SELECT * FROM user_org WHERE userID='" . $userID . "'";;
        $result = $conn->query($sql);
        if( $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<p>Organization ID: " . $row["orgID"] ." - ". $row["point_total"] ." points</p>";
            }
        }
        echo "</div>";
    }
?>