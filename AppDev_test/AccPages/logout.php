<?php
session_start();

if(isset($_SESSION['business_name'])){
    unset($_SESSION['business_name']);
}

if(isset($_SESSION['bio'])){
    unset($_SESSION['bio']);
}

if(isset($_SESSION['pfp'])){
    unset($_SESSION['pfp']);
}

if(isset($_SESSION['ownerID'])){
    unset($_SESSION['ownerID']);
}

if(isset($_SESSION['admin_email'])){
    unset($_SESSION['admin_email']);
}
header('Location: ./login-page.php');
die;
?>