<?php
include('../config/connectDb.php');
include('../navbars/profilepage-nav.php'); 

if (isset($_POST['read'])) {
    // Read operation
    $id = $_POST['id'];
    $result = mysqli_query($conn, "SELECT * FROM posting_module WHERE id = $id");
    $record = mysqli_fetch_assoc($result);

    // Display read data with design
    echo "<!DOCTYPE html>
          <html lang='en'>
          <head>
              <meta charset='UTF-8'>
              <meta name='viewport' content='width=device-width, initial-scale=1.0'>
              <title>Read Record</title>
              <style>
                  body {
                      font-family: Arial, sans-serif;
                      background-color: #f4f4f4;
                      margin: 0;
                      padding: 0;
                  }

                  .container {
                      max-width: 600px;
                      margin: 20px auto;
                      padding: 20px;
                      background-color: #fff;
                      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                  }

                  h3 {
                      color: #333;
                  }

                  img {
                      max-width: 100%;
                      height: auto;
                      margin-top: 10px;
                  }

                  a {
                      display: block;
                      margin-top: 10px;
                      color: #007bff;
                      text-decoration: none;
                  }

                  a:hover {
                      text-decoration: underline;
                  }
              </style>
          </head>
          <body>
              <div class='container'>
                  <h3>Read Record:</h3>
                  <p><strong>Name:</strong> {$record['name']}</p>
                  <p><strong>Text:</strong> {$record['text']}</p>
                  <img src='img/{$record['image']}' width='200' title=''>
                  <a href='profile-page.php?active=managePosts'>Back to List</a>
              </div>
          </body>
          </html>";
}
if (isset($_POST['edit'])) {
    // Edit operation (similar to update)
    $id = $_POST['id'];
    // Redirect to edit page or implement edit logic here
    header("Location: edit.php?id=$id");
    exit();
}

if (isset($_POST['delete'])) {
    // Delete operation
    $id = $_POST['id'];
    mysqli_query($conn, "DELETE FROM posting_module WHERE id = $id");
    echo "
            <script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Post Deleted!',
                    icon: 'success'
                }).then(function() {
                    window.location = 'profile-page.php?active=managePosts';
                });
            </script>";
        
            exit();
}
?>
