<!DOCTYPE html>
<html>
    <head>
        <h1>Your Cart</h1>
        <form method="post">
            <input type="submit" name="purchaseCart" value="Purchase" class="button">
        </form>
    <body>
        <?php
        if(isset($_POST['purchaseCart']))
        {
            $data = file_get_contents('catCart/cart.json');
            $songInfo = json_decode($data, true);
            foreach($songInfo as $info)
            {
            //Insert Catalog transaction into table
                $catPurchase = "VALUES ('" . $_SESSION['user_id'] . "', 'Withdrawal', '-" . $info[1] . " pts', 'Purchase: " . $info[0] . "');";
                $sqlInsertTrans = "INSERT INTO transactions (driver_id, action, amount, message)" . $catPurchase;
                $result = $conn->query($sqlInsertTrans);
                if (!$result) {
                    echo "Error: " . $conn->error;
                }
                if($result)
                {
                    echo "Purchase Successfull!";
                }
                else
                {
                    echo "Error! Please try again.";
                }
                $sql = "SELECT * FROM user_org WHERE userID='" . $_SESSION['user_id'] . "'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $points = $row['point_total'] - $info[1];

                $withdrawal = "UPDATE user_org SET point_total = " . $points . " WHERE userID='" . $_SESSION['user_id'] . "'";
                $withdrawalResult = $conn->query($withdrawal);
            }


            $data = file_get_contents('catCart/cart.json');
            $songInfo = json_decode($data, true);
            $songInfo = [];
            $new = json_encode($songInfo);
            file_put_contents('catCart/cart.json', $new);
        }
        ?>
        <?php
        if(file_exists('catCart/cart.json'))
        {
            $data = file_get_contents('catCart/cart.json');
            $songInfo = json_decode($data);
            ?>
            <table style="margin-left:auto;margin-right:auto;">
                <tr>
                    <th>Songname</th>
                    <th>Point Price</th>
                </tr>
            <?php
            foreach ($songInfo as $info)
            {
                    ?>
                    <tr>
                        <td> <?php echo $info[0]; ?> </td>
                        <td> <?php echo $info[1]; ?> </td>
                    </tr>
                    <?php
            }
        }?>
    </body>
</html>