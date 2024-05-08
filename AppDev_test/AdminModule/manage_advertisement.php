<?php
// Connect to your database
include('../config/connectDb.php');
?>

<div class="middle">
    <div class="container p-3">
        <h1>Manage Advertisements</h1>
        <br>

        <!-- Display Table -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>Owner ID</th>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
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
                            <td>
                                <!-- CRUD Operations Form -->
                                <form action="crud.php" method="post">
                                    <input type="hidden" name="advertisement_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-success mb-2" name="advertisement_read">Read</button>
                                    <button type="submit" class="btn btn-success mb-2" name="advertisement_edit">Edit</button>
                                    <button class="btn btn-danger" type="submit" name="advertisement_delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <br>
    </div>
</div>

