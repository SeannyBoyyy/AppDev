<?php   
include('../config/connectDb.php');
include('../navbars/profilepage-nav.php'); 

session_start();

// Check if ownerID is set in session
if(isset($_SESSION['ownerID'])){
    $business_owner = $_SESSION['ownerID']; 
} else {
    // Redirect or handle the case when ownerID is not set
    header("Location: ../login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data</title>
</head>

<body>
    <div class="middle">
        <div class="container">
            <h2>Post Module</h2>

            <!-- Display Table -->
            <table border="1" cellspacing="0" cellpadding="10">
                <tr>
                    <td>#</td>
                    <td>Name</td>
                    <td>Image</td>
                    <td>Information</td>
                    <td>Created At</td> <!-- Added Created At column -->
                    <td>Action</td>
                </tr>
                <?php
                $i = 1;
                // Modify the SQL query to select products associated with the current user's business profile
                $query = "SELECT * FROM posting_module WHERE posted_by IN (SELECT id FROM business_profile WHERE owner = ?) ORDER BY id DESC";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "i", $business_owner);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                foreach ($result as $row) :
                ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $row["name"]; ?></td>
                        <td><img src="img/<?php echo $row['image']; ?>" width="200" title=""></td>
                        <td><?php echo $row["text"]; ?></td>    
                        <td><?php echo $row["created_at"]; ?></td> <!-- Display Created At -->
                        <!-- ... -->
                        <td>
                            <!-- CRUD Operations Form -->
                            <form action="profile-page.php" method="post">
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
    <a href="profile-page.php">Go Back</a>
</body>
</html>
