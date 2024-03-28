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
if (isset($_POST["tty"])) {
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
            } else {
                echo 'No messages';
               
                exit(); // Stop further execution if no data found
            }

?>


<link rel="stylesheet" href="../CSS/profile-setup.css">
<link rel="stylesheet" href="../CSS/profilepage.css">
<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-lg-3 col-12 text-center">
            <div>
                <?php
                  $res = mysqli_query($conn, "SELECT * FROM business_profile WHERE owner = $business_owner");
                  
                  while($row = mysqli_fetch_assoc($res)){
                ?>
                <img class="img-fluid img-thumbnail rounded-circle object-fit-cover" style="height: 300px; width:300px;" src="img/<?php echo $row['image']; ?>">
                <?php } ?>
            </div>
            <h3 class="mt-3"><?php echo $business_name?></h3>
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Bio</h5>
                    <p class="card-text"><?php
                        echo $business_bio
                    ?></p>
                    <h5 class="card-title">Address</h5>
                    <p class="card-text"><?php
                        echo $business_address
                    ?></p>
                    <h5 class="card-title">Email</h5>
                    <p class="card-text"><?php
                        echo $business_email
                    ?></p>
                    <h5 class="card-title">Contact Number</h5>
                    <p class="card-text"><?php
                        echo $business_contact_number
                    ?></p>
                </div>
            </div>
            <div class="container-fluid">
                <div class="nav flex-column nav-pills text-center" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link" type="button"><a href="../index.php" class="logoutBTN btn">Home</a></button>
                    <button class="nav-link"  id="v-pills-upload-tab" data-bs-toggle="pill" data-bs-target="#v-pills-upload" type="button" role="tab" aria-controls="v-pills-upload" aria-selected="false">Upload a new Product</button>
                    <button class="nav-link active" id="v-pills-manageProduct-tab" data-bs-toggle="pill" data-bs-target="#v-pills-manageProduct" type="button" role="tab" aria-controls="v-pills-manageProduct" aria-selected="false">Manage Post</button>
                    <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">Update Profile</button>
                    <button class="nav-link" id="v-pills-advertisement-tab" data-bs-toggle="pill" data-bs-target="#v-pills-advertisement" type="button" role="tab" aria-controls="v-pills-advertisement" aria-selected="false">Upload Advertisement</button>
                    <button class="nav-link" id="v-pills-message-tab" data-bs-toggle="pill" data-bs-target="#v-pills-message" type="button" role="tab" aria-controls="v-pills-message" aria-selected="false">
                        Message
                        <span class="badge rounded-pill bg-primary">
                            <?php echo $msgCount ?>
                            
                        </span>
                    </button>
                    <button class="nav-link" type="button"><a href="../AccPages/logout.php" class="logoutBTN btn">Log Out</a></button>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-12 d-flex justify-content-center align-items-center">
            <div class="tab-content container-fluid" id="v-pills-tabContent">

                <!------------------------------------- Upload-Product Module  ---------------------------------->
                <div class="tab-pane fade" id="v-pills-upload" role="tabpanel" aria-labelledby="v-pills-upload-tab">
                    <div class="container-fluid" style="margin:auto;">
                        <div class="container-fluid">
                            <div class="">
                                <h1>Upload a new Product</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 d-flex align-items-center mt-5border rounded-5 p-3 bg-white shadow box-area p-5">
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
                <div class="tab-pane fade show active" id="v-pills-manageProduct" role="tabpanel" aria-labelledby="v-pills-manageProduct-tab">
                    
                    <!------------------------------------- Product-Management Module  ---------------------------------->
                    <div class="container-fluid" style="margin:auto;">
                        <div class="container-fluid">
                            <div class="container-fluid">
                                <h1>Post Module</h1>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center"> <!-- Center the content -->
                        <div class="col-md-10"> <!-- Adjust the column width as needed -->
                            <div class="table-responsive">
                                <!-- Display Table -->  
                                <table class="table table-striped table-borderless">
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
                                            <td><img src="img/<?php echo $row['image']; ?>" width="200" title=""></td>
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
                            </div>
                        </div>
                    </div>

                    <!------------------------------------- Advertisement-Management Module  ---------------------------------->                        
                    <div class="container-fluid" style="margin:auto;">
                        <div class="container-fluid">
                            <div class="container-fluid">
                                <h1>Advertisement Module</h1>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center"> <!-- Center the content -->
                        <div class="col-md-10"> <!-- Adjust the column width as needed -->
                            <div class="table-responsive">
                                <!-- Display Table -->  
                                <table class="table table-striped table-borderless">
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
                                            <td><img src="img/<?php echo $row['image']; ?>" width="200" title=""></td>
                                            <td><?php echo $row["text"]; ?></td>    
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


                <!------------------------------------- Update-profile Module  ---------------------------------->
                <div class="tab-pane fade " id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                    <div class="container-fluid" style="margin:auto;">
                        <div class="">
                            <h1>Update your profile</h1>
                        </div>
                    </div>
                    <div class=" col-12 d-flex align-items-center mt-5 border rounded-5 p-3 bg-white shadow box-area p-5">
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
                                <input class="form-control" type="file" id="formFile" name="image" accept=".jpg, .jpeg, .png" value="<?php echo $fromBusinessProfile; ?>">
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
                <div class="tab-pane fade" id="v-pills-advertisement" role="tabpanel" aria-labelledby="v-pills-advertisement-tab">
                    <div class="container-fluid" style="margin:auto;">
                        <div class="container-fluid">
                            <h1>Post Advertisement</h1>
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
                
                <!------------------------------------- Message Module  ---------------------------------->
                <div class="tab-pane fade" id="v-pills-message" role="tabpanel" aria-labelledby="v-pills-message-tab">
                    <div class="row d-flex align-items-start justify-content-center">
                        <h1>Messages</h1>
                        <?php foreach ($msgRows as $msgRow ){ ?>
                            <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                                <div class="card text-center ">
                                    <div class="card-header">
                                        <?php echo $msgRow['sent_by']?>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text"><?php echo $msgRow['message']?>.</p>
                                        <a href="#" class="btn btn-primary">Go somewhere</a>
                                    </div>
                                    <div class="card-footer text-body-secondary">
                                        <?php echo $msgRow['created_at']?>
                                    </div>
                                </div>
                            </div>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    include('../navbars/footer.php')
?>
