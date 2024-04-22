<?php
include('../config/connectDb.php');
include('../navbars/profilepage-nav.php');

session_start();
if(isset($_SESSION['ownerID'])){
    $business_owner = $_SESSION['ownerID']; 
}else{
    echo 'no owner ';
}

    $getSql = "SELECT name, text, image, address, email, contact_number FROM business_profile WHERE owner = ?";
    $stmt = mysqli_prepare($conn, $getSql);
    mysqli_stmt_bind_param($stmt, "i", $business_owner);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($fromBusinessProfile = mysqli_fetch_assoc($result)) {
        $business_name = $fromBusinessProfile['name'];
        $business_bio = $fromBusinessProfile['text'];
        $business_pfp = $fromBusinessProfile['image'];
        $business_address = $fromBusinessProfile['address'];
        $business_email = $fromBusinessProfile['email'];
        $business_contact_number = $fromBusinessProfile['contact_number'];
    } else {
        echo 'Failed to retrieve updated information or no data found';
        
    }


if(isset($_POST['submit'])) {
    $business_owner = $_SESSION['ownerID'];
    $file_name = $_FILES['image']['name'];
    $tempName = $_FILES['image']['tmp_name'];
    $folder = 'img/'.$file_name;
    $newBussName = $_POST['name_buss'];
    $newBio = $_POST['bio'];

    $newAddress = $_POST['address'];
    $newEmail = $_POST['email'];
    $newContact_number = $_POST['contact_number'];

    // Move uploaded image to target directory
    if(move_uploaded_file($tempName, $folder)){
        // Prepare SQL statement to update the database
        $sql = "UPDATE business_profile 
        SET name = ?, text = ?, image = ?, address = ?, email = ?, contact_number = ?
        WHERE owner = ?";
        $stmt = mysqli_prepare($conn, $sql);

        // Check if the statement is prepared successfully
        if($stmt) {
            // Bind parameters and execute the statement
            mysqli_stmt_bind_param($stmt, "ssssssi", $newBussName, $newBio, $file_name, $newAddress, $newEmail, $newContact_number, $business_owner);
            mysqli_stmt_execute($stmt);

            // Check if the query executed successfully
            if(mysqli_stmt_affected_rows($stmt) > 0) {
                echo "<script> 
                        alert('Updated successfully'); 
                        window.location.replace('profile-page.php');
                    </script>";
            } else {
                echo "<script> 
                        alert(Error updating profile.)
                    </script>";
            }
        } else {
            echo 'Error preparing statement: ' . mysqli_error($conn);
        }
    } else {
        echo 'Error uploading image.';
    }
}

// ---------------------------------- add post ----------------------------------------
if (isset($_POST["upload_product"])) {
  $name_product = mysqli_real_escape_string($conn, $_POST["name_product"]);
  $text_product = mysqli_real_escape_string($conn, $_POST["text_product"]);
  

    if ($_FILES["image_product"]["error"] === 4) {
        echo "<script> alert('Image does not exist'); </script>";
    } else {
        $fileName = $_FILES["image_product"]["name"];
        $fileSize = $_FILES["image_product"]["size"];
        $tmpName = $_FILES["image_product"]["tmp_name"];

        $validImageExtension = ['jpg', 'jpeg', 'png'];
        $imageExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        if (!in_array($imageExtension, $validImageExtension)) {
            echo "<script> alert('Invalid image extension'); </script>";
        } elseif ($fileSize > 10000000) {
            echo "<script> alert('Image size is too large'); </script>";
        } else {
            $newImageName = uniqid() . '.' . $imageExtension;
            $uploadPath = 'img/' . $newImageName;
        
            move_uploaded_file($tmpName, $uploadPath);

            $text_product = mysqli_real_escape_string($conn, $_POST["text_product"]);

            // Fetch the business profile ID associated with the current owner
            $getBusinessProfileIdSql = "SELECT id FROM business_profile WHERE owner = ?";
            $stmt = mysqli_prepare($conn, $getBusinessProfileIdSql);
            mysqli_stmt_bind_param($stmt, "i", $business_owner);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $businessProfile = mysqli_fetch_assoc($result);
            $businessProfileId = $businessProfile['id'];

            // Insert into posting_module with the retrieved business profile ID
            $query = "INSERT INTO posting_module (name, text, image, posted_by) VALUES ('$name_product', '$text_product', '$newImageName', $businessProfileId)";
            mysqli_query($conn, $query);

            echo "<script> 
                        alert('Image uploaded successfully'); 
                        window.location.replace('profile-page.php');
                </script>";

        }
    }
}


