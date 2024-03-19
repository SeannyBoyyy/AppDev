<?php
include('../config/connectDb.php');
include('../navbars/profilepage-nav.php'); 

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form data
    $userId = $_POST['user_id'];

    // Delete user from user_accounts table
    $sql = "DELETE FROM user_accounts WHERE id = $userId";

    if ($conn->query($sql) === TRUE) {
        echo "User deleted successfully";
    } else {
        echo "Error deleting user: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
</head>
<body>
    <h2>Delete User</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        User ID: <input type="text" name="user_id"><br><br>
        <input type="submit" value="Delete">
    </form>
</body>
</html>
