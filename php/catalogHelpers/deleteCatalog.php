<?php
  $catName = $_POST['catName'];

  if ($catName == "Default") {
    echo "Cannot delete default catalog.";
    return;
  }

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

  $sql = "SELECT * FROM org_catalogs WHERE cat_name = '$catName' AND org_id = '$orgID'";
  $result = $conn->query($sql);
  $result = $result->fetch_assoc();

  if ($result['active']) {
    $sql = "UPDATE org_catalogs SET active = 1 WHERE org_id = '$orgID' AND cat_name = 'Default'";
    if ($conn->query($sql)!== TRUE) {
      echo "Error: ". $sql. "<br>". $conn->error;
    }
  }

  $sql = "DELETE FROM org_catalogs WHERE org_id = '$orgID' AND cat_name = '$catName'";
  if ($conn->query($sql)!== TRUE) {
    echo "Error: ". $sql. "<br>". $conn->error;
  } else {
    echo "Successfully Deleted.";
  }