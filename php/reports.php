<!DOCTYPE html>
<html>
    <body>
    <div id="reports">
    <h3>Generate Reports</h3>

        <?php

            if(isset($_POST['reports'])){
                ?>
                <form method="post">
                    <input type="submit" value="Driver Points Report" name="driver_points_report" class="button"><br><br>
                    <input type="submit" value="Sales Reports" name="sales_report" class="button">
                    <br>
                    <br>
                </form>
                <?php
            }


        ?>
    </div>
    </body>
</html>