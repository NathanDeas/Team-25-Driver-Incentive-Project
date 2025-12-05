<!-- Page for creating accounts -->
<!DOCTYPE html>
<html>
    <body>
        <?php
            ?>
            <br>
            <div id='create-account'>
            <h2 id='title'> Create New Account </h2>
            <br>
            <form action="" method="POST" target="">
                <!-- Form for creating new user -->
                <!-- If value in _POST autofill it into form -->
                <label for="username">Username:</label>
                <input type="text" name="username" placeholder="driver_username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>"><br>
                <label for="password">Password:</label>
                <input type="text" name="password" placeholder="pass" value="<?php echo isset($_POST['password']) ? $_POST['password'] : ''; ?>"><br>
                <label for="fullName">Full Name:</label>
                <input type="text" name="fullName" placeholder="Full Name" value="<?php echo isset($_POST['fullName']) ? $_POST['fullName'] : ''; ?>"><br>
                <label for="organizationID">Organization Name:</label>
                <input type="text" name="organizationID" placeholder="org_1" value="<?php echo isset($_POST['organizationID']) ? $_POST['organizationID'] : 'NULL'; ?>"><br>

                
                <br>
                <input type="submit" value="Create Admin Account" name="create_admin" class="button">
                <input type="submit" value="Create Sponsor Account" name="create_sponsor" class="button">
                <input type="submit" value="Create Driver Account" name="create_driver" class="button">
                <br><br>
            </form><?php
            //will trigger after create_{type} button has been pressed 
            if (isset($_POST['create_admin']) || isset($_POST['create_sponsor']) || isset($_POST['create_driver'])) {
                if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['fullName']) || empty($_POST['organizationID'])) {
                    echo "All fields are required.<br>";
                } else {
                    $conn = new mysqli($servername, $username, $password, $dbname);
                    if ($conn->connect_error) {
                        echo "Connection failed:";
                    } else {
                        $sql = "SELECT * FROM users WHERE username='" . $_POST['username'] . "';";
                        //checks if username already exists
                        try {                            
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                echo "Username already exists. Choose another one.";
                            }
                            //creates sql query based on type of user being created
                            else {
                                $userValues;
                                $orgValues;
                                //TODO: create password verification here, if passes go into account type nest
                                //Check if password is at least 4 characters
                                if (strlen($_POST['password']) >= 4) {
                                    //Check that username and password are not the same
                                    if (strcmp($username, $password) != 0) {
                                        //Create hash for table
                                        $hashedPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
                                        if (isset($_POST['create_admin'])) {
                                            $userValues = "VALUES ('admin', '". $_POST['username']. "', '". $hashedPassword. "', '". $_POST['fullName']. "');";
                                            $sqlInsertUser = "INSERT INTO users (user_type,username,hashed_password,fullname) " . $userValues;
                                            $result = $conn->query($sqlInsertUser);
                                        }
                                        else if (isset($_POST['create_sponsor'])) {
                                            //Check if organization exists
                                            $sql = "SELECT * FROM organizations WHERE org_name='" . $_POST['organizationID'] . "';";
                                            $result = $conn->query($sql);
                                            if ($result->num_rows == 0) {
                                                $sql = "INSERT INTO organizations (org_name) VALUES ('" . $_POST['organizationID'] . "');";
                                                $result = $conn->query($sql);
                                            }
                                            $userValues = "VALUES ('sponsor', '". $_POST['username']. "', '". $hashedPassword. "', '". $_POST['fullName']. "');";
                                            $sqlInsertUser = "INSERT INTO users (user_type,username,hashed_password,fullname) " . $userValues;
                                            $result = $conn->query($sqlInsertUser);
                                            //Need to get the user ID for user_org table
                                            $sql = "SELECT * FROM users WHERE username='" . $_POST['username'] . "';";
                                            $result = $conn->query($sql);
                                            $row = $result->fetch_assoc();
                                            $userID = $row["userID"];
                                            //Need to get the org ID for user_org table
                                            $sql = "SELECT * FROM organizations WHERE org_name='" . $_POST['organizationID'] . "';";
                                            $result = $conn->query($sql);
                                            $row = $result->fetch_assoc();
                                            $orgID = $row["orgID"];
                                            //Use user ID and org ID to add to user_org table
                                            $orgValues = "VALUES ('". $userID . "', '". $orgID . "', 0);";
                                            $sqlInsertUser = "INSERT INTO user_org (userID, orgID, point_total) " . $orgValues;
                                        }
                                        else if (isset($_POST['create_driver'])) {
                                            //Check if organization exists
                                            $sql = "SELECT * FROM organizations WHERE org_name='" . $_POST['organizationID'] . "';";
                                            $result = $conn->query($sql);
                                            if ($result->num_rows > 0) {
                                                //if it does, store entered values for user table entry and add to users
                                                $userValues = "VALUES ('driver', '". $_POST['username']. "', '". $hashedPassword. "', '". $_POST['fullName']. "');";
                                                $sqlInsertUser = "INSERT INTO users (user_type,username,hashed_password,fullname) " . $userValues;
                                                $result = $conn->query($sqlInsertUser);
                                                //Need to get the user ID for user_org table
                                                $sql = "SELECT * FROM users WHERE username='" . $_POST['username'] . "';";
                                                $result = $conn->query($sql);
                                                $row = $result->fetch_assoc();
                                                $userID = $row["userID"];
                                                //Need to get the org ID for user_org table
                                                $sql = "SELECT * FROM organizations WHERE org_name='" . $_POST['organizationID'] . "';";
                                                $result = $conn->query($sql);
                                                $row = $result->fetch_assoc();
                                                $orgID = $row["orgID"];
                                                //Use user ID and org ID to add to user_org table
                                                $orgValues = "VALUES ('". $userID . "', '". $orgID . "', 0);";
                                                $sqlInsertUser = "INSERT INTO user_org (userID, orgID, point_total) " . $orgValues;
                                                $result = $conn->query($sqlInsertUser);
                                            } else {
                                                echo "Error: Organization does not exist.";
                                            }
                                        }
                                        if ($result) {
                                            echo "Account Creation Successful.";
                                            //retrieves info of new user and logs their account creation
                                            $sql = "SELECT * FROM users WHERE username='" . $_POST['username'] . "';";
                                            $result = $conn->query($sql);
                                            $row = $result->fetch_assoc();
                                            $userID = $row["userID"];
                                            $sqlLog = "INSERT INTO log (user_id, message, audit_type, superior_id) VALUES (" . $userID . ", " . "'account_created', " . 0 . "," . $_SESSION['user_id'] .");";
                                            $conn->query($sqlLog);
                                        } else {
                                            echo "Error: " . $conn->error;
                                        }          
                                    } else {
                                        echo "<h2>Error: Username and Password cannot be the same</h2>";
                                    }
                                } else {
                                    echo "<h2>Error: Password must be longer than 3 characters</h2>";
                                }                             
                            }     
                        } catch (mysqli_sql_exception $e) {
                            $message = "Error: <br>". $e->getMessage();
                            echo "". $message ."";
                        }
                    }
                }
            }
        ?>
        </div>
    </body>
</html>