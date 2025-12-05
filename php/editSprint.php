<?php
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check database connection
	if ($conn->connect_error) {
			$message = "Connection failed: " . $conn->connect_error;
	} else {
    $update = "UPDATE about ";
    $ver = $_POST['version'];
    ($_POST['teamNum']) ? $teamnumber = "team_number=".$_POST['teamNum'] : $teamnumber = "";
    ($_POST['productName'])? $prodName = "product_name=".$_POST['productName'] : $prodName = "";
    ($_POST['prodDesc'])? $prodDesc = "product_description=".$_POST['prodDesc'] : $prodDesc = "";

    if ($teamnumber!="" && ($prodName!="" || $prodDesc!="")) $teamnumber.", ";
    if ($prodName!="" && $prodDesc!="") $prodName.", ";

    $set = "SET ".$teamnumber.$productName.$productDesc;
    
    $where = "WHERE version='".$_POST['version']."';";

    $message = $update.$set.$where;
  }
  //   $sqlvalues = "VALUES ('". $_POST['version']. "', '". $_POST['teamNum']. "', '". $_POST['releaseDate']. "', '". $_POST['productName']. "', '". $_POST['prodDesc']. "');";

  //   $sql = "UPDATE about SET version='". $_POST['version']. "', teamNum='". $_POST['teamNum']. "', releaseDate='". $_POST['releaseDate']. "', productName='". $_POST[];
    
  //   try {
  //       $result = $conn->query($sql);
  //       $message = "New Sprint Added";
  //   } catch (mysqli_sql_exception $e) {
  //       $message = "Error: <br>". $e->getMessage();
  //   }
	// }

	$conn->close();