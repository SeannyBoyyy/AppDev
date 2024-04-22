

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

.navbar-nav .nav-link {
    display: block;
    font-size: 18px;
    font-weight: bold;
    color: #333;
    padding: 10px 15px;
    transition: color 0.5s ease;
    border: none !important;
}

.navbar-nav .nav-link:hover {
    color: white;
    transform: translateY(-5px);
    border-radius:15px;
    height:50px;
}
.navbar-nav i{
  margin-right:20px;
}

    </style>
  </head>
  <body>
  <nav class="navbar navbar sticky-top p-3" style="background-color: white;">
    <div class="container-fluid">
        <a class="navbar-brand fs-4" href="./viewer-landingPage.php" style="width:100px;">FarmDeals<img src="http://localhost/AppDev/AppDev/AppDev_test/ProfileModule/img/logo.jpg" alt="FarmDeals Logo" width="60" height="60"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation" style="border:2px solid black;border-radius:10px;">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <div class="navbar-nav">
                <a class="nav-link" aria-current="page" href="#farms" style="color:black;"><i class="fas fa-home"></i>Farm</a>
                <a class="nav-link" aria-current="page" href="#" style="color:black;"><i class="fas fa-boxes"></i>Products</a>
                <a class="nav-link" aria-current="page" href="./AccPages/login-page.php" style="color:black;"><i class="fas fa-sign-in-alt"></i>Log In</a>
                <a class="nav-link" aria-current="page" href="./AccPages/register-page.php" style="color:black;"><i class="fas fa-user"></i>Register</a>
            </div>
        </div>
    </div>
  </nav>
</body>

</html>