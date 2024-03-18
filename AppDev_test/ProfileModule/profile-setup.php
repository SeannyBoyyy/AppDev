<?php
    include('../config/connectDb.php');
    include('../navbars/farmer-navbar.php');

    $NameSetUp = $bioSetup = $imageSetup = '';
    $errors = array('business_name'=>'', 'business_bio'=>'');
    if(isset($_POST['submit'])){
        $target = "./img/" . basename($_FILES['image']['name']);
        htmlspecialchars($NameSetUp = $_POST['business_name']);
        htmlspecialchars($bioSetup = $_POST['business_bio']);

        $image = $_FILES['image']['name'];


        if(empty($NameSetUp)){
            $errors['business_name'] = 'Business Name is required!';
        }

        if(empty($bioSetup)){
            $errors['business_bio'] = 'Bio is required!';
        }
        if(move_uploaded_file($_FILES['image']['tmp_name'], $target)){
            $sql = 'INSERT INTO business_profile(owner, name, text, image) VALUES (?,?,?,?)';
            $stmt = mysqli_prepare($conn, $sql);
            if($stmt){
                mysqli_stmt_bind_param($stmt, "isss", $ownerID, $NameSetUp, $bioSetup, $image);
                if(mysqli_stmt_execute($stmt)){
                    header('location: ./profile-page.php');
                }else{
                    echo "<script>alert('Erorr')</script>";
                }
            }

        }
           

    }
    

?>

<link rel="stylesheet" href="../CSS/profile-setup.css">
<div class="container-fluid w-50" style="margin-top: 90px;">
    <div class="col-md-6 container-fluid text-center">
        <h1>Set Up you profile</h1>
    </div>
</div>
<div class="container-fluid d-flex align-items-center w-50 mt-5 border rounded-5 p-3 bg-white shadow box-area">
    <form class="row g-3" method="post" enctype="multipart/form-data">
        <div class="form-floating">
            <input type="text" class="form-control" id="floatingInput" name="business_name">
            <label for="floatingInput" style="margin-left: 5px;">Business Name</label>
        </div>
        <div class="form-floating">
            <input type="text" class="form-control" id="floatingInput" placeholder="text" name="business_bio">
            <label for="floatingInput" style="margin-left: 5px;">Bio</label>
        </div>
        <div class="col-12">
            <label for="formFile" class="form-label" style="margin-left: 5px;">Profile Picture</label>
            <input class="form-control" type="file" id="formFile" name="image" accept=".jpg, .jpeg, .png">
        </div>
        <div class="col-12">
            <button class="btn btn-lg  w-100 fs-6" type="submit" style="background-color: #90EE90;" name="submit">Upload</button>
        </div>
    </form>
</div>