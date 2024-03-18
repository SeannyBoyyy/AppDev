<?php
include('../config/connectDb.php');
session_start();

// Check if ownerID is set in the session
if(isset($_SESSION['ownerID'])){
    $ownerID = $_SESSION['ownerID'];
    
    // Check if business_name is set in the session
    if(isset($_SESSION['business_name'])){
        $business_name = $_SESSION['business_name'];
    } else {
        $business_name = 'no business name yet';
    }

    // Check if bio is set in the session
    if(isset($_SESSION['bio'])){
        $business_bio = $_SESSION['bio'];
    } else {
        $business_bio = "no bio yet";
    }

    // Check if pfp is set in the session
    if(isset($_SESSION['pfp'])){
        $business_pfp = $_SESSION['pfp'];
    } else {
        $business_pfp = 'no pfp yet';
        echo 'pfp error';
    }
} else {
    echo 'error';
}

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile Page</title>
    <link rel="stylesheet" href="../CSS/profilepage-nav.css">
    <link rel="stylesheet" href="../CSS/profile-setup.css">
    <link rel="stylesheet" href="../CSS/profilepage.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
  <nav class="navbar sticky-top shadow box-area p-3" style="background-color: white;">
    <div class="container-fluid">
      <div class="container-fluid text-center">
        <a class="navbar-brand fs-4 justify-content-center" href="../index.php">FarmDeals</a>
      </div>
    </div>
  </nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
