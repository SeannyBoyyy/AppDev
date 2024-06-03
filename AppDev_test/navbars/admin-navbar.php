<?php
include('../config/connectDb.php');

session_start();
if(isset($_SESSION['admin_email'])){
  $admin_email = $_SESSION['admin_email']; 
}else{
  echo 'no admin ';
  echo "<script>
            window.location = '../AccPages/login-page.php';
          </script>";
  exit();
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Z-MarketHub</title>
    <link rel="icon" type="image/x-icon" href="ProfileModule/img/logo.png">
    <link rel="stylesheet" href="../CSS/profilepage-nav.css">
    <link rel="stylesheet" href="../CSS/profile-setup.css">
    <link rel="stylesheet" href="../CSS/profilepage.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
  <nav class="navbar sticky-top border-bottom p-3 w-100" style="background-color: #21d192;">
    <div class="container-fluid">
      <div class="container-fluid text-center">
        <a class="navbar-brand fs-4 justify-content-center" href="../admin-landingPage.php">Z-MarketHub<img src="http://localhost/AppDev/AppDev_test/ProfileModule/img/logo.png" alt="FarmDeals Logo" width="60" height="60"></a>
      </div>
    </div>
  </nav>

