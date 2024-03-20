<?php
include('../config/connectDb.php');
include('../navbars/profilepage-nav.php'); 

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
                echo "User deleted successfully";
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
</head>
<body>
    <div class="middle">
        <div class="container">
            <h1>Manage User</h1>
            <br>
            <h5>Delete User</h5>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                User ID: <input type="text" name="user_id"><br><br>
                <input type="submit" value="Delete">
            </form>
            <br>

            <!-- Display Table -->
            <table border="1" cellspacing="0" cellpadding="10">
                <tr>
                    <td>#</td>
                    <td>email</td>
                    <td>firstName</td>
                    <td>lastName</td>
                    <td>Created At</td> <!-- Added Created At column -->
                </tr>
                <?php
                $i = 1;
                $rows = mysqli_query($conn, "SELECT * FROM user_accounts ORDER BY id DESC");
                foreach ($rows as $row) :
                ?>
                    <tr>
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
            </div>
        </div>
    <a href="index.php">Go Back</a>
</body>
</html>