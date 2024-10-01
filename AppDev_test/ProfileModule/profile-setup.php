<?php
    include('../config/connectDb.php');
    include('../navbars/setup-nav.php');

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
    <title>Set Up Your Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f9f9;
        }

        .container-border {
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 40px;
            background-color: white;
        }

        h1 {
            color: #333;
            font-weight: 600;
        }

        h5 {
            font-weight: 500;
            color: #555;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .btn.upload-btn {
            width: 100%;
            border-radius: 8px;
            background-color: #28a745;
            color: white;
            font-weight: 500;
            border: none;
        }

        .btn.upload-btn:hover {
            background-color: #218838;
        }

        .divider {
            border-left: 1px solid #ccc;
            height: 100%;
        }

        .image-container {
            border: 2px solid #ddd;
            border-radius: 10px;
            width: 100%;
            max-width: 250px;
            height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin: 0 auto; /* Center the entire container */
        }

        #preview {
            max-width: 100%;
            max-height: 100%;
        }

        .form-section {
            padding: 20px 0;
        }

        small {
            color: #e3342f;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1 class="text-center mb-4">Set Up Your Profile</h1>
            <div class="container-border">
                <form class="row g-4" method="post" enctype="multipart/form-data">
                    <!-- Profile Picture Section -->
                    <div class="col-md-4 text-center">
                        <h5>Profile Picture</h5>
                        <div class="image-container mb-3">
                            <img id="preview" src="#" alt="Your Image" style="display:none;">
                        </div>
                        <input class="form-control" type="file" id="formFile" name="image" accept=".jpg, .jpeg, .png" onchange="previewImage(event)">
                    </div>

                    <!-- Vertical Divider -->
                    <div class="col-md-1 d-flex justify-content-center align-items-center">
                        <div class="divider"></div>
                    </div>

                    <!-- Business Profile Section -->
                    <div class="col-md-7 form-section">
                        <div class="mb-3">
                            <label for="businessName" class="form-label">Business Name</label>
                            <input type="text" class="form-control" id="businessName" placeholder="Enter Business Name" name="business_name">
                            <small><?php echo $errors['business_name'] ?? ''; ?></small>
                        </div>

                        <div class="mb-3">
                            <label for="businessBio" class="form-label">Business Bio</label>
                            <textarea class="form-control" id="businessBio" rows="3" placeholder="Enter Business Bio" name="business_bio"></textarea>
                            <small><?php echo $errors['business_bio'] ?? ''; ?></small>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter Email" name="email">
                        </div>

                        <div class="mb-3">
                            <label for="contactNumber" class="form-label">Contact Number</label>
                            <input type="text" class="form-control" id="contactNumber" placeholder="Enter Contact Number" name="contact_number">
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" placeholder="Enter Address" name="address">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12 text-center mt-4">
                        <button class="btn upload-btn w-50" type="submit" name="submit">Upload Profile</button>
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
