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
  $catName = $_POST['catName'];

  $sql = "UPDATE org_catalogs SET active = 0 WHERE org_id = '$orgID'";
  if ($conn->query($sql)!== TRUE) {
    echo "Error: ". $sql. "<br>". $conn->error;
  }

  $sql = "UPDATE org_catalogs SET active = 1 WHERE cat_name = '$catName' AND org_id = '$orgID'";
  if ($conn->query($sql)!== TRUE) {
    echo "Error: ". $sql. "<br>". $conn->error;
  }

  echo "Successfully Updated.";