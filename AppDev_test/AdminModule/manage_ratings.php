<?php 
include('../config/connectDb.php');

?>


<div class="middle">
    <div class="container p-3">
        <h1>Manage Ratings</h1>
        <br>
        <!-- Display Table -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>Business ID</th>
                        <th>User Name</th>
                        <th>Star Ratings</th>
                        <th>Reviews</th>
                        <th>Date time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $rows = mysqli_query($conn, "SELECT * FROM review_table ORDER BY review_id DESC");
                    foreach ($rows as $row) :
                    ?>
                        <tr class="text-center">
                            <td><?php echo $row["review_id"]; ?></td>
                            <td><?php echo $row["business_id"]; ?></td>
                            <td><?php echo $row['user_name']; ?></td>
                            <td><?php echo $row["user_rating"]; ?></td>   
                            <td><?php echo $row["user_review"]; ?></td> 
                            <td><?php echo date('l jS, F Y h:i:s A', ($row["datetime"])); ?></td>
                            <td>
                                <!-- CRUD Operations Form -->
                                <form action="crud.php" method="post">
                                    <input type="hidden" name="review_id" value="<?php echo $row['review_id']; ?>">
                                    <button type="submit" class="btn btn-success mb-2" name="rating_read">Read</button>
                                    <button class="btn btn-danger" type="submit" name="rating_delete">Delete</button>
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
