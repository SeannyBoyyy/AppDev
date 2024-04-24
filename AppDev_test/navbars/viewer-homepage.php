<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link rel="stylesheet" href="../CSS/view-farm.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
      .nav-link:hover{
        color:black;
        transition: color 0.3s ease, transform 0.3s ease;
        transform: translateY(-5px);
      }
      </style>
  </head>
  <body>
  <nav class="navbar navbar sticky-top p-3" style="background-color: whitesmoke;">
    <div class="container-fluid">
        <a class="navbar-brand fs-4" href="./viewer-landingPage.php">Z-MarketHub<img src="http://localhost/AppDev/AppDev_test/ProfileModule/img/logo.png" alt="FarmDeals Logo" width="60" height="60"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <div class="navbar-nav ">
                <a class="nav-link" aria-current="page" href="#"><i class="fas fa-tractor" style="padding:10px;"></i>Business</a>
                <a class="nav-link" aria-current="page" href="#"><i class="fas fa-shopping-basket" style="padding:10px;"></i>Products</a>
                <a class="nav-link" aria-current="page" href="./AccPages/login-page.php"><i class="fas fa-sign-in-alt" style="padding:10px;"></i>Log In</a>
                <a class="nav-link" aria-current="page" href="./AccPages/register-page.php"><i class="fas fa-user" style="padding:10px;"></i>Register</a>
            </div>
        </div>
    </div>
  </nav>
</body>

</html>