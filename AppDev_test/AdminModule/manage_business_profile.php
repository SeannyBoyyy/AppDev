<?php
// Connect to your database
include('../config/connectDb.php');
include('../navbars/profilepage-nav.php'); 

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_profile'])) {
    // Process form data
    $profileId = $_POST['profile_id'];

    // Delete business profile from business_profile table
    $sql = "DELETE FROM business_profile WHERE id = $profileId";

    if ($conn->query($sql) === TRUE) {
        echo "Business profile deleted successfully";
    } else {
        echo "Error deleting business profile: " . $conn->error;
    }
}

// Fetch business profiles from the database
$sql = "SELECT id, owner, name, image, text FROM business_profile";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Business Profile</title>
</head>
<body>
    <div class="middle">
        <div class="container">
            <h2>Post Module</h2>

            <!-- Display Table -->
            <table border="1" cellspacing="0" cellpadding="10">
            <tr>
                <th>ID</th>
                <th>Owner</th>
                <th>Name</th>
                <th>Image</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
                <?php
                $i = 1;
                $rows = mysqli_query($conn, "SELECT * FROM business_profile ORDER BY id DESC");
                foreach ($rows as $row) :
                ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $row["owner"]; ?></td>
                        <td><?php echo $row["name"]; ?></td>   
                        <td><img src="../ProfileModule/img/<?php echo $row['image']; ?>" width="200" title=""></td>
                        <td><?php echo $row["text"]; ?></td> 
                        <!-- ... -->
                        <td>
                            <!-- CRUD Operations Form -->
                            <form action="crud.php" method="post">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="read">Read</button>
                                <button type="submit" name="edit">Edit</button> <!-- Updated from "Update" to "Edit" -->
                                <button class="delete-btn" type="submit" name="delete">Delete</button>
                            </form>
                        </td>
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

<?php
$conn->close();
?>