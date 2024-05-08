<?php
include('../config/connectDb.php');

session_start();
if(isset($_SESSION['ownerID'])){
    $business_owner = $_SESSION['ownerID']; 
}else{
    echo 'no owner ';
    header('Location: ../AccPages/login-page.php');
    exit();
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link rel="stylesheet" href="../CSS/farmer-van.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
  <nav class="navbar navbar-expand-lg sticky-top w-100 shadow box-area mb-5" style="background-color: whitesmoke;">
    <div class="container">
      <div class="col-sm-6 col-md-1 col-lg-12">
        <a class="navbar-brand fs-4 justify-content-center" href="../AccPages/login-page.php">Z-MarketHub<img src="http://localhost/AppDev/AppDev_test/ProfileModule/img/logo.png" alt="FarmDeals Logo" width="60" height="60"></a>
      </div>
      <div class="col-sm-6 col-md-1 col-lg-12">
        <ul class="navbar-nav">
            <li class="nav-item">
                <button class="btn" type="button" aria-current="page" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop" aria-controls="staticBackdrop">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#90EE90" class="bi bi-person-circle" viewBox="0 0 16 16">
                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                    </svg>
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
            <div>
                <?php
                  $res = mysqli_query($conn, "SELECT * FROM business_profile WHERE owner=$business_owner");

                  while($row = mysqli_fetch_assoc($res)){
                ?>
                <img style="width: 300px; height:300px;" class="img-fluid img-thumbnail rounded-circle object-fit-cover" src="img/<?php echo $row['image'] ?>">
                <h3 class="mt-3"><?php echo $row['name'] ?></h3>
                <?php } ?>
            </div>
            
              <div class="text-center">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item"><a href="../ProfileModule/profile-page.php">Profile</a></li>
                  <li class="list-group-item"><a href="../ProfileModule/profile-page.php">Upload a new post</a></li>
                  <li class="list-group-item"><a href="">Manage Posts</a></li>
                  <li class="list-group-item"><a href="../ProfileModule/profile-page.php">Update Profile</a></li>
                  <li class="list-group-item">
                    <a href="">Messages</a>
                    <span class="badge text-bg-primary">4</span>
                  </li>
                  <li class="list-group-item"><a href="../AccPages/logout.php">Log Out</a></li>
                </ul>
              </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>