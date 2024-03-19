<?php
include('../config/connectDb.php');
include('../navbars/profilepage-nav.php');

session_start();
if(isset($_SESSION['ownerID'])){
    $business_owner = $_SESSION['ownerID']; 
}else{
    echo 'no owner ';
}

    $getSql = "SELECT name, text, image FROM business_profile WHERE owner = ?";
    $stmt = mysqli_prepare($conn, $getSql);
    mysqli_stmt_bind_param($stmt, "i", $business_owner);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($fromBusinessProfile = mysqli_fetch_assoc($result)) {
        $business_name = $fromBusinessProfile['name'];
        $business_bio = $fromBusinessProfile['text'];
        $business_pfp = $fromBusinessProfile['image'];
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

    // Move uploaded image to target directory
    if(move_uploaded_file($tempName, $folder)){
        // Prepare SQL statement to update the database
        $sql = "UPDATE business_profile 
        SET name = ?, text = ?, image = ? 
        WHERE owner = ?";
        $stmt = mysqli_prepare($conn, $sql);

        // Check if the statement is prepared successfully
        if($stmt) {
            // Bind parameters and execute the statement
            mysqli_stmt_bind_param($stmt, "sssi", $newBussName, $newBio, $file_name, $business_owner);
            mysqli_stmt_execute($stmt);

            // Check if the query executed successfully
            if(mysqli_stmt_affected_rows($stmt) > 0) {
                echo "<script> 
                        alert('Updated successfully'); 
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
//-----------------------------------------------------------------------------------------



?>


<link rel="stylesheet" href="../CSS/profile-setup.css">
<link rel="stylesheet" href="../CSS/profilepage.css">
<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-3 text-center">
            <div>
                <?php
                  $res = mysqli_query($conn, "SELECT * FROM business_profile WHERE owner = $business_owner");
                  
                  while($row = mysqli_fetch_assoc($res)){
                ?>
                <img style="width: 300px; height:300px;" class="img-fluid img-thumbnail rounded-circle object-fit-contain" src="img/<?php echo $row['image']; ?>">
                <?php } ?>
            </div>
            <h3 class="mt-3"><?php echo $business_name?></h3>
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Bio</h5>
                    <p class="card-text"><?php
                        echo $business_bio
                    ?></p>
                </div>
            </div>
            <div class="text-center">
                <div class="nav flex-column nav-pills me-3 mt-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link" type="button"><a href="../index.php" class="logoutBTN btn">Home</a></button>
                    <button class="nav-link"  id="v-pills-upload-tab" data-bs-toggle="pill" data-bs-target="#v-pills-upload" type="button" role="tab" aria-controls="v-pills-upload" aria-selected="true">Upload a new Product</button>
                    <button class="nav-link active" id="v-pills-manageProduct-tab" data-bs-toggle="pill" data-bs-target="#v-pills-manageProduct" type="button" role="tab" aria-controls="v-pills-manageProduct" aria-selected="false">Manage Post</button>
                    <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">Update Profile</button>
                    <button class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false">Messages</button>
                    <button class="nav-link" type="button"><a href="../AccPages/logout.php" class="logoutBTN btn">Log Out</a></button>
                </div>
            </div>
        </div>
        <div class="col-7 container-sm">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade" id="v-pills-upload" role="tabpanel" aria-labelledby="v-pills-upload-tab">
                    <div class="container-fluid w-50" style="margin-top: 90px;">
                        <div class="col-md-6 container-fluid text-center">
                            <div class="container-fluid">
                                <h1>Upload a new Product</h1>
                            </div>
                        </div>
                    </div>
                    <div class="container-sm d-flex align-items-center mt-5 w-50 border rounded-5 p-3 bg-white shadow box-area p-5">
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
<!------------------------------------------------ MANAGE PRODUCT POST ------------------------------------------------------->
                <div class="tab-pane fade show active" id="v-pills-manageProduct" role="tabpanel" aria-labelledby="v-pills-manageProduct-tab">
                    <div class="text-center">
                        <div class="container">
                            <h2>Manage Product Post</h2>

                            <!-- Display Table -->
                            <table border="1" cellspacing="0" cellpadding="10">
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
                                        <button type="submit" name="read">Read</button>
                                        <button type="submit" name="edit">Edit</button> <!-- Updated from "Update" to "Edit" -->
                                        <button class="delete-btn" type="submit" name="delete">Delete</button>
                                    </form>
                                    </td>
                                    <!-- ... -->
                                </tr>

                                <?php endforeach; ?>
                            </table>
                            <br>
                        </div>
                    </div>  
                </div>

                <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                    <div class="container-fluid w-50" style="margin-top: 90px;">
                        <div class="col-md-6 container-fluid">
                            <h1>Set Up you profile</h1>
                        </div>
                    </div>
                    <div class="container-sm d-flex align-items-center w-50 mt-5 border rounded-5 p-3 bg-white shadow box-area p-5">
                        <form class="row g-3" action="" method="post" enctype="multipart/form-data">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com" name="name_buss" value="">
                                <label for="floatingInput" style="margin-left: 5px;">Business Name</label>
                            </div>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="floatingInput" placeholder="bio" name="bio">
                                <label for="floatingInput" style="margin-left: 5px;">Bio</label>
                            </div>
                            <div class="col-12">
                                <label for="formFile" class="form-label" style="margin-left: 5px;">Profile Picture</label>
                                <input class="form-control" type="file" id="formFile" name="image" accept=".jpg, .jpeg, .png" value="">
                            </div>
                            <div class="col-12">
                                <button class="btn btn-lg  w-100 fs-6" type="submit" name="submit" style="background-color: #90EE90;">Upload</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">Messages</div>
            </div>
        </div>
    </div>
</div>
