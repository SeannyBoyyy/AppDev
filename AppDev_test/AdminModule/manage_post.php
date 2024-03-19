<?php 
include('../config/connectDb.php');
include('../navbars/profilepage-nav.php'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data</title>
</head>

<body>
    <div class="middle">
        <div class="container">
            <h2>Manage Post</h2>

            <!-- Display Table -->
            <table border="1" cellspacing="0" cellpadding="10">
                <tr>
                    <td>#</td>
                    <td>Name</td>
                    <td>Image</td>
                    <td>Information</td>
                    <td>Created At</td> <!-- Added Created At column -->
                    <td>Posted By</td> <!-- Added Posted By column -->
                    <td>Action</td>
                </tr>
                <?php
                $i = 1;
                $rows = mysqli_query($conn, "SELECT * FROM posting_module ORDER BY id DESC");
                foreach ($rows as $row) :
                ?>
                    <tr>
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
                                <button type="submit" name="read">Read</button>
                                <button type="submit" name="edit">Edit</button> <!-- Updated from "Update" to "Edit" -->
                                <button class="delete-btn" type="submit" name="delete">Delete</button>
                            </form>
                        </td>
                        <!-- ... -->

                    </tr>
                <?php endforeach; ?>
            </table>
            <br>
        </div>
    </div>
    <a href="index.php">Go Back</a>
</body>
</html>
