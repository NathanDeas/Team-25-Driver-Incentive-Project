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
                //queries database

                $sql = "SELECT * from users WHERE username ='" . $_SESSION['User'] . "'";

                try {
                    $result = $conn->query($sql);
                    //prints if not empty
                    if($result->num_rows > 0){
                        
                        $row = $result->fetch_assoc();
                        echo "<div id='edit-profile'>";
                        echo "<br><h2 id='title'>" . "Edit Profile" ."</h2>"; 

                        
                        //buttons for changing username and password
                        //hidden input sends username 
                        if($row['user_type'] == 'admin'){
                            //Display table of all users in system
                            $sql = "SELECT users.userID as userID, users.username as Username, users.user_type as User_Type, users.fullname as Full_Name, user_org.orgID as orgID, user_org.point_total as Points
                            FROM users LEFT JOIN user_org ON (users.userID = user_org.userID)";
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();
                            $userID = $row["userID"];
                            $username = $row["Username"];
                            $userType = $row["User_Type"];
                            $fullName = $row["Full_Name"];
                            $orgID = $row["orgID"];
                            $points = $row["Points"];
                        
                            // Display results as a table
                            if ($result->num_rows > 0) {
                                echo "<h2>Admin Accounts Table</h2>";
                                echo "<table align='center'>";
                                echo "<tr>";
                                foreach ($row as $key => $value) {
                                    echo "<td id='t-header'><strong>" . $key . "</strong></td>";
                                }
                                echo "</tr>";
                                echo "<td>" . $userID . "</td>";
                                echo "<td>" . $username . "</td>";
                                echo "<td>" . $userType . "</td>";
                                echo "<td>" . $fullName . "</td>";
                                echo "<td>" . $orgID . "</td>";
                                echo "<td>" . $points . "</td>";
                                echo "</tr>";
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    foreach ($row as $key => $value) {
                                        echo "<td>" . $value. "</td>";
                                    }
                                    echo "</tr>";
                                }
                                echo "</table><br>";
                            } else {
                                echo "No Result Returned";
                            }

                            $sql = "SELECT orgID as orgID, org_name as Organization_Name from organizations";
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();
                            $orgID = $row["orgID"];
                            $orgName = $row["Organization_Name"];
                        
                            // Display results as a table
                            if ($result->num_rows > 0) {
                                echo "<h4>Organizations Table</h4>";
                                echo "<table align='center'>";
                                echo "<tr>";
                                foreach ($row as $key => $value) {
                                    echo "<td id='t-header'><strong>" . $key . "</strong></td>";
                                }
                                echo "</tr>";
                                echo "<td>" . $orgID . "</td>";
                                echo "<td>" . $orgName . "</td>";
                                echo "</tr>";
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    foreach ($row as $key => $value) {
                                        echo "<td>" . $value. "</td>";
                                    }
                                    echo "</tr>";
                                }
                                echo "</table><br>";
                            } else {
                                echo "No Result Returned";
                            }
                            ?>
                            <form action="" method="POST" >
                                <h3>Enter username:</h3>
                                <input type="hidden" name="userID" value="<?php echo $_SESSION['User'] ?>">
                                <input type="text" name="altUser" value="<?php echo $_SESSION['User'] ?>"><br><br>
                                <input name="change_username" type="submit" class="button" value="Change Username"> <br><br>
                                <input name="change_password" type="submit" class="button" value="Change Password"><br><br>
                                <input name="change_name" type="submit" class="button" value="Change Name"><br><br>
                                <input name="remove_driver" type="submit" class="button" value="Remove Driver from Organization"><br><br>
                                <input name="change_org" type="submit" class="button" value="Change Sponsor's Organization"><br><br>
                                <input name="delete_user" type="submit" class="button" value="Delete User"><br><br>
                            </form>
                            <?php
                        }
                        else if($row['user_type'] == 'sponsor'){
                            //Find userID of current sponsor
                            $sql = "SELECT * from users WHERE username ='" . $_SESSION['User'] . "'";
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();
                            $userID = $row["userID"];
                            //Use userID to find which orgID
                            $sql = "SELECT * FROM user_org WHERE userID='" . $userID . "'";
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();
                            $orgID = $row["orgID"];

                            $sql = "SELECT users.userID as userID, users.user_type as userType, users.fullname as fullName, users.username as Username, user_org.orgID as orgID, 
                                    user_org.point_total as points FROM users INNER JOIN user_org ON (users.userID = user_org.userID) WHERE user_org.orgID = " . $orgID . "";
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();
                            $userID = $row["userID"];
                            $username = $row["Username"];
                            $userType = $row["userType"];
                            $fullName = $row["fullName"];
                            $orgID = $row["orgID"];
                            $pointTotal = $row["points"];
                        
                            // Display results as a table
                            if ($result->num_rows > 0) {
                                echo "<h2>Sponsor Accounts Table</h2>";
                                echo "<table align='center'>";
                                echo "<tr>";
                                foreach ($row as $key => $value) {
                                    echo "<td><strong>" . $key . "</strong></td>";
                                }
                                echo "</tr>";
                                echo "<td>" . $userID . "</td>";
                                echo "<td>" . $userType . "</td>";
                                echo "<td>" . $fullName . "</td>";
                                echo "<td>" . $username . "</td>";
                                echo "<td>" . $orgID . "</td>";
                                echo "<td>" . $pointTotal . "</td>";
                                echo "</tr>";
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    foreach ($row as $key => $value) {
                                        echo "<td>" . $value. "</td>";
                                    }
                                    echo "</tr>";
                                }
                                echo "</table><br>";
                            } else {
                                echo "No Result Returned";
                            }
                            ?>
                            <form action="" method="POST" >
                                <input type="hidden" name="userID" value="<?php echo $_SESSION['User'] ?>">
                                <h3>Enter username:</h3>
                                <input type="text" name="altUser" value="<?php echo $_SESSION['User'] ?>"><br><br>
                                <input name="change_username" type="submit" class="button" value="Change Username"> <br><br>
                                <input name="change_password" type="submit" class="button" value="Change Password"><br><br>
                                <input name="change_name" type="submit" class="button" value="Change Name"><br><br>
                                <input name="remove_driver" type="submit" class="button" value="Remove Driver from Organization">
                                <input name="user_type" type="hidden" class="button" value="<?php echo "sponsor" ?>">
                                <input name="user_org" type="hidden" class="button" value="<?php echo $row['organization_id'] ?>">
                                
                            </form>
                            <?php
                        }
                        else{
                            //Display table of current driver user information
                            $sql = "SELECT * FROM users WHERE username='" . $_SESSION['User'] . "'";;
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();

                            $userType = $row["user_type"];
                            $userType[0] = strtoupper($userType[0]);

                            echo "<p><strong>Full Name:</strong> ". $row["fullname"] ."</p>";
                            echo "<p><strong>Username: </strong>". $_SESSION['User'] ."</p>";
                            echo "<p><strong>User Type: </strong>". $userType ."</p>";

                            echo "<p><strong>Organizations:</strong></p>";
                            $userID = $row["userID"];
                            $sql = "SELECT * FROM user_org WHERE userID='" . $userID . "'";;
                            $result = $conn->query($sql);
                            if( $result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    $sql = "SELECT * FROM organizations WHERE orgID='" . $row["orgID"] . "'";;
                                    $currentResult = $conn->query($sql);
                                    $currentRow = $currentResult->fetch_assoc();
                                    echo "<p>Organization ID: " . $currentRow["orgID"] ." - ". $currentRow["org_name"] ."</p>";
                                }
                            }

                            echo "<p><strong>Point Totals:</strong></p>";
                            $sql = "SELECT * FROM user_org WHERE userID='" . $userID . "'";;
                            $result = $conn->query($sql);
                            if( $result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<p>Organization ID: " . $row["orgID"] ." - ". $row["point_total"] ." points</p>";
                                }
                            }
                            ?>
                            <form action="" method="POST" >
                                <input type="hidden" name="userID" value="<?php echo $_SESSION['User'] ?>">
                                <input type="hidden" name="altUser" value="<?php echo $_SESSION['User'] ?>"><br>
                                <input name="change_username" type="submit" class="button" value="Change Username"> <br><br>
                                <input name="change_password" type="submit" class="button" value="Change Password"><br><br>
                                <input name="change_name" type="submit" class="button" value="Change Name">
                            </form>
                            <?php
                        }
                        echo "</div>";


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