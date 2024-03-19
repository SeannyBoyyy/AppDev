
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
            <h1>Manage User</h1>
            <br>
            <h5>Delete User</h5>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    User ID: <input class="form-control w-50" type="text" name="user_id"><br><br>
                    <input type="submit" value="Delete" class="btn btn-danger">
                </form>
            <br>

            <!-- Display Table -->
            <table border="1" cellspacing="0" cellpadding="10" class="table table-striped">
                <tr>
                    <td>#</td>
                    <td>email</td>
                    <td>firstName</td>
                    <td>lastName</td>
                    <td>Created At</td> <!-- Added Created At column -->
                </tr>
                <?php
                $i = 1;
                $rows = mysqli_query($conn, "SELECT * FROM user_accounts ORDER BY id DESC");
                foreach ($rows as $row) :
                ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row["email"]; ?></td>
                        <td><?php echo $row["firstName"]; ?></td>
                        <td><?php echo $row["lastName"]; ?></td>   
                        <td><?php echo $row["created_at"]; ?></td> <!-- Display Created At --> 
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
