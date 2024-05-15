
<?php
include('../config/connectDb.php');
include('../navbars/profilepage-nav.php');


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


    // Check if the form is submitted
    if(isset($_POST['submit'])) {
        
        // Get form data
        $newBussName = $_POST['name_buss'];
        $newBio = $_POST['bio'];
        $newAddress = $_POST['address'];
        $newEmail = $_POST['email'];
        $newContact_number = $_POST['contact_number'];
        $business_owner = $_SESSION['ownerID']; // Assuming you have a session variable for ownerID
        
        // Check if an image file is uploaded
        if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            // Move uploaded image to target directory
            $folder = 'img/';
            $file_name = $_FILES['image']['name'];
            $tempName = $_FILES['image']['tmp_name'];
            if(move_uploaded_file($tempName, $folder . $file_name)){
                // Update image field in the database
                $sql_image = "UPDATE business_profile SET image = ? WHERE owner = ?";
                $stmt_image = mysqli_prepare($conn, $sql_image);
                mysqli_stmt_bind_param($stmt_image, "si", $file_name, $business_owner);
                mysqli_stmt_execute($stmt_image);
            }
        }
        
        // Prepare SQL statement to update other fields in the database
        $sql = "UPDATE business_profile 
                SET name = ?, text = ?, address = ?, email = ?, contact_number = ?
                WHERE owner = ?";
        $stmt = mysqli_prepare($conn, $sql);

        // Check if the statement is prepared successfully
        if($stmt) {
            // Bind parameters and execute the statement
            mysqli_stmt_bind_param($stmt, "sssssi", $newBussName, $newBio, $newAddress, $newEmail, $newContact_number, $business_owner);
            mysqli_stmt_execute($stmt);

            // Check if the query executed successfully
            if(mysqli_stmt_affected_rows($stmt) > 0 || isset($stmt_image) && mysqli_stmt_affected_rows($stmt_image) > 0) {
                echo "
                <script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'Updated successfully!',
                        icon: 'success'
                    }).then(function() {
                        window.location = 'profile-page.php?active=profile';
                    });
                </script>";
            
                exit();
            } else {
                echo "
                <script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Error updating profile!',
                        icon: 'error'
                    }).then(function() {
                        window.location = 'profile-page.php?active=profile';
                    });
                </script>";
        
                exit();
            }
        } else {
            echo 'Error preparing statement: ' . mysqli_error($conn);
        }
    }


// ---------------------------------- add post ----------------------------------------
if (isset($_POST["upload_product"])) {
  $name_product = mysqli_real_escape_string($conn, $_POST["name_product"]);
  $text_product = mysqli_real_escape_string($conn, $_POST["text_product"]);
  

    if ($_FILES["image_product"]["error"] === 4) {
        echo "
                <script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Image does not exist!',
                        icon: 'error'
                    }).then(function() {
                        window.location = 'profile-page.php?active=upload';
                    });
                </script>";
        
                exit();
    } else {
        $fileName = $_FILES["image_product"]["name"];
        $fileSize = $_FILES["image_product"]["size"];
        $tmpName = $_FILES["image_product"]["tmp_name"];

        $validImageExtension = ['jpg', 'jpeg', 'png'];
        $imageExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        if (!in_array($imageExtension, $validImageExtension)) {
            echo "
                <script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Invalid image extension!',
                        icon: 'error'
                    }).then(function() {
                        window.location = 'profile-page.php?active=upload';
                    });
                </script>";

                exit();
        } elseif ($fileSize > 10000000) {
            echo "
                <script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Image size is too large!',
                        icon: 'error'
                    }).then(function() {
                        window.location = 'profile-page.php?active=upload';
                    });
                </script>";

                exit();

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

            echo "
                <script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'Image uploaded successfully!',
                        icon: 'success'
                    }).then(function() {
                        window.location = 'profile-page.php?active=upload';
                    });
                </script>";
            
                exit();

        }
    }
}


