<?php 
include('../config/connectDb.php');

?>


    <div class="middle">
        <div class="container">
            <h1>Manage Farm Photos</h1>

            <!-- Display Table -->
            <table border="1" cellspacing="0" cellpadding="10" class="table table-striped">
                <tr class="text-center">
                    <th>#</th>
                    <th>Image</th>
                    <th>Created At</th> <!-- Added Created At column -->
                    <th>Posted By</th> <!-- Added Posted By column -->
                    <th>Action</th>
                </tr>
                <?php
                $i = 1;
                $rows = mysqli_query($conn, "SELECT * FROM business_photos ORDER BY id DESC");
                foreach ($rows as $row) :
                ?>
                    <tr class="text-center">
                        <td><?php echo $i++; ?></td>
                        <td><img src="../ProfileModule/img/<?php echo $row['image']; ?>" width="200" title=""></td>
                        <td><?php echo $row["created_at"]; ?></td> <!-- Display Created At --> 
                        <td><?php echo $row["posted_by"]; ?></td> <!-- Display Created At --> 
                        <!-- ... -->
                        <td>
                            <!-- CRUD Operations Form -->
                            <form action="crud.php" method="post">
                                <input type="hidden" name="photos_id" value="<?php echo $row['id']; ?>">
                                <button class="btn btn-danger" type="submit" name="photos_delete">Delete</button>
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
    include('../navbars/footer.php')
?>