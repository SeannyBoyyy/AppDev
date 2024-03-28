<?php
include('../config/connectDb.php');


// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form data
    $userId = $_POST['user_id'];

    // Delete dependent records from posting_module table first
    $deletePostingModuleSql = "DELETE FROM posting_module WHERE posted_by IN (SELECT id FROM business_profile WHERE owner = $userId)";
    if ($conn->query($deletePostingModuleSql) === TRUE) {
        // Delete user from business_profile table
        $deleteBusinessProfileSql = "DELETE FROM business_profile WHERE owner = $userId";
        if ($conn->query($deleteBusinessProfileSql) === TRUE) {
            // Now delete user from user_accounts table
            $deleteUserSql = "DELETE FROM user_accounts WHERE id = $userId";
            if ($conn->query($deleteUserSql) === TRUE) {
                echo "";
            } else {
                echo "Error deleting user: " . $conn->error;
            }
        } else {
            echo "Error deleting business profile: " . $conn->error;
        }
    } else {
        echo "Error deleting dependent records: " . $conn->error;
    }
}


?>
    
        <div class="container">
            <h1>Manage Users</h1>
            <br>
            <h5>Delete User</h5>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <div class="input-group flex-nowrap mb-2" style="width:200px;">
                    <span class="input-group-text" id="addon-wrapping">ID</span>
                    <input type="text" class="form-control" name="user_id" placeholder="User ID" aria-label="Username" aria-describedby="addon-wrapping">
                </div>
                <input type="submit" class="btn btn-danger" id="btn" data-bs-toggle="modal" data-bs-target="#exampleModal" value="Delete">
            </form>
            <br>

            <!-- Display Table -->
            <table border="1" cellspacing="0" cellpadding="10" class="table table-striped">
                <tr class="text-center">
                    <td>User ID</td>
                    <td>E-mail</td>
                    <td>First Name</td>
                    <td>Last Name</td>
                    <td>Created At</td> <!-- Added Created At column -->
                </tr>
                <?php
                $i = 1;
                $rows = mysqli_query($conn, "SELECT * FROM user_accounts ORDER BY id DESC");
                foreach ($rows as $row) :
                ?>
                    <tr class="text-center">
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row["email"]; ?></td>
                        <td><?php echo $row["firstName"]; ?></td>
                        <td><?php echo $row["lastName"]; ?></td>   
                        <td><?php echo $row["created_at"]; ?></td> <!-- Display Created At --> 
                        <!-- ... -->
                    </tr>
                <?php endforeach; ?>
            </table>
            <br>
            <a href="index.php" class="btn btn-success">Go Back</a>
        </div>
    </div>

<?php
    include('../navbars/footer.php')
?>