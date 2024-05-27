<?php 
include('../config/connectDb.php');
?>

<div class="middle">
    <div class="container p-3">
        <h1>Manage Farm Photos</h1>
        <br>
        <!-- Display Table -->
        <div class="table-responsive">
            <table class="table table-hover">
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
                            <td><?php echo $row["id"]; ?></td>
                            <td><img src="../ProfileModule/img/<?php echo $row['image']; ?>" width="200" title=""></td>
                            <td><?php echo $row["created_at"]; ?></td>
                            <td><?php echo $row["posted_by"]; ?></td>
                            <td>
                                <!-- CRUD Operations Form -->
                                <form action="crud.php" method="post">
                                    <input type="hidden" name="photos_id" value="<?php echo $row['id']; ?>">
                                    <button class="btn btn-danger" type="submit" name="photos_delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                        </svg>
                                    </button>
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
