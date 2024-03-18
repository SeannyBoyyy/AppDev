<?php
    include('../config/connectDb.php');
    include('../navbars/farmer-navbar.php');

    $newBusinessName = $newBio = '';

?>

<link rel="stylesheet" href="../CSS/profile-setup.css">
<div class="container-fluid w-50" style="margin-top: 90px;">
    <div class="col-md-6 container-fluid text-center">
        <h1>Set Up you profile</h1>
    </div>
</div>
<div class="container-fluid d-flex align-items-center w-50 mt-5 border rounded-5 p-3 bg-white shadow box-area">
    <form class="row g-3">
        <div class="form-floating">
            <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput" style="margin-left: 5px;">Business Name</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" id="floatingPassword" placeholder="Password">
            <label for="floatingPassword" style="margin-left: 5px;">Bio</label>
        </div>
        <div class="col-12">
            <label for="formFile" class="form-label" style="margin-left: 5px;">Profile Picture</label>
            <input class="form-control" type="file" id="formFile">
        </div>
        <div class="col-12">
            <button class="btn btn-lg  w-100 fs-6" type="submit" style="background-color: #90EE90;">Upload</button>
        </div>
    </form>
</div>