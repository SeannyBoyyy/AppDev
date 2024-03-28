<?php 
include('../config/connectDb.php');

?>


    <div class="middle">
        <div class="container">
            <h1>Manage Posts</h1>

            <!-- Display Table -->
            <table border="1" cellspacing="0" cellpadding="10" class="table table-striped">
                <tr class="text-center">
                    <th>#</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Information</th>
                    <th>Created At</th> <!-- Added Created At column -->
                    <th>Posted By</th> <!-- Added Posted By column -->
                    <th>Action</th>
                </tr>
                <?php
                $i = 1;
                $rows = mysqli_query($conn, "SELECT * FROM posting_module ORDER BY id DESC");
                foreach ($rows as $row) :
                ?>
                    <tr class="text-center">
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $row["name"]; ?></td>
                        <td><img src="../ProfileModule/img/<?php echo $row['image']; ?>" width="200" title=""></td>
                        <td><?php echo $row["text"]; ?></td>   
                        <td><?php echo $row["created_at"]; ?></td> <!-- Display Created At --> 
                        <td><?php echo $row["posted_by"]; ?></td> <!-- Display Created At --> 
                        <!-- ... -->
                        <td>
                            <!-- CRUD Operations Form -->
                            <form action="crud.php" method="post">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit mb-2" class="btn btn-success" name="read">Read</button>
                                <button type="submit mb-2" class="btn btn-success" name="edit">Edit</button> <!-- Updated from "Update" to "Edit" -->
                                <button class="btn btn-danger" type="submit" name="delete">Delete</button>
                            </form>
                        </td>
                        <!-- ... -->

                    </tr>
                <?php endforeach; ?>
            </table>
            <br>
            <a class="btn btn-success" href="index.php">Go Back</a>
        </div>

    </div>
    
<?php
    include('../navbars/footer.php')
?>