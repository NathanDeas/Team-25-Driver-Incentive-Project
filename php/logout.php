<?php
    // echo "Logout.php opened";

    // $username = $_POST['username'];
    // $password = $_POST['password'];

    // echo "Username: {$username}";
    // echo "<br>";
    // echo "Password: {$password}";
    // echo "<br>";
    $conn = new mysqli($servername, $username, "performancepineapple25", $dbname);
    
    $sqlLog = "INSERT INTO log (user_id, message, audit_type) VALUES (" . $_SESSION['user_id'] . ", " . "'logout', " . 1 . ");";
    $conn->query($sqlLog);

    session_start();
    //destroy the session
    session_unset();
    //redirect to login page
    header("location: ../index.php");
?>