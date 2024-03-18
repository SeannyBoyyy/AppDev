<?php
include('../navbars/farmer-navbar.php');
?>

<div class="container-fluid w-50" style="margin-top: 90px;">
    <div class="col-md-6 container-fluid text-center">
        <h1>Create a new post</h1>
    </div>
</div>
    <div class="container-fluid d-flex align-items-center mt-5 w-50 border rounded-5 p-3 bg-white shadow box-area p-5">
        <form class="row w-100 g-3">
            <div class="col-12">
                <label for="formFile" class="form-label" style="margin-left: 5px;">Upload a picture</label>
                <input class="form-control" type="file" id="formFile">
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="floatingPassword" placeholder="Password">
                <label for="floatingPassword" style="margin-left: 5px;">Write something about the post.</label>
            </div>
            <div class="col-12">
                <button class="btn btn-lg fs-6 w-100" type="submit" style="background-color: #90EE90;">Upload</button>
            </div>
        </form>
    </div>
</div>