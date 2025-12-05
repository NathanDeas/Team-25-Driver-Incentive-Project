<!DOCTYPE html>
<html>
    <body>
        <div id="sponsor-apps">
        <?php 
            session_start();
            // Get sponsor user ID
            $sql = "SELECT * FROM users WHERE username='" . $_SESSION['User'] . "'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $userIDs = $row["userID"];
            //Get orgID of current userID 
            $sql = "SELECT * FROM user_org WHERE userID='" . $userIDs . "'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $orgID = $row["orgID"];
            //Get org name of current orgID
            $sql = "SELECT * FROM organizations WHERE orgID='" . $orgID . "'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $orgName = $row["org_name"];
            //Select applications for org name
            $sql = "SELECT * FROM applications WHERE organization_id='" . $orgName . "'";
            $ID_Array = array();
            if(isset($_POST['pending']) || isset($_POST['submit']))
            {
                ?>
                <form target="" method="post" action="">
                    <h2>Add a Driver(id):</h2>
                    <input type="number" name="userID" placeholder="Driver ID" min="0"><br>
                    <input type="radio" id="accept" name="sponsorChoice" value="Accept">
                    <label for="accept">Accept</label><br>
                    <input type="radio" id="deny" name="sponsorChoice" value="Deny">
                    <label for="deny">Deny</label><br>
                    <input type="submit" name="submit" value="Submit" class="button">
                    <!-- <input type="submit" id="deny" name="submit" value="Deny" class="button"> -->
                    <br>
                    <br>
                </form><?php
                function test_input($data) {
                    // Strip HTML Tags
                    $data = strip_tags($data);
                    // Clean up things like &amp;
                    $data = html_entity_decode($data);
                    // Strip out any url-encoded stuff
                    $data = urldecode($data);
                    // Replace non-AlNum characters with space
                    $data = preg_replace('/[^A-Za-z0-9]/', ' ', $data);
                    // Replace Multiple spaces with single space
                    $data = preg_replace('/ +/', ' ', $data);
                    // Trim the string of leading/trailing space
                    $data = trim($data);
            
                    return $data;
                }
                if(isset($_POST['submit'])) 
                {
                    if(!empty($_POST['userID']))
                    {
                        if($_POST['sponsorChoice'] == "Accept")
                        {

                            $userID = test_input($_POST['userID']);

                            $sql = "SELECT * FROM applications WHERE driver_id = '" . $userID. "'";
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();
                            if($row != NULL) {
                                if($row['status'] == 'pending') {
                                    $sqlUpdateStatus = "UPDATE applications SET status='accepted' WHERE driver_id='" . $userID. "'";
                                    $result = $conn->query($sqlUpdateStatus);

                                    echo "Driver Accepted!";
                                    //logs driver accepted
                                    $sqlLog = "INSERT INTO log (user_id, message, audit_type, superior_id) VALUES (" . $userID  . ", " . "'accepted', " . 3 . "," . $_SESSION['user_id'] .");";
                                    $conn->query($sqlLog);
                                    $userOrgUpdate = "INSERT INTO user_org (userID, orgID, point_total) VALUES (" . $userID  . ", " . $orgID  . ", 0);";
                                    $result = $conn->query($userOrgUpdate);
                                } else {
                                    echo "Error: Status is not pending!";
                                }
                            } else {
                                echo "Error: Driver ID not found!";
                            }
                        }
                        else{
                            $userID = test_input($_POST['userID']);
                            //Enter code to remove application
                            $updateStatus = "DELETE FROM applications WHERE driver_id='" . $userID. "' AND status='pending'";
                            $result = $conn->query($updateStatus);
                            if($result)
                            {
                                echo "Application Denied";
                            }
                            $sqlLog = "INSERT INTO log (user_id, message, audit_type, superior_id) VALUES (" . $userID  . ", " . "'denied', " . 3 . "," . $_SESSION['user_id'] .");";
                            $conn->query($sqlLog);
                        }
                    }
                    else
                    {
                        echo "Please select a Driver ID";
                    }
                }
                

                //printing table(pending driver info)
                $result = $conn->query($sql);?>
                <table style="margin-left:auto;margin-right:auto;">
                    <tr>
                        <th>Driver ID</th>
                        <th>Username</th>
                        <th>Status</th>
                    </tr><?php
                while($row = $result->fetch_assoc())
                {
                    if($row['status'] == 'pending')
                    {
                        array_push($ID_Array, $row['driver_id']);
                    }
                }
                foreach ($ID_Array as &$id)
                {
                    $sql = "SELECT * FROM users WHERE userID='" . $id . "'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    ?>
                    <tr>
                        <td><?php echo $row['userID'] ?> </td>
                        <td><?php echo $row['username'] ?> </td>
                        <td> Pending </td>
                    </tr>
                    <?php
                }?>
                </table><?php
            }

            if(isset($_POST['accepted']))
            {
                $result = $conn->query($sql);?>
                <h2>Drivers In your Organization</h2>
                <table style="margin-left:auto;margin-right:auto;">
                    <tr>
                        <th>Driver ID</th>
                        <th>Username</th>
                        <th>Status</th>
                    </tr><?php
                while($row = $result->fetch_assoc())
                {
                    if($row['status'] == 'accepted')
                    {
                        array_push($ID_Array, $row['driver_id']);
                    }
                }
                foreach ($ID_Array as &$id)
                {
                    $sql = "SELECT * FROM users WHERE userID='" . $id . "'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    ?>
                    <tr>
                        <td><?php echo $row['userID'] ?> </td>
                        <td><?php echo $row['username'] ?> </td>
                        <td> Accepted </td>
                    </tr>
                    <?php
                }
                
                ?>
                </table><?php
            }
        ?>
        </div>
    </body>
</html>
