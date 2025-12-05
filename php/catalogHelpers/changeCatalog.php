<?php
  $servername = "team25-rds.cobd8enwsupz.us-east-1.rds.amazonaws.com";
  $username = "admin";
  $password = "performancepineapple25";
  $dbname = "team_25_database";

  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check database connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $orgID = (int)$_POST['orgID'];
  $userID = (int)$_POST['userID'];

  $sql = "UPDATE user_org SET active_cat = 0 WHERE userID = '$userID';";
  if ($conn->query($sql)!== TRUE) {
    echo "Error: ". $sql. "<br>". $conn->error;
  }

  $sql = "UPDATE user_org SET active_cat = 1 WHERE orgID = '$orgID';";
  if ($conn->query($sql)!== TRUE) {
    echo "Error: ". $sql. "<br>". $conn->error;
  }

  echo "Successfully Updated.";
?>