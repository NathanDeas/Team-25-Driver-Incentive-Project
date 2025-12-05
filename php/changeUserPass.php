<!-- This file is used to change username and password for users! -->
<!DOCTYPE html>
<html>
    <body>
        <?php
        //will alter different user if userID is custom. Used for admin and sponsors
        $alterUser = isset($_POST['altUser']) ? $_POST['altUser'] : $_POST['userID'];
        $access = True; 

        //Search for username in database and see if exists
        $sql = "SELECT * from users where username='" . $alterUser . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        if ($result->num_rows == 0) {
            echo $alterUser;
            echo "<br><br>This user doesn't exist";
            $access = False;
            ?>
            <br><br>
                <form action="" method="POST" target="">
                    <input type="submit" name="button3" class="button" value="Ok">
                </form>
            <?php
        }
        $sponsorOrg = "";
        //sponsors have restrictions so the check is placed here
        if (isset($_POST['user_type']) && $_POST['user_type'] == 'sponsor') {
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                $message = "Connection failed: " . $conn->connect_error;
            } else {
                //Get orgID of current sponsor user
                $sql = "SELECT users.userID as userID, users.user_type as userType, users.username as username, user_org.orgID as orgID 
                        FROM users INNER JOIN user_org ON (users.userID = user_org.userID) WHERE users.username = '" . $_SESSION['User'] . "'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $sponsorOrgID = $row['orgID'];

                //Check if alter user is in same orgID
                $sql = "SELECT users.userID as userID, users.user_type as userType, users.username as username, user_org.orgID as orgID 
                        FROM users INNER JOIN user_org ON (users.userID = user_org.userID) WHERE users.username = '" . $alterUser . "'";
                $result = $conn->query($sql);
                $isSponsor = False;
                while ($row = $result->fetch_assoc()) {
                    if ($row["orgID"] == $sponsorOrgID) {
                        $isSponsor = True;
                    }
                }

                //If alter user is admin or current user is not alter user sponsor, no access
                if ($row['userType'] == 'admin' || $isSponsor == False) {
                    //if try illegal access change access variable to false
                    echo "<br><br>You Don't Access Over This User";
                    $access = False;
                    ?>
                    <br><br>
                        <form action="" method="POST" target="">
                            <input type="submit" name="button3" class="button" value="Sorry">
                        </form>
                    <?php
                }
            }
        }

        //for changing username
        if ((isset($_POST['change_username']) || isset($_POST['username_change_submit'])) && $access == True) {
            //check if sponsor or other driver
            //will take user input for username change
            if (isset($_POST['change_username'])) {
                ?>
                <br>
                <h2>Update Username</h2>
                <h3><?php echo $alterUser ?></h3>
                <form action="" method="POST" target="">
                    <label for="newUsr">New Username:</label>
                    <input type="text" name="newUsr" placeholder="test_driver_new"><br>
                    <label for="pass">Your Password:</label>
                    <input type="text" name="pass" ><br>
                    <label for="passConfirm">Confirm Password:</label>
                    <input type="text" name="passConfirm" ><br>
                    <input type="hidden" name="userID" value="<?php echo $alterUser ?>">
                    <br>
                    <input type="submit" name="username_change_submit" class="button" value="Submit">
                    <br>
                </form><?php
            }

            //Page to show result of username change attempt. And will give option to change it again. 
            if (isset($_POST['username_change_submit'])) {
                //variables needed, newUsr, currentUsr, pass, passConfirm
                $conn = new mysqli($servername, $username, $password, $dbname);
                // Check database connection
                if ($conn->connect_error) {
                    $message = "Connection failed: " . $conn->connect_error;
                } else {
                    //Checks if username exists in table
                    $sql = "SELECT * from users where username='" . $_POST['newUsr'] . "'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    if ($result->num_rows > 0) {
                        echo "<br>Error: Username already exists<br>";
                    } else {
                        echo "<br>Success: Username is available<br>";
                        //Check passwords are the same for password check
                        if ($_POST['pass'] == $_POST['passConfirm']) {
                            echo "<br>Success: Passwords match<br>";
                            //Check that given password and table hash matach
                            $sql = "SELECT * from users where username='" . $_SESSION['User'] . "'";
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();
                            if (password_verify($_POST['pass'], $row["hashed_password"])) {
                                //SQL command to change username 
                                $hashedPassword = password_hash($_POST['newPass'], PASSWORD_BCRYPT);
                                $sql = "UPDATE users SET username = '" . $_POST['newUsr'] . "' WHERE username='" . $alterUser . "'";
                                $result = $conn->query($sql);
                                echo "<br>Success: Username changed from " . $alterUser . " to ". $_POST['newUsr']. "<br>";
                                $_SESSION['User'] = $_POST['newUsr'];
                            } else {
                                echo "<br>Error: Incorrect password, Username not changed! <br>";
                            }
                        } else {
                            echo "<br>Error: Passwords don't match, Username not changed!<br>";
                        }
                    }
                }
                //if pressed takes it back to update username page
                ?>
                <br><br>
                <form action="" method="POST" >
                    <input type="hidden" name="userID" value="<?php echo $alterUser ?>">
                    <input name="change_username" type="submit" class="button" value="Change Username">
                </form>
                <?php
            }
        } 
        //for password changing
        else if ((isset($_POST['change_password']) || isset($_POST['password_change_submit'])) && $access == True) {
            //input for changing password
            if (isset($_POST['change_password'])) {
                ?>
                <br>
                <h2> Update Password </h2>
                <h3><?php echo $alterUser ?></h3>
                <form action="" method="POST" target="">
                    <label for="currentPass">Your Current Password:</label>
                    <input type="text" name="currentPass" placeholder="hash"><br>

                    <label for="newPass">New Password:</label>
                    <input type="text" name="newPass" placeholder="hash_new"><br>

                    <label for="newPassConfirm">Confirm New Password:</label>
                    <input type="text" name="newPassConfirm" placeholder="hash_new"><br>

                    <input type="hidden" name="userID" value="<?php echo $alterUser ?>">
                    <br>

                    <input type="submit" name="password_change_submit" class="button" value="Submit">
                    <br>
                </form><?php
            } else if (isset($_POST['password_change_submit'])) {
                $conn = new mysqli($servername, $username, $password, $dbname);
                // Check database connection
                if ($conn->connect_error) {
                    $message = "Connection failed: " . $conn->connect_error;
                } else {
                    $sql = "SELECT * from users where username = '" . $_SESSION['User'] . "'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    //Check that given password is correct
                    if (password_verify($_POST['currentPass'], $row["hashed_password"])) {
                        //Check that new password is not same as current password
                        if (!password_verify($_POST['newPass'], $row["hashed_password"])) { 
                            //Check that both new passwords match
                            if ($_POST['newPass'] ==  $_POST['newPassConfirm']) {
                                //Check if new password is longer than 4 characters
                                if (strlen($_POST['newPass']) >= 4) {
                                    //Check that new password is not same as username
                                    if (strcmp($alterUser, $_POST['newPass']) != 0) {
                                        //SQL command to update database with new password
                                        $hashedPassword = password_hash($_POST['newPass'], PASSWORD_BCRYPT);
                                        $sql = "UPDATE users SET hashed_password = '" . $hashedPassword . "' WHERE username = '" . $alterUser . "'";
                                        $result = $conn->query($sql);
                                        echo "<br>Success: Password changed for " . $alterUser . ".<br>";
                                        //logs password change
                                        $sql = "SELECT * from users where username = '" . $alterUser . "'";
                                        $result = $conn->query($sql);
                                        $row = $result->fetch_assoc();
                                        $sqlLog = "INSERT INTO log (user_id, message, audit_type, superior_id) VALUES (" . $row['userID']  . ", " . "'password_change', " . 0 . "," . $_SESSION['user_id'] . ");";
                                        $conn->query($sqlLog);
                                    } else {
                                        echo "<h2>Error: Username and new password cannot be the same</h2>";
                                    }
                                } else {
                                    echo "<h2>Error: New password must be longer than 3 characters</h2>";
                                }
                            } else {
                                echo "<br>Error: Passwords do not match<br>";
                            }
                        } else {
                            echo "<br>Error: New password is same as current password<br>";
                        }
                    } else {
                        echo "<br>Error: Incorrect password<br>";
                    }
                }
                //if pressed takes it back to update password page
                ?>
                <br><br>
                <form action="" method="POST" >
                    <input type="hidden" name="userID" value="<?php echo $alterUser ?>">
                    <input name="change_password" type="submit" class="button" value="Change Password">
                </form>
                <?php
            }

        }
        //form for name change
        else if ((isset($_POST['change_name']) || (isset($_POST['change_name_submit']))) && $access == True) {
            //triggers when form is submitted
            if (isset($_POST['change_name_submit'])) {
                $conn = new mysqli($servername, $username, $password, $dbname);
                // Check database connection
                if ($conn->connect_error) {
                    $message = "Connection failed: " . $conn->connect_error;
                } else {
                    $sql = "SELECT * from users where username = '" . $alterUser . "'";
                    $result = $conn->query($sql);
                    //SQL COMMAND TO CHANGE NAME GOES HERE
                    $sql = "UPDATE users SET fullname='" . $_POST['new_name'] . "' WHERE username = '" . $alterUser . "'";
                    $result = $conn->query($sql);
                    echo "<h3>Success: " . $alterUser . " name changed to " . $_POST['new_name'] . "</h3>";
                }
            } else {
                ?>
                <br><br>
                <h2>Update Name</h2>
                <h3><?php echo $alterUser ?></h3>
                <form action="" method="POST" >
                    <input type="hidden" name="userID" value="<?php echo $alterUser ?>">
                    <input type="text" name="new_name">
                    <input name="change_name_submit" type="submit" class="button" value="Change Name">
                </form>
                <?php
            }
        } else if ((isset($_POST['remove_driver']) || (isset($_POST['remove_driver_submit']))) && $access == True){
            if (isset($_POST['remove_driver'])) {
                ?>
                <br>
                <h2>Remove Driver</h2>
                <h3><?php echo $alterUser ?></h3>
                <form action="" method="POST" target="">
                    <label for="pass">Your Password:</label>
                    <input type="text" name="pass" ><br>
                    <label for="passConfirm">Confirm Password:</label>
                    <input type="text" name="passConfirm" ><br>
                    <input type="hidden" name="userID" value="<?php echo $alterUser ?>">
                    <br>
                    <input type="submit" name="remove_driver_submit" class="button" value="Submit">
                    <br>
                </form><?php
            }

            if (isset($_POST['remove_driver_submit'])) {
                $conn = new mysqli($servername, $username, $password, $dbname);
                // Check database connection
                if ($conn->connect_error) {
                    $message = "Connection failed: " . $conn->connect_error;
                } else {
                    //Check passwords are the same for password check
                    if ($_POST['pass'] == $_POST['passConfirm']) {
                        $sql = "SELECT users.userID as userID, users.user_type as userType, users.username as username, user_org.orgID as orgID 
                        FROM users INNER JOIN user_org ON (users.userID = user_org.userID) WHERE users.username = '" . $alterUser . "'";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        $sponsorOrgID = $row['orgID'];
                        $sql = "SELECT * from users where username = '" . $alterUser . "'";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        $userID = $row['userID'];
                        //Check that given password and table hash matach
                        $sql = "SELECT * from users where username = '" . $_SESSION['User'] . "'";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        if (password_verify($_POST['pass'], $row["hashed_password"])) {
                            //SQL command to remove driver
                            $sql = "DELETE FROM user_org WHERE userID = '" . $userID . "' AND orgID = '" . $sponsorOrgID . "'";
                            $result = $conn->query($sql);
                            echo "<br>Success: " . $alterUser . " has been removed from organization<br>";
                        } else {
                            echo "<br>Error: Incorrect password, user not removed! <br>";
                        }
                    } else {
                        echo "<br>Error: Passwords don't match, user not removed!<br>";
                    }
                }
            }
        //Delete Driver
        } else if ((isset($_POST['delete_user']) || (isset($_POST['delete_user_submit']))) && $access == True){
            if (isset($_POST['delete_user'])) {
                ?>
                <br>
                <h2>Delete User</h2>
                <h3><?php echo $alterUser ?></h3>
                <form action="" method="POST" target="">
                    <label for="pass">Your Password:</label>
                    <input type="text" name="pass" ><br>
                    <label for="passConfirm">Confirm Password:</label>
                    <input type="text" name="passConfirm" ><br>
                    <input type="hidden" name="userID" value="<?php echo $alterUser ?>">
                    <br>
                    <input type="submit" name="delete_user_submit" class="button" value="Submit">
                    <br>
                </form><?php
            }

            if (isset($_POST['delete_user_submit'])) {
                $conn = new mysqli($servername, $username, $password, $dbname);
                // Check database connection
                if ($conn->connect_error) {
                    $message = "Connection failed: " . $conn->connect_error;
                } else {
                    //Check passwords are the same for password check
                    if ($_POST['pass'] == $_POST['passConfirm']) {
                        $sql = "SELECT * from users where username = '" . $alterUser . "'";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        $userID = $row['userID'];
                        $userType = $row['user_type'];
                        $fullName = $row['fullname'];
                        //Check that given password and table hash match
                        $sql = "SELECT * from users where username = '" . $_SESSION['User'] . "'";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        if (password_verify($_POST['pass'], $row["hashed_password"])) {
                            //SQL command to delete user and move to delete table
                            $sql = "DELETE FROM user_org WHERE userID = '" . $userID . "'";
                            $result = $conn->query($sql);

                            $sql = "DELETE FROM users WHERE userID = '" . $userID . "'";
                            $result = $conn->query($sql);

                            $userValues = "VALUES ('" . $userID . "', '" . $userType . "', '". $alterUser . "', '". $fullName . "');";
                            $sqlInsertUser = "INSERT INTO delete_users (userID, user_type, username, fullname) " . $userValues;
                            $result = $conn->query($sqlInsertUser);

                            echo "<br>Success: " . $alterUser . " has been deleted<br>";
                        } else {
                            echo "<br>Error: Incorrect password, user not deleted! <br>";
                        }
                    } else {
                        echo "<br>Error: Passwords don't match, user not deleted!<br>";
                    }
                }
            }
        //change sponsor org
        } else if ((isset($_POST['change_org']) || (isset($_POST['change_org_submit']))) && $access == True){
            if (isset($_POST['change_org'])) {
                $conn = new mysqli($servername, $username, $password, $dbname);
                if ($conn->connect_error) {
                    $message = "Connection failed: " . $conn->connect_error;
                } else {
                    $sql = "SELECT * from users where username = '" . $alterUser . "'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    $userType = $row['user_type'];
                    if($userType == 'sponsor') {
                        ?>
                        <form method="POST">
                        <h2>Select An Organization</h2>
                        <select name="organizations">
                        <?php
                        $orgArray = array();
                        $sql = "SELECT DISTINCT org_name FROM organizations ORDER BY orgID";
                        $result = $conn->query($sql);
                        while($row = $result->fetch_assoc())
                        {
                            if($row['org_name'] != 'NULL')
                            {
                                array_push($orgArray, $row['org_name']);
                            }
                        }
                        foreach($orgArray as &$org)
                        {
                            echo "<option value='$org'>$org</option>";
                        }
                        ?>
                        </select>
                        <br>
                        <label for="pass">Your Password:</label>
                        <input type="text" name="pass" ><br>
                        <label for="passConfirm">Confirm Password:</label>
                        <input type="text" name="passConfirm" ><br>
                        <input type="hidden" name="userID" value="<?php echo $alterUser ?>">
                        <input type="submit" name="change_org_submit" value="Submit" class="button">
                        </form><?php
                    } else {
                        echo "<br>Error: User is not a sponsor<br>";
                    }
                }
            }

            if (isset($_POST['change_org_submit'])) {
                $conn = new mysqli($servername, $username, $password, $dbname);
                // Check database connection
                if ($conn->connect_error) {
                    $message = "Connection failed: " . $conn->connect_error;
                } else {
                    //Check passwords are the same for password check
                    if ($_POST['pass'] == $_POST['passConfirm']) {
                        //Check that given password and table hash match
                        $sql = "SELECT * from users where username = '" . $_SESSION['User'] . "'";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        if (password_verify($_POST['pass'], $row["hashed_password"])) {
                            //Get userID of sponsor
                            $sql = "SELECT * from users where username = '" . $alterUser . "'";
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();
                            $userID = $row['userID'];
                            //Get orgID of wanted org
                            $sql = "SELECT * from organizations where org_name = '" . $_POST['organizations'] . "'";
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();
                            $orgID = $row['orgID'];
                            //SQL command to delete user and move to delete table
                            $sql = "UPDATE user_org SET orgID = '" . $orgID . "'WHERE userID = '" . $userID . "'";
                            $result = $conn->query($sql);

                            echo "<br>Success: " . $alterUser . "'s organization has been updated!<br>";
                        } else {
                            echo "<br>Error: Incorrect password, user not updated! <br>";
                        }
                    } else {
                        echo "<br>Error: Passwords don't match, user not updated!<br>";
                    }
                }
            }
        }
        ?>
    </body>
</html>