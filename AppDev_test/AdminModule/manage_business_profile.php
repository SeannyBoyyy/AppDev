<?php
// Connect to your database
include('../config/connectDb.php');

// Check if form is submitted
if (isset($_POST['owner_id'])) {
    // Process form data
    $owner_id = $_POST['owner_id'];

    // Add error handling
    if (!is_numeric($owner_id)) {
        echo "
        <script>
            Swal.fire({
                title: 'Error!',
                text: 'Invalid Owner ID!',
                icon: 'error'
            }).then(function() {
                window.location = 'index.php';
            });
        </script>";
        
        exit();
    }

    // Delete dependent records from posting_module table first
    $deletePostingModuleSql = "DELETE FROM posting_module WHERE posted_by = $owner_id";
    $deleteBusinessAdvertisementSql = "DELETE FROM business_advertisement WHERE posted_by = $owner_id"; 
    $deleteBusinessPhotos = "DELETE FROM business_photos WHERE posted_by = $owner_id"; 
    $deleteMessageModuleSql = "DELETE FROM message_module WHERE sent_to = $owner_id"; 

    if ($conn->query($deletePostingModuleSql) === TRUE && $conn->query($deleteBusinessAdvertisementSql) === TRUE 
        && $conn->query($deleteBusinessPhotos) === TRUE && $conn->query($deleteMessageModuleSql) === TRUE) {

        // Delete user from business_profile table
        $deleteUserSql = "DELETE FROM business_profile WHERE id = $owner_id";
        if ($conn->query($deleteUserSql) === TRUE) {
            echo "
            <script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Deleting Owner ID!',
                    icon: 'success'
                }).then(function() {
                    window.location = 'index.php';
                });
            </script>";
        
            exit();
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
        <h1>Manage Business Profiles</h1>
        <br>
        <h5>Delete Business Profile</h5>
        <form method="post" action="">
            <div class="input-group flex-nowrap mb-2" style="width:200px;">
                <span class="input-group-text" id="addon-wrapping">ID</span>
                <!-- Corrected name attribute -->
                <input type="text" class="form-control" name="owner_id" placeholder="Owner ID" aria-label="Username" aria-describedby="addon-wrapping">
            </div>
            <input class="btn btn-danger" type="submit" value="Delete">
        </form>
        <br>

        <!-- Display Table -->
        <table border="1" cellspacing="0" cellpadding="10" class="table table-striped">
            <tr class="text-center">
                <th>Owner ID</th>
                <th>Name</th>
                <th>Image</th>
                <th>Description</th>
            </tr>
            <?php
            $rows = mysqli_query($conn, "SELECT * FROM business_profile ORDER BY id DESC");
            foreach ($rows as $row) :
                ?>
                <tr class="text-center">
                    <td><?php echo $row["id"]; ?></td>
                    <td><?php echo $row["name"]; ?></td>
                    <td><img src="../ProfileModule/img/<?php echo $row['image']; ?>" width="200" title=""></td>
                    <td><?php echo $row["text"]; ?></td>
                    <!-- ... -->
                </tr>
            <?php endforeach; ?>
        </table>
        <br>
        <a class="btn btn-success" href="index.php" style="margin-bottom: 20px;">Go Back</a>
    </div>
</div>    

<?php
$conn->close();
?>