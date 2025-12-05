<?php
  // echo "<pre>";
  // var_dump($_POST);
  // echo "</pre>";
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
  // echo $orgID;
  $catName = $_POST['cat-name'];
  $country = $_POST['country'];
  $sfMT = $_POST['sf-mt'];
  $sfRE = $_POST['sf-re'];
  $seMT = "";
  if (isset($_POST['se-re-mt'])) $seMT .= $_POST['se-re-mt'];
  if (isset($_POST['se-re-a'])) {
    if ($seMT!= "") $seMT.= ',';
    $seMT .= $_POST['se-re-a'];
  }
  $seRE = $_POST['se-re'];
  $active = $_POST['active'];

  //check if cat name is already in database under userid
  $sql = "SELECT * FROM org_catalogs WHERE cat_name = '$catName' AND org_id = '$orgID'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    echo "<script>alert('A catalog entry with that name already exists in this organization. Please choose another name.');</script>";
  } else {
    if ($active) {
      $sql = "UPDATE org_catalogs SET active = 0 WHERE org_id = '$orgID'";
      if ($conn->query($sql) !== TRUE) {
        echo "Error: ". $sql. "<br>". $conn->error;
      }
    }

    //create catalog entry
    $sql = "INSERT INTO org_catalogs (org_id, cat_name, country, sf_mt, sf_re, se_mt, se_re, active)
    VALUES ('$orgID', '$catName', '$country', '$sfMT', '$sfRE', '$seMT', '$seRE', '$active')";
    if ($conn->query($sql) === TRUE) {
      echo "<script>alert('Catalog entry created successfully.');</script>";
    } else {
      echo "Error: ". $sql. "<br>". $conn->error;
    }
  }

  echo "Successfully Added.";
