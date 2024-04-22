<?php
    include('../config/connectDb.php');
   session_start();
   if(isset($_SESSION['ownerID'])){
    $business_owner = $_SESSION['ownerID']; 
  }else{
    echo 'no owner ';
  }

    $NameSetUp = $bioSetup = $imageSetup = '';
    $errors = array('business_name'=>'', 'business_bio'=>'');
    if(isset($_POST['submit'])){
        $file_name = $_FILES['image']['name'];
        $tempName = $_FILES['image']['tmp_name'];
        $folder = 'img/'.$file_name;
        $NameSetUp = htmlspecialchars($_POST['business_name']);
        $bioSetup = htmlspecialchars($_POST['business_bio']);

        $newAddress = $_POST['address'];
        $newEmail = $_POST['email'];
        $newContact_number = $_POST['contact_number'];

        if(empty($NameSetUp)){
            $errors['business_name'] = 'Business Name is required!';
        }

        if(empty($bioSetup)){
            $errors['business_bio'] = 'Bio is required!';
        }
        if(move_uploaded_file($tempName, $folder)){
            $sql = 'INSERT INTO business_profile(owner, name, text, image, address, email, contact_number) VALUES (?,?,?,?,?,?,?)';
            $stmt = mysqli_prepare($conn, $sql);
            if($stmt){
                mysqli_stmt_bind_param($stmt, "issssss", $business_owner, $NameSetUp, $bioSetup, $file_name, $newAddress, $newEmail, $newContact_number);
                if(mysqli_stmt_execute($stmt)){
                    header('location: ./profile-page.php');
                }else{
                    echo "<script>alert('Erorr')</script>";
                }
            }

        }
           

    }
    

?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link rel="stylesheet" href="../CSS/farmer-van.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .container .form-floating {
            width:350px;
        }
        .container{
            width:800px;
            height:700px;
            display:flex;
            align-items:center;
            margin-top:50px;
            border:1px solid black ;
            border-radius: 25px;
            background:rgba(255,255,255, 0.5); 
        }
        #preview {
            width: 300px; 
            height:200px; 
            margin-left:10px;
            margin-top:3px;
        }
        .text-center .btn {
            width:300px;
            border-radius: 15px;
        }
        .text-center.btn:hover {
            background-color: #ffffff;
            color:rgba(0,0,0,0.1)
        }
        .text-center.btn:active {
            background-color: #6DBA9E;
        }
        .upload-btn:hover {
        transform: translateY(-5px);
        transition: transform 0.3s ease;
        }
        
    </style>
</head>
<body style="background-image: url('http://localhost/AppDev/AppDev/AppDev_test/ProfileModule/img/R16731_product.jpg');background-size: cover; background-repeat: no-repeat;">
<link rel="stylesheet" href="../CSS/profile-setup.css">
<div class="container-fluid w-50" style="margin-top: 90px;">
    <div class="col-md-6 container-fluid text-center">
        <h1>Set Up you profile</h1>
    </div>
</div>
<div class="container">
    <form class="row g-3" method="post" enctype="multipart/form-data">
    
        <!-- 左側欄位（Profile Picture） -->
        <div class="col-md-6">
            <div class="text-center" style="margin-left:50px;">
                <!-- 預覽圖片容器 -->
                <h4 class="form-label" style="text-align:left;margin-top:15px;font-size:28px;font-weight:bold;color:black">Profile Picture:</h4>
                <div class="image-container" style="width:325px;height:210px;border: 2px solid black;border-radius: 15px; ">
                    <img id="preview" src="#" alt="Preview Image" style="display:none;">
                </div>
                <div style="height:50px;"></div>
                <input class="form-control" style="width:325px;" type="file" id="formFile" name="image" accept=".jpg, .jpeg, .png" onchange="previewImage(event)">
            </div>
        </div>

        <!-- 右側欄位 -->
        <div class="col-md-6">
            
            <!-- Business Name -->
            <div class="col-md-12 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control" id="floatingInput" name="business_name" style="border-radius:15px;margin-right:20px;">
                    <label for="floatingInput">Business Name</label>
                    <small class="text-red mb-2" style="color:red"><?php echo $errors['business_name'] ?></small>
                </div>
            </div>

            <!-- Bio -->
            <div class="col-md-12 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control" id="floatingInput" name="business_bio" style="border-radius:15px;">
                    <label for="floatingInput">Bio</label>
                    <small class="text-red mb-2" style="color:red"><?php echo $errors['business_bio'] ?></small>
                </div>
            </div>
            
            <!-- Email -->
            <div class="col-md-12 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control" id="floatingInput" name="email" style="border-radius:15px;">
                    <label for="floatingInput">Email</label>
                </div>
            </div>
            
            <!-- Contact Number -->
            <div class="col-md-12 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control" id="floatingInput" name="contact_number" style="border-radius:15px;">
                    <label for="floatingInput">Contact Number</label>
                </div>
            </div>

            <!-- Address -->
            <div class="col-md-12 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control" id="floatingInput" name="address" style="border-radius:15px;">
                    <label for="floatingInput">Address</label>
                </div>
            </div>
        </div>
            <!-- 上傳按鈕 -->
        <div class="col-md-12 text-center" style="margin-top:50px;">
            <button class="btn upload-btn" type="submit" style="background-color: #90EE90;" name="submit">Upload</button>
        </div>
    </form>
</div>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var imgElement = document.getElementById("preview");
            imgElement.src = reader.result;
            imgElement.style.display = "block";
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
</body>