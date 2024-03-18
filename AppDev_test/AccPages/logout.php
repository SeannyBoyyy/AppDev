<?php
session_start();

if(isset($_SESSION['business_name'])){
    unset($_SESSION['business_name']);
}

header('Location: ./login-page.php');
die;
?>