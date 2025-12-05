<!DOCTYPE html>
<html>
    <body>
        <div id='transactions'>
        <?php
            $sql = "SELECT * FROM users WHERE username='" . $_SESSION['User'] . "'";
            $result = $conn->query($sql);
            $userType = $result->fetch_assoc();
            //Driver
            //******************* PRINT ORGANIZATION THE TRANSACTION WAS FROM ***************************************
            if($userType['user_type'] == 'driver' || $_SESSION['driver_view'] == 1)
            {
                $listTransactions = "SELECT * FROM transactions WHERE driver_id='" . $userType['userID'] . "' ORDER BY transaction_id DESC";
                $result = $conn->query($listTransactions);
                if($result->num_rows == 0)
                {
                    echo '<h2 id="message">No Transaction History</h2>';
                }
                else
                {
                    //Some way to not have to do this twice?
                    ?>
                    <br>
                    <h2>All Transactions</h2>
                    <br>

                    <table>
                        <tr>

                            <th>Transaction ID</th>
                            <th>Date/Time</th>
                            <th>Action</th>
                            <th>Amount</th>
                            <th>Message</th>
                            <th>Organization</th>
                        </tr>
                    <?php
                    while($row2 = $result->fetch_assoc())
                    {
                        ?>
                        <tr>
                            <td><?php echo $row2['transaction_id'] ?></td>
                            <td><?php echo $row2['trans_date'] ?></td>
                            <td><?php echo $row2['action'] ?></td>
                            <td><?php echo $row2['amount'] ?></td>
                            <td><?php echo $row2['message'] ?></td>
                            <?php
                            if($row2['orgID'] == -1)
                            {
                                ?><td style="color: red;">Admin Transactransaction</td><?php
                            }
                            else
                            {
                                ?><td><?php echo $row2['orgID'] ?></td><?php
                            }?>
                        </tr>
                        <?php
                    }
                }
            }

            //Sponsor/Admin
            else
            {
                ?>
                <form method = 'POST'>
                    <br>
                    <h2 id="title">Driver's Transactions</h2><br>
                    <input type="submit" name="view_all" value="View All" class="button">
                    <input type="submit" name="add_trans" value="Change Points" class="button">
                    <br><br>
                </form>
                <?php
                //print list of transactions for specific driver
                if(isset($_POST['view_all']) || isset($_POST['search']))
                {
                    ?>
                    <form method = 'POST'>
                        <br>
                        <h2>Search for driver</h2>
                        <input type="number" name="userID" placeholder="0" min="0">
                        <input type="submit" name="search" value="Search" class="button">
                        <br><br>
                    </form>
                    <?php
                    if(isset($_POST['search']))
                    {
                        if(!empty($_POST['userID']))
                        {
                            $driver_transactions = "SELECT * FROM transactions WHERE driver_id='" . $_POST['userID'] . "' ORDER BY transaction_id DESC";
                            $result = $conn->query($driver_transactions);
                            if($result->num_rows == 0)
                            {
                                echo '<div id="message">Driver ID has no Transactions</div>';
                            }
                            else
                            {
                                ?>
                                <table>
                                    <tr>
                                    <th>Driver ID</th>
                                        <th>Transaction ID</th>
                                        <th>Date/Time</th>
                                        <th>Action</th>
                                        <th>Amount</th>
                                        <th>Message</th>
                                    </tr>
                                <?php
                                while($row2 = $result->fetch_assoc())
                                {
                                    ?>
                                    <tr>
                                        <td><?php echo $row2['driver_id'] ?></td>
                                        <td><?php echo $row2['transaction_id'] ?></td>
                                        <td><?php echo $row2['trans_date'] ?></td>
                                        <td><?php echo $row2['action'] ?></td>
                                        <td><?php echo $row2['amount'] ?></td>
                                        <td><?php echo $row2['message'] ?></td>
                                    <?php
                                }
                            }
                        }
                        else
                        {
                            echo "Please supply a Driver ID";
                        }
                    }
                    
                }

                //Deposit or Withdrawal points 
                if(isset($_POST['add_trans']) || isset($_POST['add_deposit']) || isset($_POST['add_Withdrawal']))
                {
                    ?>
                    <form method = 'POST'>
                        <h2>Deposit/Withdrawal from account</h2>
                        Driver ID: <input type="number" name="user_ID" placeholder="0" min="0">
                        <?php if ($userType['user_type'] == 'admin')
                        {
                            $findOrgs = "SELECT * FROM organizations;";
                            $allOrgs = $conn->query($findOrgs);
                        ?>
                        <?php
                        }?>
                        Point Amount: <input type="number" name="amount" value="Transaction Amount" placeholder="0" min ="0"><br>
                        Message for transaction: <br><input style="height:20px;width:400px;font-size:14pt;resize:none;" type="text" name="message"><br><br>
                        <input type="submit" name="add_deposit" value="Deposit" class="button">
                        <input type="submit" name="add_Withdrawal" value="Withdrawal" class="button">
                        <br><br>
                    </form>
                    <?php
                    $sql = "SELECT * FROM user_org WHERE userID='" . $_POST['user_ID'] . "'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();

                    $req = "SELECT * FROM user_org WHERE userID='" . $_SESSION['user_id'] . "'";
                    $organization = $conn->query($req);
                    $info = $organization->fetch_assoc();
                    //deposit points
                    //********************* CHECK USER TYPE AND IF THE DRIVER IS IN THEIR ORGANIZATION *********************
                    if(isset($_POST['add_deposit']))
                    {
                        //check if id is valid
                        $exists = "SELECT * FROM users WHERE userID='" . $_POST['user_ID'] . "'";
                        $result = $conn->query($exists);
                        if($result->num_rows == 0)
                        {
                            echo 'Driver does not exist!';
                        }
                        //insert transaction and update points
                        else
                        {
                            if ($_SESSION['user_type'] == 'admin')
                            {
                                $transValues = " VALUES('". $_POST['user_ID']. "', 'Deposit', '+" . $_POST['amount'] . " pts', '" . $_POST['message'] . "', -1);";
                            }
                            else
                            {
                                $transValues = " VALUES('". $_POST['user_ID']. "', 'Deposit', '+" . $_POST['amount'] . " pts', '" . $_POST['message'] . "', '" . $info['orgID'] . "');";
                            }
                            $sqlInsertTrans = "INSERT INTO transactions (driver_id, action, amount, message, orgID)" . $transValues;
                            $result = $conn->query($sqlInsertTrans);

                            $points = $row['point_total'];
                            $points = $points + $_POST['amount'];
                            //updating table
                            $deposit = "UPDATE user_org SET point_total = " . $points . " WHERE userID='" . $_POST['user_ID'] . "' AND orgID='" . $info['orgID'] . "';";
                            $depositResult = $conn->query($deposit);
                            echo "Points Deposited";
                            //for logging
                            $sqlLog = "INSERT INTO log (user_id, message, audit_type, superior_id) VALUES (" . $_POST['user_ID']  . ", " . "'points_added', " . 2 . "," . $_SESSION['user_id'] . ");";
                            $conn->query($sqlLog);
                        }
                    }

                    //Withdrawal points
                    //********************* CHECK USER TYPE AND IF THE DRIVER IS IN THEIR ORGANIZATION *********************
                    //NEGATIVE POINTS?
                    if(isset($_POST['add_Withdrawal']))
                    {
                        //check if id is valid
                        $exists = "SELECT * FROM users WHERE userID='" . $_POST['user_ID'] . "'";
                        $result = $conn->query($exists);
                        if($result->num_rows == 0)
                        {
                            echo 'Driver does not exist!';
                        }
                        //insert transaction and update points
                        else
                        {
                            if ($_SESSION['user_type'] == 'admin')
                            {
                                $transValues = "VALUES ('" . $_POST['user_ID'] . "', 'Withdrawal', '-" . $_POST['amount'] . " pts', '" . $_POST['message'] . "', -1);";
                            }
                            else
                            {
                                $transValues = "VALUES ('" . $_POST['user_ID'] . "', 'Withdrawal', '-" . $_POST['amount'] . " pts', '" . $_POST['message'] . "', '" . $info['orgID'] . "');";
                            }
                            $sqlInsertTrans = "INSERT INTO transactions (driver_id, action, amount, message, orgID)" . $transValues;
                            $result = $conn->query($sqlInsertTrans);
                            if (!$result) {
                                echo "Error: " . $conn->error;
                            }
                            $points = $row['point_total'];
                            $points = $points - $_POST['amount'];
                            //updating table
                            $withdrawal = "UPDATE user_org SET point_total = " . $points . " WHERE userID='" . $_POST['user_ID'] . "' AND orgID='" . $info['orgID'] . "';";
                            $withdrawalResult = $conn->query($withdrawal);
                            echo "Points Withdrawn";
                            $sqlLog = "INSERT INTO log (user_id, message, audit_type, superior_id) VALUES (" . $_POST['user_ID']  . ", " . "'points_withdraw', " . 2 . "," . $_SESSION['user_id'] . ");";
                            $conn->query($sqlLog);
                        }
                    }
                }
            }

            
        ?>
        </div>
    </body>
</html>