// ---------------------------------- advertisement module ----------------------------------------
if (isset($_POST["upload_advertisement"])) {
    $name_advertisement = mysqli_real_escape_string($conn, $_POST["name_advertisement"]);
    $text_advertisement = mysqli_real_escape_string($conn, $_POST["text_advertisement"]);
    
  
      if ($_FILES["image_advertisement"]["error"] === 4) {
        echo "
        <script>
            Swal.fire({
                title: 'Error!',
                text: 'Image does not exist!',
                icon: 'error'
            }).then(function() {
                window.location = 'profile-page.php?active=advertisement';
            });
        </script>";

        exit();
      } else {
          $fileName = $_FILES["image_advertisement"]["name"];
          $fileSize = $_FILES["image_advertisement"]["size"];
          $tmpName = $_FILES["image_advertisement"]["tmp_name"];
  
          $validImageExtension = ['jpg', 'jpeg', 'png'];
          $imageExtension = pathinfo($fileName, PATHINFO_EXTENSION);
  
          if (!in_array($imageExtension, $validImageExtension)) {
            echo "
            <script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Invalid image extension!',
                    icon: 'error'
                }).then(function() {
                    window.location = 'profile-page.php?active=advertisement';
                });
            </script>";

            exit();

          } elseif ($fileSize > 10000000) {
            echo "
            <script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Image size is too large!',
                    icon: 'error'
                }).then(function() {
                    window.location = 'profile-page.php?active=advertisement';
                });
            </script>";

            exit();

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
  
              echo "
              <script>
                  Swal.fire({
                      title: 'Success!',
                      text: 'Image uploaded successfully!',
                      icon: 'success'
                  }).then(function() {
                      window.location = 'profile-page.php?active=advertisement';
                  });
              </script>";
          
              exit();
  
          }
      }
  }

  // ---------------------------------- Farm Photos module ----------------------------------------
    if (isset($_POST["upload_photos"])) {
        
      if ($_FILES["image_farm"]["error"] === 4) {
          echo "
                <script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Image does not exist!',
                        icon: 'error'
                    }).then(function() {
                        window.location = 'profile-page.php?active=photos';
                    });
                </script>";
        
                exit();
      } else {
          $fileName = $_FILES["image_farm"]["name"];
          $fileSize = $_FILES["image_farm"]["size"];
          $tmpName = $_FILES["image_farm"]["tmp_name"];
  
          $validImageExtension = ['jpg', 'jpeg', 'png'];
          $imageExtension = pathinfo($fileName, PATHINFO_EXTENSION);
  
          if (!in_array($imageExtension, $validImageExtension)) {
            echo "
            <script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Invalid Image Extenstion!',
                    icon: 'error'
                }).then(function() {
                    window.location = 'profile-page.php?active=photos';
                });
            </script>";
    
            exit();
          } elseif ($fileSize > 10000000) {
            echo "
            <script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Image size is too large!',
                    icon: 'error'
                }).then(function() {
                    window.location = 'profile-page.php?active=photos';
                });
            </script>";
    
            exit();
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
  
              echo "
              <script>
                  Swal.fire({
                      title: 'Success!',
                      text: 'Image uploaded successfully!',
                      icon: 'success'
                  }).then(function() {
                      window.location = 'profile-page.php?active=photos';
                  });
              </script>";
          
              exit();
  
          }
      }
  }

  if (isset($_POST['photos_delete'])) {
    // Delete operation
    $id = $_POST['id'];
    $query = "DELETE FROM business_photos WHERE id = $id";
    mysqli_query($conn, $query);

    echo "
              <script>
                  Swal.fire({
                      title: 'Success!',
                      text: 'Image delete successfully!',
                      icon: 'success'
                  }).then(function() {
                      window.location = 'profile-page.php?active=managePosts';
                  });
              </script>";
          
              exit();
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



        // Retrieve active page from query parameter
        $activePage = isset($_GET['active']) ? $_GET['active'] : '';

        // Define function to add 'active' class to the button
        function isActive($page, $activePage) {
                return $page === $activePage ? 'active' : '';
        }

        // Retrieve active page from query parameter
        $activePage = isset($_GET['active']) ? $_GET['active'] : '';

        // Define function to add 'show active' class to the button
        function isShowActive($page, $activePage) {
                return $page === $activePage ? 'show active' : '';
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
        margin: 2px;
    }
    .nav-link:hover {
        border: none !important;
        color: black;
        background-color: #90EE90;
        transition: ease-in 0.50s;
        border-radius: 5px;
    }
    
    .nav-link.active {
        border: none !important;
        background-color: #90EE90;
        color: white;
        padding: 15px 20px;
        border-radius: 5px;
    }
    .nav-link:focus {
        outline: none !important;
        box-shadow: none !important;
    }
</style>
</head>
<link rel="stylesheet" href="../CSS/profile-setup.css">
<link rel="stylesheet" href="../CSS/profilepage.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<body style="background-color: #f6f8fc;">
<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-lg-3 col-12 text-center" style="padding: 20px;">
            <div>
                <?php
                  $res = mysqli_query($conn, "SELECT * FROM business_profile WHERE owner = $business_owner");
                  
                  while($row = mysqli_fetch_assoc($res)){
                ?>
                <img class="img-fluid img-thumbnail rounded-circle object-fit-cover" style="height: 300px; width:300px;" src="img/<?php echo $row['image']; ?>">
                <?php } ?>
            </div>
            <h3 class="mt-3" style="color:black; font-size:40px"><i class="fas fa-building" style="margin-right: 15px;"></i><?php echo $business_name?></h3>
            
            <div class="container-fluid" style="width: 300px;">
                <div class="nav flex-column nav-pills text-start align-items-start" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link d-flex justify-content-start align-items-center <?php echo isActive('home', $activePage); ?>" type="button" onclick="window.location.href='../index.php'"><i class="fas fa-home" style="margin-right: 8px;"></i>Home</button>   
                    <button class="nav-link d-flex justify-content-start align-items-center <?php echo isActive('upload', $activePage); ?>" id="v-pills-upload-tab" data-bs-toggle="pill" data-bs-target="#v-pills-upload" type="button" role="tab" aria-controls="v-pills-upload" aria-selected="false"><i class="fas fa-upload" style="margin-right: 8px;"></i>Upload a new Product</button>
                    <button class="nav-link d-flex justify-content-start align-items-center <?php echo isActive('managePosts', $activePage); ?>" id="v-pills-manageProduct-tab" data-bs-toggle="pill" data-bs-target="#v-pills-manageProduct" type="button" role="tab" aria-controls="v-pills-manageProduct" aria-selected="false"><i class="fas fa-tasks" style="margin-right: 8px;"></i>Manage Post</button>
                    <button class="nav-link d-flex justify-content-start align-items-center <?php echo isActive('profile', $activePage); ?>" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false"><i class="fas fa-user" style="margin-right: 8px;"></i>Update Profile</button>
                    <button class="nav-link d-flex justify-content-start align-items-center <?php echo isActive('advertisement', $activePage); ?>" id="v-pills-advertisement-tab" data-bs-toggle="pill" data-bs-target="#v-pills-advertisement" type="button" role="tab" aria-controls="v-pills-advertisement" aria-selected="false"><i class="fas fa-ad" style="margin-right: 8px;"></i>Upload Advertisement</button>
                    <button class="nav-link d-flex justify-content-start align-items-center <?php echo isActive('photos', $activePage); ?>" id="v-pills-photos-tab" data-bs-toggle="pill" data-bs-target="#v-pills-photos" type="button" role="tab" aria-controls="v-pills-photos" aria-selected="false"><i class="fas fa-images" style="margin-right: 8px;"></i>Upload Farm Photos</button>
                    <button class="nav-link d-flex justify-content-start align-items-center <?php echo isActive('messages', $activePage); ?>" id="v-pills-message-tab" data-bs-toggle="pill" data-bs-target="#v-pills-message" type="button" role="tab" aria-controls="v-pills-message" aria-selected="false"><i class="fas fa-envelope" style="margin-right: 8px;"></i>Messages
                        <span class="badge rounded-pill bg-primary">
                            <?php echo $msgCount ?>
                        </span>
                    </button>
                    <button class="nav-link d-flex justify-content-start align-items-center <?php echo isActive('subDetails', $activePage); ?>" id="v-pills-accDeets-tab" data-bs-toggle="pill" data-bs-target="#v-pills-accDeets" type="button" role="tab" aria-controls="v-pills-accDeets" aria-selected="false"><i class="fa-solid fa-file-invoice-dollar" style="margin-right: 8px"></i>Subscription Details</button>
                    <button class="nav-link d-flex justify-content-start align-items-center <?php echo isActive('logout', $activePage); ?>" type="button" onclick="window.location.href='../AccPages/logout.php'"><i class="fas fa-sign-out-alt" style="margin-right: 8px;"></i>Log Out</button>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-12 d-flex align-items-top border 2px solid black bg-white" style="padding:20px;border-radius:50px;">
            <div class="tab-content container-fluid" id="v-pills-tabContent">
                <!------------------------------------- Upload-Product Module  ---------------------------------->
                <div class="tab-pane fade <?php echo isShowActive('upload', $activePage); ?>" id="v-pills-upload" role="tabpanel" aria-labelledby="v-pills-upload-tab">
                    <div class="container-fluid" style="margin:auto;">
                        <div class="container-fluid">
                            <div class="">
                                <h1 style="font-family: Verdana, Geneva, Tahoma, sans-serif;font-size: 50px;color:black;font-weight: bold;">Upload a new Product</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 d-flex align-items-center mt-5border rounded-5 p-3 bg-white box-area p-5">
                        <form class="row w-100 g-3" action="" method="post" enctype="multipart/form-data">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="floatingInput name_product" placeholder="" name="name_product" value="">
                                <label for="floatingInput" style="margin-left: 5px;">Product Name</label>
                            </div>
                            <div class="col-12">
                                <label for="formFile" class="form-label" style="margin-left: 5px;">Upload a picture</label>
                                <input class="form-control" type="file" id="formFile" name="image_product">
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlTextarea1" class="form-label">Write something about the Product.</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="text_product"></textarea>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-lg fs-6 w-100" type="upload_product" name="upload_product" style="background-color: #90EE90;">Upload</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!------------------------------------- Posting-Management Module  ---------------------------------->
                <div class="tab-pane fade <?php echo isShowActive('managePosts', $activePage); ?> container-fluid" id="v-pills-manageProduct" role="tabpanel" aria-labelledby="v-pills-manageProduct-tab"> 
                    <div class="container-fluid bg-white">
                        <div class="row">
                            <div class="col-md-12">
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
                                                    <h1 class="fw-bold">Farm Photos Module</h1>
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
                                                        $query = "SELECT * FROM business_photos WHERE posted_by IN (SELECT id FROM business_profile WHERE owner = ?) ORDER BY id DESC";                                                            $stmt = mysqli_prepare($conn, $query);
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
                                                    <h1 class="fw-bold">Post Module</h1>
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
                                                                <td><img src="img/<?php echo $row['image']; ?>" width="200" height="150" title=""></td>                                                                    <td><?php echo $row["text"]; ?></td>    
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
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab" tabindex="0">
                                        <!------------------------------------- Advertisement-Management Module  ---------------------------------->                        
                                        <div class="container-fluid" style="margin:auto;">
                                            <div class="container-fluid">
                                                <div class="container-fluid">
                                                    <br>
                                                    <h1 class="fw-bold">Advertisement Module</h1>
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
                <div class="tab-pane fade <?php echo isShowActive('profile', $activePage); ?>" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                    <div class="container-fluid" style="margin:auto;">
                        <div class="">
                            <h1 style="font-family: Verdana, Geneva, Tahoma, sans-serif;font-size: 50px;color:black;font-weight: bold;">Update your profile</h1>
                        </div>
                    </div>
                    <div class=" col-12 d-flex align-items-center mt-3 border rounded-5 p-3 bg-white box-area p-5">
                        <form class="row g-3" action="" method="post" enctype="multipart/form-data">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com" name="name_buss" value="<?php echo $business_name; ?>">
                                <label for="floatingInput" style="margin-left: 5px;">Business Name</label>
                            </div>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="floatingInput" placeholder="bio" name="bio" value="<?php echo $business_bio; ?>">
                                <label for="floatingInput" style="margin-left: 5px;">Bio</label>
                            </div>
                            <div class="col-12">
                                <label for="formFile" class="form-label" style="margin-left: 5px;">Profile Picture</label>
                                <input class="form-control" type="file" id="formFile" name="image" accept=".jpg, .jpeg, .png" value="<?php echo $business_pfp; ?>">
                            </div>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="floatingInput" placeholder="address" name="address" value="<?php echo $business_address; ?>">
                                <label for="floatingInput" style="margin-left: 5px;">Address</label>
                            </div>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="floatingInput" placeholder="email" name="email" value="<?php echo $business_email; ?>">
                                <label for="floatingInput" style="margin-left: 5px;">Email</label>
                            </div>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="floatingInput" placeholder="contact_number" name="contact_number" value="<?php echo $business_contact_number; ?>">
                                <label for="floatingInput" style="margin-left: 5px;">Contact Number</label>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-lg  w-100 fs-6" type="submit" name="submit" style="background-color: #90EE90;">Upload</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!------------------------------------- Advertisement Module  ---------------------------------->
                <div class="tab-pane fade <?php echo isShowActive('advertisement', $activePage); ?>" id="v-pills-advertisement" role="tabpanel" aria-labelledby="v-pills-advertisement-tab">
                    <div class="container-fluid" style="margin:auto;">
                        <div class="container-fluid">
                            <h1 class="fw-bold">Post Advertisement</h1>
                        </div>
                    </div>
                    <div class="col-12 d-flex align-items-center mt-3 border rounded-5 p-3 bg-white box-area p-5">
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
                <div class="tab-pane fade <?php echo isShowActive('photos', $activePage); ?>" id="v-pills-photos" role="tabpanel" aria-labelledby="v-pills-photos-tab">
                    <div class="container-fluid" style="margin:auto;">
                        <div class="container-fluid">
                            <h1 style="font-family: Verdana, Geneva, Tahoma, sans-serif;font-size: 50px;color:black;font-weight: bold;">Farm Photos</h1>
                        </div>
                    </div>
                    <div class="col-12 d-flex align-items-center mt-3 border rounded-5 p-3 bg-white box-area p-5">
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
                <div class="tab-pane fade <?php echo isShowActive('messages', $activePage); ?>" id="v-pills-message" role="tabpanel" aria-labelledby="v-pills-message-tab">
                    <ul class="nav nav-underline">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="#">Inbox</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Sent</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Archive</a>
                        </li>
                    </ul>                                  
                    <?php if (!empty($msgRows)): ?>
                        <div class="row d-block align-items-start justify-content-start ">
                            <h1>Messages</h1>
                            <?php foreach ($msgRows as $index => $msgRow): ?> <!-- Add $index variable -->
                                <div class="container">
                                    <div class="row g-0 w-100">
                                        <div class="card mb-3">
                                        <div class="card-header w-100">
                                            <p class="me-2 w-50"><?php echo $msgRow['sent_by'] ?></p>
                                            <span class="float-end"><?php echo $msgRow['created_at'] ?></span>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text"><?php echo $msgRow['message'] ?>.</p>
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
                                                    <input type="hidden" name="my_number" value="<?php echo $row['contact_number'] ?>">
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
                <!------------------------------------- AccountDetails Module  ---------------------------------->
                <div class="tab-pane fade <?php echo isShowActive('subDetails', $activePage); ?>" id="v-pills-accDeets" role="tabpanel" aria-labelledby="v-pills-accDeets-tab">
                    <div class="container mt-5">
                        <?php
                            include('./account-info.php');
                        ?>    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                    </body>
<?php
    include('../navbars/footer.php')
?>
