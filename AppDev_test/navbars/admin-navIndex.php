<?php
include('./config/connectDb.php');
session_start();

// Check if user is logged in
if(isset($_SESSION['admin_email'])){
  $admin_email = $_SESSION['admin_email']; 

  // Query to count messages
  $sql = "SELECT COUNT(*) AS message_count FROM admin_messages";

  // Execute query
  $result = $conn->query($sql);

  // Check if query executed successfully
  if ($result) {
      // Fetch the result as an associative array
      $row = $result->fetch_assoc();
      
      // Get the message count
      $msgCount = $row['message_count'];
  } else {
      // Handle query error
      echo "Error: " . $sql . "<br>" . $conn->error;
  }
} else {
  // If admin is not logged in, set message count to 0
  $msgCount = 0;
  header('Location: ./AccPages/login-page.php');
  exit();
  
}

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link rel="stylesheet" href="./CSS/farmer-navIndex.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
      .list-group-item {
        border: none !important;
        margin-top: -5px;
        margin-bottom: -5px;
    }
    .list-group-item a {
        display: block;
        font-size: 18px;
        font-weight: bold;
        color: #333;
        padding: 10px 15px;
        transition: color 0.3s ease;
        border: none !important;
    }
    .list-group-item a i {
        margin-right: 10px;
        margin-left: 10px;
    }

    .list-group-item a:hover {
        color: white;
        background-color: #007bff;
    }

    .list-group-item a:focus {
        outline: none !important;
        box-shadow: none !important;
    }
</style>
  </head>
  <body>
  <nav class=" sticky-top navbar navbar-expand-lg border-bottom p-3 w-100" style="background-color:white;">
    <div class="container d-flex">
      <div class="container col-6">
        <a class="navbar-brand fs-4" href="#">FarmDeals<img src="http://localhost/AppDev/AppDev_test/ProfileModule/img/logo.jpg" alt="FarmDeals Logo" width="60" height="60"></a>
      </div>
      <div class="container d-flex justify-content-end col-6">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
    </div>
    
    <div class="container d-flex justify-content-end">
      <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
        <ul class="navbar-nav justify-start">
              <li class="nav-item">
                <button class="btn" type="button" aria-current="page" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop" aria-controls="staticBackdrop">
                  <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#90EE90" class="bi bi-person-circle" viewBox="0 0 16 16">
                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                  </svg>
                  Admin Page
                </button>
              </li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="offcanvas offcanvas-start" data-bs-backdrop="static" tabindex="-1" id="staticBackdrop" aria-labelledby="staticBackdropLabel">
      <div class="offcanvas-header">
          <h5 class="offcanvas-title" id="staticBackdropLabel">Options</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <div class="text-center">
            <img style="width: 300px; height:300px;" class="img-fluid img-thumbnail rounded-circle object-fit-cover" src="ProfileModule/img/AdminIcon.png">
            <h3 class="mt-3" style="color:black; font-size:40px">Admin</h3>
              <div class="text-center">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item"><a style="color: black; text-decoration:none;"  href="./AdminModule/index.php"><i class="fas fa-users"></i>Manage Users</a></li>
                  <li class="list-group-item"><a style="color: black; text-decoration:none;"  href="./AdminModule/index.php"><i class="fas fa-user-circle"></i>Manage Profiles</a></li>
                  <li class="list-group-item"><a style="color: black; text-decoration:none;"  href="./AdminModule/index.php"><i class="fas fa-pen"></i>Manage Posts</a></li>
                  <li class="list-group-item"><a style="color: black; text-decoration:none;"  href="./AdminModule/index.php"><i class="fas fa-ad"></i>Manage Advertisement</a></li>
                  <li class="list-group-item">
                    <a style="color: black;" href="./AdminModule/index.php">
                    <i class="fas fa-envelope" style="margin-right: 8px;"></i>Messages
                      <span class="badge rounded-pill bg-primary"> <?php echo $msgCount ?></span>
                    </a>
                  </li>
                  <li class="list-group-item"><a style="color: black; text-decoration:none;"  href="./AccPages/logout.php"><i class="fas fa-sign-out-alt"></i>Log Out</a></li>
                </ul>
              </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>