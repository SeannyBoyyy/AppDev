<?php
include('./config/connectDb.php');
session_start();
if(isset($_SESSION['ownerID'])){
  $business_owner = $_SESSION['ownerID']; 
}else{
  echo 'no owner ';
}

  $getSql = "SELECT name, text, image FROM business_profile WHERE owner = ?";
  $stmt = mysqli_prepare($conn, $getSql);
  mysqli_stmt_bind_param($stmt, "i",$business_owner);
  mysqli_stmt_execute($stmt);

  $result = mysqli_stmt_get_result($stmt);

  if ($fromBusinessProfile = mysqli_fetch_assoc($result)) {
      $business_name = $fromBusinessProfile['name'];
      $business_bio = $fromBusinessProfile['text'];
      $business_pfp = $fromBusinessProfile['image'];
  } else {
      echo 'Failed to retrieve updated information or no data found';
      
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
  </head>
  <body>
  <nav class="navbar navbar-expand-lg shadow box-area p-3 w-100">
    <div class="container d-flex">
      <div class="container col-6">
        <a class="navbar-brand fs-4" href="#">FarmDeals</a>
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
                <a class="btn" href="./ProfileModule/profile-page.php">Upload</a>
              </li>
              <li class="nav-item"><a class="btn" href="./ProfileModule/profile-page.php">Profile</a></li>
              <li class="nav-item">
                <button class="btn" type="button" aria-current="page" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop" aria-controls="staticBackdrop">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#90EE90" class="bi bi-person-circle" viewBox="0 0 16 16">
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
                <?php
                  $res = mysqli_query($conn, "SELECT * FROM business_profile WHERE owner = $business_owner");

                  while($row = mysqli_fetch_assoc($res)){
                ?>
                <img style="width: 300px; height:300px;" class="img-fluid img-thumbnail rounded-circle object-fit-cover" src="ProfileModule/img/<?php echo $row['image'] ?>">
                <?php } ?>
            <h3 class="mt-3"><?php echo $business_name?></h3>
              <div class="text-center">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item"><a style="color: black; text-decoration:none;"  href="./ProfileModule/profile-page.php">Upload</a></li>
                  <li class="list-group-item"><a style="color: black; text-decoration:none;"  href="./ProfileModule/profile-page.php">Profile</a></li>
                  <li class="list-group-item"><a style="color: black; text-decoration:none;"  href="./ProfileModule/profile-page.php">Manage Posts</a></li>
                  <li class="list-group-item"><a style="color: black; text-decoration:none;"  href="./ProfileModule/profile-page.php">Update Profile</a></li>
                  <li class="list-group-item">
                    <a style="color: black; text-decoration:none;"  href="">Messages</a>
                    <span class="badge text-bg-primary">4</span>
                  </li>
                  <li class="list-group-item"><a style="color: black; text-decoration:none;"  href="./AccPages/logout.php">Log Out</a></li>
                </ul>
              </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>