<?php
    //searches and prints orders by driver_id
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check database connection
    if ($conn->connect_error) {
        $message = "Connection failed: " . $conn->connect_error;
    } else {
        //queries database
        $sql = $sql = "SELECT order_id, total_points, dt_stamp FROM orders WHERE user_id='" . $_POST['driverID'] . "'";

        try {
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            //prints if not empty
            if($result->num_rows > 0){

                echo "<h2>All Orders</h2>";
                echo "<table align='center'>";
                echo "<tr>";
                foreach ($row as $key => $value) {
                    echo "<td><strong>" . $key . "</strong></td>";
                }
                echo "</tr>";
                echo "<td>" . $row["order_id"] . "</td>";
                echo "<td>" . $row["total_points"] . "</td>";
                echo "<td>" . $row["dt_stamp"] . "</td>";
                echo "</tr>";
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    foreach ($row as $key => $value) {
                        echo "<td>" . $value. "</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            }
            //empty case
            else{
                echo "<h2>No orders found</h2>";
            }
        } catch (mysqli_sql_exception $e) {
            $message = "Error: <br>". $e->getMessage();
        }
    }

    $conn->close();


?>