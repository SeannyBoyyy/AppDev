<?php
// Connect to your database
include('../config/connectDb.php');

?>

    <div class="middle">
        <div class="container">
            <h1>Manage Advertisements</h1>
            <br>

            <!-- Display Table -->
            <table border="1" cellspacing="0" cellpadding="10" class="table table-striped">
            <tr class="text-center">
                <th>#</th>
                <th>Owner ID</th>
                <th>Name</th>
                <th>Image</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
                <?php
                $rows = mysqli_query($conn, "SELECT * FROM business_advertisement ORDER BY id DESC");
                foreach ($rows as $row) :
                ?>
                    <tr class="text-center">
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row["posted_by"]; ?></td>
                        <td><?php echo $row["name"]; ?></td>   
                        <td><img src="../ProfileModule/img/<?php echo $row['image']; ?>" width="200" title=""></td>
                        <td><?php echo $row["text"]; ?></td> 
                        <!-- ... -->
                        <td>
                            <!-- CRUD Operations Form -->
                            <form action="crud.php" method="post">
                                <input type="hidden" name="advertisement_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-success mb-2" name="advertisement_read">Read</button>
                                <button type="submit" class="btn btn-success mb-2" name="advertisement_edit">Edit</button> <!-- Updated from "Update" to "Edit" -->
                                <button class="btn btn-danger" type="submit" name="advertisement_delete">Delete</button>
                            </form>
                        </td>
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