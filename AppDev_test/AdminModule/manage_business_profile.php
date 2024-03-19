
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Business Profile</title>
</head>
<body>
    <div class="middle">
        <div class="container">
            <h1>Manage Business Profile</h1>
            <br>
            <h5>Delete Business Profile</h5>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    User ID: <input class="form-control w-50" type="text" name="user_id"><br><br>
                    <input type="submit" value="Delete">
                </form>
            <br>


            <!-- Display Table -->
            <table border="1" cellspacing="0" cellpadding="10" class="table table-striped">
            <tr>
                <th>ID</th>
                <th>Owner</th>
                <th>Name</th>
                <th>Image</th>
                <th>Description</th>
            </tr>
                <?php
                $rows = mysqli_query($conn, "SELECT * FROM business_profile ORDER BY id DESC");
                foreach ($rows as $row) :
                ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row["owner"]; ?></td>
                        <td><?php echo $row["name"]; ?></td>   
                        <td><img src="../ProfileModule/img/<?php echo $row['image']; ?>" width="200" title=""></td>
                        <td><?php echo $row["text"]; ?></td> 
                        <!-- ... -->
                    </tr>
                <?php endforeach; ?>
            </table>
            <br>
            <a class="btn btn-warning" href="index.php">Go Back</a>
        </div>
        
    </div>
    

</body>
</html>

<?php
?>