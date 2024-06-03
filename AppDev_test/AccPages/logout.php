<?php
session_start();

if(isset($_SESSION['business_name'])){
    unset($_SESSION['business_name']);
    echo "<script>
        window.location = './login-page.php';
      </script>";
}

if(isset($_SESSION['bio'])){
    unset($_SESSION['bio']);
    echo "<script>
        window.location = './login-page.php';
      </script>";
}

if(isset($_SESSION['pfp'])){
    unset($_SESSION['pfp']);
    echo "<script>
        window.location = './login-page.php';
      </script>";
}

if(isset($_SESSION['ownerID'])){
    unset($_SESSION['ownerID']);
    echo "<script>
        window.location = './login-page.php';
      </script>";
}

if(isset($_SESSION['admin_email'])){
    unset($_SESSION['admin_email']);
    echo "<script>
        window.location = './login-page.php';
      </script>";
}

die;
?>