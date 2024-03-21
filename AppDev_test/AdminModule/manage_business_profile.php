<?php
// Connect to your database
include('../config/connectDb.php');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form data
    $userId = $_POST['user_id'];

    // Delete dependent records from posting_module table first
    $deletePostingModuleSql = "DELETE FROM posting_module WHERE posted_by = $userId";
    if ($conn->query($deletePostingModuleSql) === TRUE) {
        // Delete user from business_profile table
        $deleteUserSql = "DELETE FROM business_profile WHERE id = $userId";
        if ($conn->query($deleteUserSql) === TRUE) {
            echo "";
        } else {
            echo "Error deleting user: " . $conn->error;
        }
    } else {
        echo "Error deleting dependent records: " . $conn->error;
    }
}
?>

    <div class="middle">
        <div class="container">
            <h1>Manage Business Profile</h1>
            <br>
            <h5>Delete Business Profile</h5>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="input-group flex-nowrap mb-2" style="width:200px;">
                        <span class="input-group-text" id="addon-wrapping">ID</span>
                        <input type="text" class="form-control" name="user_id" placeholder="User ID" aria-label="Username" aria-describedby="addon-wrapping">
                    </div>
                    <input class="btn btn-danger" type="submit" value="Delete">
                </form>
            <br>

            <!-- Display Table -->
            <table border="1" cellspacing="0" cellpadding="10" class="table table-striped">
            <tr>
                <th>ID</th>
                <th>Owner</th>
                <th>Name</th>
                <th>Image</th>
                <th>Description</th>
            </tr>
                <?php
                $rows = mysqli_query($conn, "SELECT * FROM business_profile ORDER BY id DESC");
                foreach ($rows as $row) :
                ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row["owner"]; ?></td>
                        <td><?php echo $row["name"]; ?></td>   
                        <td><img src="../ProfileModule/img/<?php echo $row['image']; ?>" width="200" title=""></td>
                        <td><?php echo $row["text"]; ?></td> 
                        <!-- ... -->
                    </tr>
                <?php endforeach; ?>
            </table>
            <br>
            <a class="btn btn-success" href="index.php">Go Back</a>
        </div>
    </div>    

<?php
$conn->close();
?>