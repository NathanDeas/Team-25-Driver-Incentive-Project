<?php
	$conn = new mysqli($_POST['servername'], $username, $password, $dbname);

	// Check database connection
	if ($conn->connect_error) {
			$message = "Connection failed: " . $conn->connect_error;
	} else {
			$sqlvalues = "VALUES ('". $_POST['version']. "', '". $_POST['teamNum']. "', '". $_POST['releaseDate']. "', '". $_POST['productName']. "', '". $_POST['prodDesc']. "');";

			$sql = "INSERT INTO about (version, team_num, release_date, product_name, product_description) " . $sqlvalues;
			
			try {
					$result = $conn->query($sql);
					$message = "New Sprint Added";
			} catch (mysqli_sql_exception $e) {
					$message = "Error: <br>". $e->getMessage();
			}
	}

	$conn->close();
	
?>