<!DOCTYPE html>
<html>
    <body>
        <h3>Add Order</h3>
        <?php

            //TODO: implement checks for sponsor
            
            ?>
            <br>
            <form action="" method="POST" target="">
                <label for="orderID">Order ID:</label>
                <input type="number" name="orderID" placeholder="25"><br>
                <label for="userID">userID:</label>
                <input type="number" name="userID" ><br>
                <label for="points">Points:</label>
                <input type="number" name="points" ><br>
                <br>
                <input type="submit" name="add_order_submit" class="button" value="Submit">
                <br>
            </form><?php
            
            if(isset($_POST["add_order_submit"])){
                echo "<br>";
                if ($conn->connect_error) {
                    $message = "Connection failed: " . $conn->connect_error;
                } 
                else {
                    //checking if all fields exists and are not empy
                    if (isset($_POST["orderID"]) && !empty($_POST["orderID"]) &&
                        isset($_POST["userID"]) && !empty($_POST["userID"]) &&
                        isset($_POST["points"]) && !empty($_POST["points"])) {
                    
                        $sql = "SELECT * from orders where order_id=" . $_POST['orderID'];
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            echo "OrderID already exists.";
                        }
                        else{
                            $sql = "INSERT INTO orders (order_id, user_id, total_points) VALUES (" . $_POST["orderID"] .",". $_POST["userID"] . "," . $_POST["points"] . ");";
                            $result = $conn->query($sql);
                            echo "Order Added!";
                        }
                    }
                    else{
                        echo "Missing fields";
                    }                   

                }
            }
        
        ?>
    </body>
</html>