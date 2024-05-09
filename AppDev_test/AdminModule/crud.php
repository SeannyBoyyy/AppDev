<?php
include('../config/connectDb.php');
include('../navbars/admin-navbar.php'); 

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
                      color: #black;
                      text-decoration: none;
                  }

                  a:hover {
                      text-decoration: underline;
                  }
              </style>
          </head>
          <body>
              <div class='container border-rounded'>
                  <h3>Read Record:</h3>
                  <p><strong>Name:</strong> {$record['name']}</p>
                  <p><strong>Text:</strong> {$record['text']}</p>
                  <img src='../ProfileModule/img/{$record['image']}' width='200' title=''>
                  <a clas='btn btn-success' href='index.php?active=posts'>Back to List</a>
              </div>
          </body>
          </html>";
}

if (isset($_POST['advertisement_read'])) {
    // Read operation
    $advertisement_id = $_POST['advertisement_id'];
    $result = mysqli_query($conn, "SELECT * FROM business_advertisement WHERE id = $advertisement_id");
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
                      color: #black;
                      text-decoration: none;
                  }

                  a:hover {
                      text-decoration: underline;
                  }
              </style>
          </head>
          <body>
              <div class='container border-rounded'>
                  <h3>Read Record:</h3>
                  <p><strong>Name:</strong> {$record['name']}</p>
                  <p><strong>Text:</strong> {$record['text']}</p>
                  <img src='../ProfileModule/img/{$record['image']}' width='200' title=''>
                  <a clas='btn btn-success' href='index.php?active=advertisement'>Back to List</a>
              </div>
          </body>
          </html>";
}

if (isset($_POST['rating_read'])) {
    // Read operation
    $review_id = $_POST['review_id'];
    $result = mysqli_query($conn, "SELECT * FROM review_table WHERE review_id = $review_id");
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
                      color: #black;
                      text-decoration: none;
                  }

                  a:hover {
                      text-decoration: underline;
                  }
              </style>
          </head>
          <body>
              <div class='container border-rounded'>
                  <h3>Read Record:</h3>
                  <p><strong>Name:</strong> {$record['user_name']}</p>
                  <p><strong>Review:</strong> {$record['user_review']}</p>
                  <p><strong>Star Rating:</strong> {$record['user_rating']}</p>
                  <a clas='btn btn-success' href='index.php?active=rating'>Back to List</a>
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

if (isset($_POST['advertisement_edit'])) {
    // Edit operation (similar to update)
    $advertisement_id = $_POST['advertisement_id'];
    // Redirect to edit page or implement edit logic here
    header("Location: edit.php?advertisement_id=$advertisement_id");
    exit();
}

if (isset($_POST['delete'])) {
    // Delete operation
    $id = $_POST['id'];
    mysqli_query($conn, "DELETE FROM posting_module WHERE id = $id");
    header("Location: index.php?active=posts");
    exit();
}

if (isset($_POST['advertisement_delete'])) {
    // Delete operation
    $advertisement_id = $_POST['advertisement_id'];
    mysqli_query($conn, "DELETE FROM business_advertisement  WHERE id = $advertisement_id");
    header("Location: index.php?active=advertisement");
    exit();
}

if (isset($_POST['rating_delete'])) {
    // Delete operation
    $review_id = $_POST['review_id'];
    mysqli_query($conn, "DELETE FROM review_table WHERE review_id = $review_id");
    header("Location: index.php?active=rating");
    exit();
}

if (isset($_POST['photos_delete'])) {
    // Delete operation
    $photos_id = $_POST['photos_id'];
    mysqli_query($conn, "DELETE FROM business_photos  WHERE id = $photos_id");
    header("Location: index.php?active=photos");
    exit();
}
?>