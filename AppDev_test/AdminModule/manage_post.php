<?php 
include('../config/connectDb.php');

?>


<div class="middle">
    <div class="container p-3">
        <h1>Manage Posts</h1>
        <br>
        <!-- Display Table -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Information</th>
                        <th>Category</th>
                        <th>Price</th> <!-- Added Price Range column -->
                        <th>Created At</th>
                        <th>Posted By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $rows = mysqli_query($conn, "SELECT * FROM posting_module ORDER BY id DESC");
                    foreach ($rows as $row) :
                    ?>
                        <tr class="text-center">
                            <td><?php echo $row["id"]; ?></td>
                            <td><?php echo $row["name"]; ?></td>
                            <td><img src="../ProfileModule/img/<?php echo $row['image']; ?>" width="200" title=""></td>
                            <td><?php echo $row["text"]; ?></td>   
                            <td><?php echo $row["category"]; ?></td> 
                            <td><?php echo $row["price_range"]; ?></td> <!-- Display Price Range -->
                            <td><?php echo $row["created_at"]; ?></td> 
                            <td><?php echo $row["posted_by"]; ?></td> 
                            <td>
                                <!-- CRUD Operations Form -->
                                <form action="crud.php" method="post">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-success mb-2" name="read">Read</button>
                                    <button type="submit" class="btn btn-success mb-2" name="edit">Edit</button>
                                    <button class="btn btn-danger" type="submit" name="delete">Delete</button>
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
