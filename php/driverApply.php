<!DOCTYPE html>
<html>
    <div id="apply">
    <form method="POST">
        <h2 id="title">Select An Organization</h2>
        <br>
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
        }?>
        <input type="submit" name="org_submit" value="Apply" class="button">
    </form><?php
    if(isset($_POST['org_submit']))
    {
        $sql = "SELECT * FROM users WHERE username='" . $_SESSION['User'] . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $check = "SELECT * FROM applications WHERE driver_id='" . $row['userID'] . "' AND organization_id='" . $_POST['organizations'] . "'";
        $resultcheck = $conn->query($check);
        $rowcheck = $resultcheck->fetch_assoc();

        if($rowcheck['status'] == 'pending' || $rowcheck['status'] == 'accepted')
        {
            echo "<br><h2 id='message'>Application Already Submitted to this organization</h2>";
        }
        else
        {
            $sqlNewApp = "INSERT INTO applications (driver_id,organization_id,status) 
            VALUES ('" . $row['userID'] . "' , '" . $_POST['organizations'] . "', 'pending')";
            $result = $conn->query($sqlNewApp);
            if($result)
            {
                echo "<br><h2 id='message'>Application submitted to " . $_POST['organizations'] . "</h2>";
                //logs app is submitted
                //todo: specify which org
                $sqlLog = "INSERT INTO log (user_id, message, audit_type) VALUES (" . $_SESSION['user_id']  . ", " . "'submitted', " . 3 . ");";
                $conn->query($sqlLog);
            }
            else 
            {
                echo "Error: " . $conn->error;
            }
        }
    }
    ?>
    </div>
</html>