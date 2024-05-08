<?php
include('../config/connectDb.php');


// Check if form is submitted
if (isset($_POST['user_id'])) {
    // Process form data
    $userId = $_POST['user_id'];

    // Add error handling
    if (!is_numeric($userId)) {
        echo "
        <script>
            Swal.fire({
                title: 'Error!',
                text: 'Invalid User ID!',
                icon: 'error'
            }).then(function() {
                window.location = 'index.php?active=users';
            });
        </script>";
        
        exit();
    }

    // Delete dependent records from posting_module table first
    $deletePostingModuleSql = "DELETE FROM posting_module WHERE posted_by IN (SELECT id FROM business_profile WHERE owner = $userId)";
    $deleteBusinessAdvertisementSql = "DELETE FROM business_advertisement WHERE posted_by IN (SELECT id FROM business_profile WHERE owner = $userId)"; 
    $deleteBusinessPhotos = "DELETE FROM business_photos WHERE posted_by IN (SELECT id FROM business_profile WHERE owner = $userId)"; 
    $deleteMessageModuleSql = "DELETE FROM message_module WHERE sent_to IN (SELECT id FROM business_profile WHERE owner = $userId)"; 

    if ($conn->query($deleteBusinessAdvertisementSql) === TRUE) {
        // Delete records from other related tables
        $conn->query($deletePostingModuleSql);
        $conn->query($deleteBusinessPhotos);
        $conn->query($deleteMessageModuleSql);

        // Now delete user from business_profile table
        $deleteBusinessProfileSql = "DELETE FROM business_profile WHERE owner = $userId";
        if ($conn->query($deleteBusinessProfileSql) === TRUE) {
            // Now delete user from user_accounts table
            $deleteUserSql = "DELETE FROM user_accounts WHERE id = $userId";
            if ($conn->query($deleteUserSql) === TRUE) {
                echo "
                <script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'Deleting User ID!',
                        icon: 'success'
                    }).then(function() {
                        window.location = 'index.php?active=users';
                    });
                </script>";

                exit();
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
<div class="middle">
    <div class="container p-3">
        <h1>Manage Users</h1>
        <br>
        <h5>Delete User</h5>
        <form method="post" action="">
            <div class="input-group flex-nowrap mb-2" style="max-width: 200px;">
                <span class="input-group-text" id="addon-wrapping">ID</span>
                <input type="text" class="form-control" name="user_id" placeholder="User ID" aria-label="Username" aria-describedby="addon-wrapping">
            </div>
            <input type="submit" class="btn btn-danger" id="btn" data-bs-toggle="modal" data-bs-target="#exampleModal" value="Delete">
        </form>
        <br>

        <!-- Display Table -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr class="text-center">
                        <th>User ID</th>
                        <th>E-mail</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $rows = mysqli_query($conn, "SELECT * FROM user_accounts ORDER BY id DESC");
                    foreach ($rows as $row) :
                    ?>
                        <tr class="text-center">
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row["email"]; ?></td>
                            <td><?php echo $row["firstName"]; ?></td>
                            <td><?php echo $row["lastName"]; ?></td>   
                            <td><?php echo $row["created_at"]; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <br>
    </div>
</div>
