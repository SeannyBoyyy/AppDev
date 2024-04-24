<?php 
include('../config/connectDb.php');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the action is for status change
    if (isset($_POST['action']) && ($_POST['action'] == 'SUSPEND' || $_POST['action'] == 'ACTIVE')) {
        $id = $_POST['id'];
        $status = $_POST['action']; // Status to be changed
        
        // Update the status in the database
        $query = "UPDATE user_subscriptions SET status = '$status' WHERE id = $id";
        mysqli_query($conn, $query);
    }
}

?>

<div class="middle">
    <div class="container">
        <h1>Manage User Subscriptions</h1>

        <!-- Display Table -->
        <table border="1" cellspacing="0" cellpadding="10" class="table table-striped">
            <tr class="text-center">
                <th>#</th>
                <th>User ID</th>
                <th>Plan ID</th>
                <th>Status</th>
                <th>Valid From</th>
                <th>Valid To</th>
                <th>Action</th>
            </tr>
            <?php
            $i = 1;
            $rows = mysqli_query($conn, "SELECT * FROM user_subscriptions ORDER BY id ASC");
            foreach ($rows as $row) :
            ?>
                <tr class="text-center">
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $row["user_id"]; ?></td>
                    <td><?php echo $row["plan_id"]; ?></td>
                    <td><?php echo $row["status"]; ?></td>
                    <td><?php echo $row["valid_from"]; ?></td>
                    <td><?php echo $row["valid_to"]; ?></td>
                    <td>
                        <!-- Form for status change -->
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="btn btn-primary" name="action" value="SUSPEND">Suspend</button>
                            <button type="submit" class="btn btn-success" name="action" value="ACTIVE">Active</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br>
        <a class="btn btn-success" href="index.php" style="margin-bottom: 20px;">Go Back</a>
    </div>
</div>

<?php
include('../navbars/footer.php')
?>