// ---------------------------------- advertisement module ----------------------------------------
if (isset($_POST["upload_advertisement"])) {
    $name_advertisement = mysqli_real_escape_string($conn, $_POST["name_advertisement"]);
    $text_advertisement = mysqli_real_escape_string($conn, $_POST["text_advertisement"]);
    
  
      if ($_FILES["image_advertisement"]["error"] === 4) {
          echo "<script> alert('Image does not exist'); </script>";
      } else {
          $fileName = $_FILES["image_advertisement"]["name"];
          $fileSize = $_FILES["image_advertisement"]["size"];
          $tmpName = $_FILES["image_advertisement"]["tmp_name"];
  
          $validImageExtension = ['jpg', 'jpeg', 'png'];
          $imageExtension = pathinfo($fileName, PATHINFO_EXTENSION);
  
          if (!in_array($imageExtension, $validImageExtension)) {
              echo "<script> alert('Invalid image extension'); </script>";
          } elseif ($fileSize > 10000000) {
              echo "<script> alert('Image size is too large'); </script>";
          } else {
              $newImageName = uniqid() . '.' . $imageExtension;
              $uploadPath = 'img/' . $newImageName;
          
              move_uploaded_file($tmpName, $uploadPath);
  
              $text_advertisement = mysqli_real_escape_string($conn, $_POST["text_advertisement"]);
  
              // Fetch the business profile ID associated with the current owner
              $getBusinessProfileIdSql = "SELECT id FROM business_profile WHERE owner = ?";
              $stmt = mysqli_prepare($conn, $getBusinessProfileIdSql);
              mysqli_stmt_bind_param($stmt, "i", $business_owner);
              mysqli_stmt_execute($stmt);
              $result = mysqli_stmt_get_result($stmt);
              $businessProfile = mysqli_fetch_assoc($result);
              $businessProfileId = $businessProfile['id'];
  
              // Insert into posting_module with the retrieved business profile ID
              $query = "INSERT INTO business_advertisement (name, text, image, posted_by) VALUES ('$name_advertisement', '$text_advertisement', '$newImageName', $businessProfileId)";
              mysqli_query($conn, $query);
  
              echo "<script> 
                          alert('Image uploaded successfully'); 
                          window.location.replace('profile-page.php');
                  </script>";
  
          }
      }
  }

  // ---------------------------------- Farm Photos module ----------------------------------------
    if (isset($_POST["upload_photos"])) {
        
      if ($_FILES["image_farm"]["error"] === 4) {
          echo "<script> alert('Image does not exist'); </script>";
      } else {
          $fileName = $_FILES["image_farm"]["name"];
          $fileSize = $_FILES["image_farm"]["size"];
          $tmpName = $_FILES["image_farm"]["tmp_name"];
  
          $validImageExtension = ['jpg', 'jpeg', 'png'];
          $imageExtension = pathinfo($fileName, PATHINFO_EXTENSION);
  
          if (!in_array($imageExtension, $validImageExtension)) {
              echo "<script> alert('Invalid image extension'); </script>";
          } elseif ($fileSize > 10000000) {
              echo "<script> alert('Image size is too large'); </script>";
          } else {
              $newImageName = uniqid() . '.' . $imageExtension;
              $uploadPath = 'img/' . $newImageName;
          
              move_uploaded_file($tmpName, $uploadPath);
  
              // Fetch the business profile ID associated with the current owner
              $getBusinessProfileIdSql = "SELECT id FROM business_profile WHERE owner = ?";
              $stmt = mysqli_prepare($conn, $getBusinessProfileIdSql);
              mysqli_stmt_bind_param($stmt, "i", $business_owner);
              mysqli_stmt_execute($stmt);
              $result = mysqli_stmt_get_result($stmt);
              $businessProfile = mysqli_fetch_assoc($result);
              $businessProfileId = $businessProfile['id'];
  
              // Insert into posting_module with the retrieved business profile ID
              $query = "INSERT INTO business_photos (image, posted_by) VALUES ('$newImageName', $businessProfileId)";
              mysqli_query($conn, $query);
  
              echo "<script> 
                          alert('Image uploaded successfully'); 
                          window.location.replace('profile-page.php');
                  </script>";
  
          }
      }
  }

  if (isset($_POST['photos_delete'])) {
    // Delete operation
    $id = $_POST['id'];
    $query = "DELETE FROM business_photos WHERE id = $id";
    mysqli_query($conn, $query);
    echo "<script> 
            alert('Image delete successfully'); 
            window.location.replace('profile-page.php');
          </script>";
  }

    // ---------------------------------- messages module ----------------------------------------
        $getBusinessProfileIdSql = "SELECT id FROM business_profile WHERE owner = ?";
        $stmt = mysqli_prepare($conn, $getBusinessProfileIdSql);
        mysqli_stmt_bind_param($stmt, "i", $business_owner);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $businessProfile = mysqli_fetch_assoc($result);
        $businessProfileId = $businessProfile['id'];

        $countSql = "SELECT sent_to, COUNT(*) AS occurrences
        FROM message_module
        WHERE sent_to = ' $businessProfileId'
        GROUP BY sent_to";

        $result = $conn->query($countSql);

        // Check if there are results
        if ($result->num_rows > 0) {
            // Fetch and output each row of data
            while($row = $result->fetch_assoc()) {
                $msgCount =  $row["occurrences"];
            }
        }else{
            $msgCount = 0;
        }


        $getMsgQuery = "SELECT sent_by, message, created_at FROM message_module WHERE sent_to = ?";
        $stmt = mysqli_prepare($conn, $getMsgQuery);
        mysqli_stmt_bind_param($stmt, "i", $businessProfileId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        

        if (mysqli_num_rows($result) > 0){
            // Fetch posting modules
            $msgRows = mysqli_fetch_all($result, MYSQLI_ASSOC);
            }

?>
<head>
<style>
    .nav-link {
        font-size: 18px;
        font-weight: bold;
        color: #333; 
        transition: color 0.3s ease;
        border: none !important;
    }
    .nav-link:hover {
        border: none !important;
        color: black;
        transform: translateY(-5px);
    }
    .nav-link.active {
        border: none !important;
        background-color: rgb(192, 192, 192);
        color: white;
        padding: 10px 15px;
    }
    .nav-link:focus {
        outline: none !important;
        box-shadow: none !important;
    }
    #preview1 {
        width: 400px;
        height: 300px;
    }

    #preview2 {
        width: 210px;
        height: 100px;
        margin-left:5px;
        margin-top:5px;
    }
