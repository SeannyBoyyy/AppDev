<?php 
include('../config/connectDb.php');
?>

<div class="middle">
    <div class="container p-3">
        <h1>Manage Farm Photos</h1>
        <br>
        <!-- Display Table -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>Image</th>
                        <th>Created At</th>
                        <th>Posted By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $rows = mysqli_query($conn, "SELECT * FROM business_photos ORDER BY id DESC");
                    foreach ($rows as $row) :
                    ?>
                        <tr class="text-center">
                            <td><?php echo $i++; ?></td>
                            <td><img src="../ProfileModule/img/<?php echo $row['image']; ?>" width="200" title=""></td>
                            <td><?php echo $row["created_at"]; ?></td>
                            <td><?php echo $row["posted_by"]; ?></td>
                            <td>
                                <!-- CRUD Operations Form -->
                                <form action="crud.php" method="post">
                                    <input type="hidden" name="photos_id" value="<?php echo $row['id']; ?>">
                                    <button class="btn btn-danger" type="submit" name="photos_delete">Delete</button>
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
