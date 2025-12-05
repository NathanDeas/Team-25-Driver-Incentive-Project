<!-- Dummy home page for testing purposes -->
<!DOCTYPE html>
<html>
    <body>
        <?php
            //home page for admin
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check database connection
            if ($conn->connect_error) {
                $message = "Connection failed: " . $conn->connect_error;
            } else {

                $sql = "SELECT * from users WHERE username ='" . $_SESSION['User'] . "'";

                try {
                    $result = $conn->query($sql);
                    //prints if not empty
                    if($result->num_rows > 0){
                        
                        //order stuff for admin
                        if($row['user_type'] == 'admin'){
                            //Display table of all users in system
                
                            ?>
                            <form action="" method="POST" >
                                <h3>Edit Orders</h3>
                                <input name="add_order" type="submit" class="button" value="Add Order"> <br><br>
                            </form>
                            <?php
                        }
                        else if($row['user_type'] == 'sponsor'){
                            //Display table of all users in system
                
                            ?>
                            <form action="" method="POST" >
                                <h3>Edit Orders</h3>
                                <input name="add_order" type="submit" class="button" value="Add Order"> <br><br>
                            </form>
                            <?php
                        }


                    }
                    else{
                        echo "<h2>Username Not Found.</h2>";
                    }
                } catch (mysqli_sql_exception $e) {
                    $message = "Error: <br>". $e->getMessage();
                }

            }
            $conn->close();
        ?>
    </body>
</html>