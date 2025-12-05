<!DOCTYPE html>
<html>
    <body>
        <div id="applications">
        <h2 id='title'>Search All Pending Or Accepted Applications</h2>
        <h2>Search:</h2>
        <form method="POST">
            <input type="submit" value="Pending" name="pend_apps" class="button">
            <input type="submit" value="Accepted" name="acc_apps" class="button">
            <br><br>
        </form>
        <?php
        if(isset($_POST['pend_apps']))
        {
            ?>
            <h2>All Pending Applications</h2>
            <table style="margin-left:auto;margin-right:auto;">
                <tr>
                    <th>Driver ID</th>
                    <th>Organization</th>
                </tr>
            <?php
            $sql = "SELECT * FROM applications WHERE status='pending'";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc())
            {
                ?>
                <tr>
                    <td><?php echo $row['driver_id'] ?></td>
                    <td><?php echo $row['organization_id'] ?></td>
                </tr>
                <?php
            }
            ?></table><?php
        }
        if(isset($_POST['acc_apps']))
        {?>
            <h2>All Active Drivers</h2>
            <table style="margin-left:auto;margin-right:auto;">
                <tr>
                    <th>Driver ID</th>
                    <th>Organization</th>
                </tr>
            <?php
            $sql = "SELECT * FROM applications WHERE status='accepted'";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc())
            {
                ?>
                <tr>
                    <td><?php echo $row['driver_id'] ?></td>
                    <td><?php echo $row['organization_id'] ?></td>
                </tr>
                <?php
            }
            ?></table><?php
            
        }?>
        </div>
    </body>
</html>