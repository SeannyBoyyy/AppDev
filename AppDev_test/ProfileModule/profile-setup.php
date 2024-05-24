<?php
    include('../config/connectDb.php');
    include('../navbars/subs-navbar.php');

    session_start();
    if(isset($_SESSION['ownerID'])){
        $business_owner = $_SESSION['ownerID']; 
    }else{
        echo 'no owner ';
        header('Location: ../AccPages/login-page.php');
        exit();
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
                    header('location: ./profile-page.php?active=profile');
                }else{
                    echo "
                <script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Error!',
                        icon: 'error'
                    }).then(function() {
                        window.location = 'profile-setup.php';
                    });
                </script>";
        
                exit();
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
         /* Custom styles */
        body {
            background-color: whitesmoke;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            min-height: 100vh; /* Ensure the background covers the entire viewport height */
        }
        .container-border {
            border: 1px solid black;
            border-radius: 25px;
            padding: 40px; /* Increased padding for more space */
            background: rgba(255, 255, 255, 0.5);
        }
        .image-container {
            border: 2px solid black;
            border-radius: 15px;
            overflow: hidden;
            width: 100%;
            max-width: 300px;
            margin: 0 auto;
        }
        #preview {
            width: 100%;
            height: auto;
        }
        .btn.upload-btn {
            width: 100%;
            border-radius: 15px;
            background-color: #90EE90; /* Button color */
        }
        .upload-btn:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;/
        }
    </style>
</head>
<body>

<div class="container-fluid mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="text-center mb-4">Set Up Your Profile</h1> <!-- Title outside the container -->
            <div class="container-border">
                <form class="row g-3" method="post" enctype="multipart/form-data">
                    <!-- Profile Picture -->
                    <div class="col-md-6">
                        <div class="text-center">
                            <h5>Profile Picture</h5> <!-- Text indicating profile picture upload -->
                            <div class="image-container mb-3">
                                <img id="preview" src="#" alt="Preview Image" style="display:none;">
                            </div>
                            <input class="form-control mt-3" type="file" id="formFile" name="image" accept=".jpg, .jpeg, .png" onchange="previewImage(event)">
                        </div>
                    </div>
                    <!-- Profile Details -->
                    <div class="col-md-6">
                        <!-- Business Name -->
                        <div class="col-md-12 mb-3">
                            <input type="text" class="form-control" placeholder="Business Name" name="business_name">
                            <small class="text-danger"><?php echo $errors['business_name'] ?></small>
                        </div>
                        <!-- Bio -->
                        <div class="col-md-12 mb-3">
                            <input type="text" class="form-control" placeholder="Bio" name="business_bio">
                            <small class="text-danger"><?php echo $errors['business_bio'] ?></small>
                        </div>
                        <!-- Email -->
                        <div class="col-md-12 mb-3">
                            <input type="email" class="form-control" placeholder="Email" name="email">
                        </div>
                        <!-- Contact Number -->
                        <div class="col-md-12 mb-3">
                            <input type="text" class="form-control" placeholder="Contact Number" name="contact_number">
                        </div>
                        <!-- Address -->
                        <div class="col-md-12 mb-3">
                            <input type="text" class="form-control" placeholder="Address" name="address">
                        </div>
                    </div>
                    <!-- Upload Button -->
                    <div class="col-md-12 text-center mt-5 mb-3">
                        <button class="btn upload-btn w-50" type="submit" name="submit">Upload</button>
                    </div>
                </form>
            </div>
        </div>  
    </div>
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