</style>
</head>
<link rel="stylesheet" href="../CSS/profile-setup.css">
<link rel="stylesheet" href="../CSS/profilepage.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<body style="background-image: url('http://localhost/AppDev/AppDev/AppDev_test/ProfileModule/img/profilebg.jpg');background-size: cover; background-repeat: no-repeat;">
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-lg-3 col-12 text-center border rounded-5" style="margin-left:20px;background-color: rgba(192, 192, 192, 0.7)">
            <div>
                <?php
                  $res = mysqli_query($conn, "SELECT * FROM business_profile WHERE owner = $business_owner");
                  
                  while($row = mysqli_fetch_assoc($res)){
                ?>
                <img class="img-fluid img-thumbnail rounded-circle object-fit-cover" style="height: 300px; width:300px;margin-top:20px;" src="img/<?php echo $row['image']; ?>">
                <?php } ?>
            </div>
            <h3 class="mt-3" style="color:black; font-size:40px"><i class="fas fa-building" style="margin-right: 15px;"></i><?php echo $business_name?></h3>
            
            <div class="container-fluid mt-3 bg-white border rounded-5" style="width: 350px;padding:10px;margin-bottom:20px;">
                <div class="nav flex-column nav-pills text-start align-items-start" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link d-flex justify-content-start align-items-center" type="button" onclick="window.location.href='../index.php'"><i class="fas fa-home" style="margin-right: 8px;"></i>Home</button>   
                    <button class="nav-link d-flex justify-content-start align-items-center"  id="v-pills-upload-tab" data-bs-toggle="pill" data-bs-target="#v-pills-upload" type="button" role="tab" aria-controls="v-pills-upload" aria-selected="false"><i class="fas fa-upload"style="margin-right: 8px;"></i>Upload Product</button>
                    <button class="nav-link d-flex justify-content-start align-items-center active" id="v-pills-manageProduct-tab" data-bs-toggle="pill" data-bs-target="#v-pills-manageProduct" type="button" role="tab" aria-controls="v-pills-manageProduct" aria-selected="false"><i class="fas fa-tasks"style="margin-right: 8px;"></i>Manage Post</button>
                    <button class="nav-link d-flex justify-content-start align-items-center" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false"><i class="fas fa-user"style="margin-right: 8px;"></i>Update Profile</button>
                    <button class="nav-link d-flex justify-content-start align-items-center" id="v-pills-advertisement-tab" data-bs-toggle="pill" data-bs-target="#v-pills-advertisement" type="button" role="tab" aria-controls="v-pills-advertisement" aria-selected="false"><i class="fas fa-ad"style="margin-right: 8px;"></i>Upload Advertisement</button>
                    <button class="nav-link d-flex justify-content-start align-items-center" id="v-pills-photos-tab" data-bs-toggle="pill" data-bs-target="#v-pills-photos" type="button" role="tab" aria-controls="v-pills-photos" aria-selected="false"><i class="fas fa-images"style="margin-right: 8px;"></i>Upload Farm Photos</button>
                    <button class="nav-link d-flex justify-content-start align-items-center" id="v-pills-message-tab" data-bs-toggle="pill" data-bs-target="#v-pills-message" type="button" role="tab" aria-controls="v-pills-message" aria-selected="false"><i class="fas fa-envelope"style="margin-right: 8px;"></i>
                        Message
                        <span class="badge rounded-pill bg-primary">
                            <?php echo $msgCount ?>
                            
                        </span>
                    </button>
                    <button class="nav-link d-flex justify-content-start align-items-center" type="button" onclick="window.location.href='../AccPages/logout.php'"><i class="fas fa-sign-out-alt" style="margin-right: 8px;"></i>Log Out</button>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-12 d-flex bg-white border rounded-5 align-items-top" style="width:1000px;margin-left:200px;">
            <div class="tab-content container-fluid" id="v-pills-tabContent">
                <!------------------------------------- Upload-Product Module  ---------------------------------->
                <div class="tab-pane fade" id="v-pills-upload" role="tabpanel" aria-labelledby="v-pills-upload-tab" style="width:900px;">
                    <div class="container-fluid" style="margin-bottom:20px;">
                        <div class="container-fluid">
                            <div class="">
                                <h1 style="font-family: Verdana, Geneva, Tahoma, sans-serif;font-size: 50px;color:black;font-weight: bold;margin-bottom:50px;margin-top:30px;">Upload a new Product</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 d-flex align-items-center mt-3 border rounded-5 p-3" style="border:1px solid black;width:950px;height:600px;background: rgba(255,255,255, 0.9)">
                    <form class="row w-100 g-3" action="" method="post" enctype="multipart/form-data">
                        <!-- left -->
                        <div class="col-lg-6 col-12">
                        <!-- Product Name -->
                            <div class="mb-3">
                                <label for="floatingInput" class="form-label" style="margin-left:30px;">Product Name</label>
                                <input type="text" class="form-control" id="floatingInput name_product" placeholder="" name="name_product" value="" style="margin-left:30px;width:300px;">
                            </div>
        
                            <!-- Write something about the Product -->
                            <div class="mb-3">
                                <label for="exampleFormControlTextarea1" class="form-label" style="margin-left:30px;">Write something about the Product.</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="5" name="text_product" style="margin-left:30px; width:300px;height:300px;"></textarea>
                            </div>
        
                        <!-- Upload -->
                            <div class="col-12">
                                <button class="btn btn-lg fs-6" type="upload_product" name="upload_product" style="background-color: #90EE90; width:300px;margin-left:30px;margin-bottom:20px;">Upload</button>
                                </div>
                            </div>
                        <!-- right -->
                        <div class="col-lg-6 col-12">
                        <!-- preview -->
                        <div class="mb-3">
                            <label for="formFile1" class="form-label" style="font-size:20px;margin-left:30px;">Upload a picture</label>
                                <div class="image-container" style="width:425px;height:310px;border: 2px solid black;border-radius: 15px;margin-right:30px;margin-top:50px;">
                                    <img id="preview1" src="#" alt="Preview Image" style="display:none;">
                                </div>
                                    <input class="form-control" style="width:300px;margin-left:30px;margin-top:30px;" type="file" id="formFile1" name="image" accept=".jpg, .jpeg, .png" onchange="previewImage(event, 'preview1')">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                <div style="margin-top:50px;margin-bottom:50px;"></div>
                <!------------------------------------- Posting-Management Module  ---------------------------------->
                <div class="tab-pane fade show active container-fluid" id="v-pills-manageProduct" role="tabpanel" aria-labelledby="v-pills-manageProduct-tab"> 
                    <div class="row container-fluid">
                        <div class="nav container-fluid">
                            <div class="justify-content-start align-items-start">
                                <ul class="nav nav-underline top-0 start-0" id="nav-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Farm Photos</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Product Management</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Advertisement Management</a>                           
                                    </li>
                                
                                </ul>
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
                                            <!------------------------------------- Farm Photos Management Module  ---------------------------------->
                                        <div class="container-fluid" style="margin:auto;">
                                                <div class="container-fluid">
                                                    <div class="container-fluid">
                                                        <br>
                                                        <h1 style="font-family: Verdana, Geneva, Tahoma, sans-serif;font-size: 50px;color:black;font-weight: bold;">Farm Photos Module</h1>
                                                        <br>
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="row justify-content-center align-items-center"> <!-- Center the content -->
                                            <div class="container-fluid"> <!-- Adjust the column width as needed -->
                                                    <div class="table-responsive">
                                                        <!-- Display Table -->  
                                                        <table class="table table-striped table-borderless overflow-auto">
                                                            <tr>
                                                                <td>#</td>
                                                                <td>Image</td>
                                                                <td>Created At</td> <!-- Added Created At column -->

                                                                <td>Action</td>
                                                            </tr>
                                                            <?php
                                                            $i = 1;
                                                            // Modify the SQL query to select products associated with the current user's business profile
                                                            $query = "SELECT * FROM business_photos WHERE posted_by IN (SELECT id FROM business_profile WHERE owner = ?) ORDER BY id DESC";
                                                            $stmt = mysqli_prepare($conn, $query);
                                                            mysqli_stmt_bind_param($stmt, "i", $business_owner);
                                                            mysqli_stmt_execute($stmt);
                                                            $result = mysqli_stmt_get_result($stmt);
                                                            foreach ($result as $row) :
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $i++; ?></td>
                                                                    <td><img src="img/<?php echo $row['image']; ?>" width="250" height="150" title=""></td>
                                                                    <td><?php echo $row["created_at"]; ?></td> <!-- Display Created At -->
                                                                    <!-- ... -->
                                                                    <td>
                                                                        <!-- CRUD Operations Form -->
                                                                        <form action="" method="post" enctype="multipart/form-data">
                                                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                                            <button class="btn btn-danger mb-2" type="submit" name="photos_delete">Delete</button>
                                                                        </form>
                                                                    </td>
                                                                    <!-- ... -->
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </table>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade show active" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
                                        <!------------------------------------- Product-Management Module  ---------------------------------->
                                        <div class="container-fluid" style="margin:auto;">
                                                <div class="container-fluid">
                                                    <div class="container-fluid">
                                                        <br>
                                                        <h1 style="font-family: Verdana, Geneva, Tahoma, sans-serif;font-size: 50px;color:black;font-weight: bold;">Post Module</h1>
                                                        <br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row justify-content-center align-items-center"> <!-- Center the content -->
                                                <div class="container-fluid"> <!-- Adjust the column width as needed -->
                                                    <div class="table-responsive">
                                                        <!-- Display Table -->  
                                                        <table class="table table-striped table-borderless overflow-auto">
                                                            <tr>
                                                                <td>#</td>
                                                                <td>Name</td>
                                                                <td>Image</td>
                                                                <td>Information</td>
                                                                <td>Created At</td> <!-- Added Created At column -->

                                                                <td>Action</td>
                                                            </tr>
                                                            <?php
                                                            $i = 1;
                                                            // Modify the SQL query to select products associated with the current user's business profile
                                                            $query = "SELECT * FROM posting_module WHERE posted_by IN (SELECT id FROM business_profile WHERE owner = ?) ORDER BY id DESC";
                                                            $stmt = mysqli_prepare($conn, $query);
                                                            mysqli_stmt_bind_param($stmt, "i", $business_owner);
                                                            mysqli_stmt_execute($stmt);
                                                            $result = mysqli_stmt_get_result($stmt);
                                                            foreach ($result as $row) :
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $i++; ?></td>
                                                                    <td><?php echo $row["name"]; ?></td>
                                                                    <td><img src="img/<?php echo $row['image']; ?>" width="200" height="150" title=""></td>
                                                                    <td><?php echo $row["text"]; ?></td>    
                                                                    <td><?php echo $row["created_at"]; ?></td> <!-- Display Created At -->
                                                                    <!-- ... -->
                                                                    <td>
                                                                        <!-- CRUD Operations Form -->
                                                                        <form action="crud.php" method="post">
                                                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                                            <button type="submit" class="btn btn-success mb-2" name="read">Read</button>
                                                                            <button type="submit" class="btn btn-success mb-2" name="edit">Edit</button> <!-- Updated from "Update" to "Edit" -->
                                                                            <button class="btn btn-danger mb-2" type="submit" name="delete">Delete</button>
                                                                        </form>
                                                                    </td>
                                                                    <!-- ... -->
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </table>
                                                    </div>Post Advertisement

                                                </div>
                                            </div>
                                    </div>
                                    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab" tabindex="0">
                                        <!------------------------------------- Advertisement-Management Module  ---------------------------------->                        
                                        <div class="container-fluid" style="margin:auto;">
                                                <div class="container-fluid">
                                                    <div class="container-fluid">
                                                        <br>
                                                        <h1 style="font-family: Verdana, Geneva, Tahoma, sans-serif;font-size: 50px;color:black;font-weight: bold;">Advertisement Module</h1>
                                                        <br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row justify-content-center"> <!-- Center the content -->
                                                <div class="container-fluid"> <!-- Adjust the column width as needed -->
                                                    <div class="table-responsive">
                                                        <!-- Display Table -->  
                                                        <table class="table table-striped table-borderless overflow-auto">
                                                            <tr>
                                                                <td>#</td>
                                                                <td>Name</td>
                                                                <td>Image</td>
                                                                <td>Information</td>
                                                                <td>Created At</td> <!-- Added Created At column -->
                                                                <td>Action</td>
                                                            </tr>
                                                            <?php
                                                            $i = 1;
                                                            // Modify the SQL query to select products associated with the current user's business profile
                                                            $query = "SELECT * FROM business_advertisement WHERE posted_by IN (SELECT id FROM business_profile WHERE owner = ?) ORDER BY id DESC";
                                                            $stmt = mysqli_prepare($conn, $query);
                                                            mysqli_stmt_bind_param($stmt, "i", $business_owner);
                                                            mysqli_stmt_execute($stmt);
                                                            $result = mysqli_stmt_get_result($stmt);
                                                            foreach ($result as $row) :
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $i++; ?></td>
                                                                    <td><?php echo $row["name"]; ?></td>
                                                                    <td><img src="img/<?php echo $row['image']; ?>" width="200" height="150" title=""></td>
                                                                    <td class="overflow-x-auto" width="365"><?php echo $row["text"]; ?></td>    
                                                                    <td><?php echo $row["created_at"]; ?></td> <!-- Display Created At -->
                                                                    <!-- ... -->
                                                                    <td>
                                                                        <!-- CRUD Operations Form -->
                                                                        <form action="advertisement-crud.php" method="post">
                                                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                                            <button type="submit" class="btn btn-success mb-2" name="read">Read</button>
                                                                            <button type="submit" class="btn btn-success mb-2" name="edit">Edit</button> <!-- Updated from "Update" to "Edit" -->
                                                                            <button class="btn btn-danger mb-2" type="submit" name="delete">Delete</button>
                                                                        </form>
                                                                    </td>
                                                                    <!-- ... -->
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="tab-pane fade" id="nav-disabled" role="tabpanel" aria-labelledby="nav-disabled-tab" tabindex="0">...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>   


                <!------------------------------------- Update profile Module  ---------------------------------->
                <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                    <div class="container-fluid" style="margin:auto;">
                        <div class="">
                            <h1 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 50px; color:black; font-weight: bold;">
                                Update your profile
                            </h1>
                        </div>
                    </div>
                    <div class="col-12 d-flex align-items-center mt-5 border rounded-5 p-3 bg-white shadow box-area p-5">
                    <form class="row g-3" action="" method="post" enctype="multipart/form-data">

                        <!-- Business Name -->
                        <div class="form-floating col-md-6">
                            <input type="text" class="form-control" id="businessName" placeholder="Business Name" name="name_buss" value="<?php echo $business_name; ?>">
                            <label for="businessName" style="margin-left: 5px;">Business Name</label>
                        </div>

                        <!-- Bio -->
                        <div class="form-floating col-md-6">
                            <input type="text" class="form-control" id="bio" placeholder="Bio" name="bio" value="<?php echo $business_bio; ?>">
                            <label for="bio" style="margin-left: 5px;">Bio</label>
                        </div>
                        <!-- Contact Number -->
                        <div class="form-floating col-md-6">
                            <input type="text" class="form-control" id="contactNumber" placeholder="Contact Number" name="contact_number" value="<?php echo $business_contact_number; ?>">
                            <label for="contactNumber" style="margin-left: 5px;">Contact Number</label>
                        </div>

                        <!-- Email -->
                        <div class="form-floating col-md-6">
                            <input type="text" class="form-control" id="email" placeholder="Email" name="email" value="<?php echo $business_email; ?>">
                            <label for="email" style="margin-left: 5px;">Email</label>
                        </div>
                        <!-- Address -->
                        <div class="form-floating col-md-12">
                            <input type="text" class="form-control" id="address" placeholder="Address" name="address" value="<?php echo $business_address; ?>">
                            <label for="address" style="margin-left: 5px;">Address</label>
                        </div>

                        
                        <!-- Profile Picture -->
                        <div class="col-md-6" style="margin-top: 20px;">
                            <div class="image-container" style="width: 225px; height: 110px; border: 2px solid black; border-radius: 15px;">
                                <img id="preview2" src="#" alt="Preview Image" style="display:none;">
                            </div>
                        </div>
                        <div class="col-md-12" style="margin-top: 20px;">
                            <div class="form-floating">
                                <input class="form-control" style="width:300px;height:40px;" type="file" id="formFile2" name="image" accept=".jpg, .jpeg, .png" onchange="previewImage(event, 'preview2')">
                            </div>
                        </div>                            

                            <div class="col-12">
                                <button class="btn btn-lg  w-100 fs-6" type="submit" name="submit" style="background-color: #90EE90;">Upload</button>
                            </div>

                        </form>
                    </div>
                </div>

                <!------------------------------------- Advertisement Module  ---------------------------------->
                <div class="tab-pane fade" id="v-pills-advertisement" role="tabpanel" aria-labelledby="v-pills-advertisement-tab">
                    <div class="container-fluid" style="margin:auto;">
                        <div class="container-fluid">
                            <h1 style="font-family: Verdana, Geneva, Tahoma, sans-serif;font-size: 50px;color:black;font-weight: bold;">Post Advertisement</h1>
                        </div>
                    </div>
                    <div class="col-12 d-flex align-items-center mt-5 border rounded-5 p-3 bg-white shadow box-area p-5">
                        <form class="row g-3 w-100" action="" method="post" enctype="multipart/form-data">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="name_advertisement" placeholder="Advertisement Name" name="name_advertisement" value="">
                                <label for="name_advertisement" style="margin-left: 5px;">Advertisement Name</label>
                            </div>
                            <div class="form-floating">
                                <textarea class="form-control" id="text_advertisement" placeholder="Advertisement Text" name="text_advertisement"></textarea>
                                <label for="text_advertisement" style="margin-left: 5px;">Advertisement Info</label>
                            </div>
                            <div class="col-12">
                                <label for="image_advertisement" class="form-label" style="margin-left: 5px;">Advertisement Image</label>
                                <input class="form-control" type="file" id="image_advertisement" name="image_advertisement" accept=".jpg, .jpeg, .png" value="">
                            </div>
                            <div class="col-12">
                                <button class="btn btn-lg  w-100 fs-6" type="submit" name="upload_advertisement" style="background-color: #90EE90;">Upload</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!------------------------------------- Upload Farm Photos Module  ---------------------------------->
                <div class="tab-pane fade" id="v-pills-photos" role="tabpanel" aria-labelledby="v-pills-photos-tab">
                    <div class="container-fluid" style="margin:auto;">
                        <div class="container-fluid">
                            <h1 style="font-family: Verdana, Geneva, Tahoma, sans-serif;font-size: 50px;color:black;font-weight: bold;">Farm Photos</h1>
                        </div>
                    </div>
                    <div class="col-12 d-flex align-items-center mt-5 border rounded-5 p-3 bg-white shadow box-area p-5">
                        <form class="row g-3 w-100" action="" method="post" enctype="multipart/form-data">
                            <div class="col-12">
                                <label for="image_farm" class="form-label" style="margin-left: 5px;">Farm Photos</label>
                                <input class="form-control" type="file" id="image_farm" name="image_farm" accept=".jpg, .jpeg, .png" value="">
                            </div>
                            <div class="col-12">
                                <button class="btn btn-lg  w-100 fs-6" type="submit" name="upload_photos" style="background-color: #90EE90;">Upload</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!------------------------------------- Message Module  ---------------------------------->
                <div class="tab-pane fade" id="v-pills-message" role="tabpanel" aria-labelledby="v-pills-message-tab">
                    <?php if (!empty($msgRows)): ?>
                        <div class="row d-flex align-items-start justify-content-start ">
                            <h1>Messages</h1>
                            <?php foreach ($msgRows as $index => $msgRow): ?> <!-- Add $index variable -->
                                <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                                    <div class="card text-center">
                                        <div class="card-header">
                                            <?php echo $msgRow['sent_by'] ?>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text"><?php echo $msgRow['message'] ?>.</p>
                                            <button type="button"  class="btn btn-lg btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $index ?>">
                                                <small>Message Back!</small>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#90EE90" class="bi bi-chat-fill" viewBox="0 0 16 16">
                                                    <path d="M8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6-.097 1.016-.417 2.13-.771 2.966-.079.186.074.394.273.362 2.256-.37 3.597-.938 4.18-1.234A9 9 0 0 0 8 15"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="card-footer text-body-secondary">
                                            <?php echo $msgRow['created_at'] ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- modal -->
                                <div class="modal fade" id="exampleModal<?php echo $index ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">New message</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form method="post" action="send.php" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="staticEmail" class="col-form-label">Recipient:</label>
                                                    <input type="text" name="to_sent" readonly class="form-control-plaintext" id="staticEmail" value="<?php echo $msgRow['sent_by'] ?>">
                                                </div>

                                                <?php
                                                $res = mysqli_query($conn, "SELECT * FROM business_profile WHERE owner = $business_owner");
                                                
                                                while($row = mysqli_fetch_assoc($res)){
                                                ?>
                                                <div class="mb-3">
                                                    <label for="message-text" class="col-form-label">Your email:</label>
                                                    <input type="text" name="my_email" readonly class="form-control-plaintext" id="staticEmail" value="<?php echo $row['email'] ?>">
                                                </div>
                                                <?php }?>
                                                <div class="row">
                                                    <small class="text-red mb-2" style=" color:red"></small>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="message-text" class="col-form-label">Message:</label>
                                                    <textarea name="message" class="form-control" id="message-text"></textarea>
                                                </div>
                                                <div class="row">
                                                    <small class="text-red mb-2 " style=" color:red"></small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button name="sendMSG" type="submit" class="btn btn-primary">Send message</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p>No messages found.</p>
                    <?php endif; ?>

                    
                    
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function previewImage(event, previewId) {
    var reader = new FileReader();
    reader.onload = function() {
        var imgElement = document.getElementById(previewId);
        if (imgElement) {
            imgElement.src = reader.result;
            imgElement.style.display = "block";
        }
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>
                    </body>
<?php
    include('../navbars/footer.php')
?>
