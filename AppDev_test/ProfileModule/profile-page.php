<?php
include('../config/connectDb.php');
include('../navbars/profilepage-nav.php');

if(isset($_POST['submit']) && isset($_POST['name_buss']) && isset($_POST['bio']) && isset($_FILES['image'])) {
    // Fetch ownerID from session or wherever you're storing it
    $ownerID = $_SESSION['ownerID'];

    $target = "./img/" . basename($_FILES['image']['name']);
    $newBussName = $_POST['name_buss'];
    $newBio = $_POST['bio'];
    $image = $_FILES['image']['name'];

    // Move uploaded image to target directory
    if(move_uploaded_file($_FILES['image']['tmp_name'], $target)){
        // Prepare SQL statement to update the database
        $sql = "INSERT INTO business_profile SET owner = ?, name = ?, text = ?, image = ?";
        $stmt = mysqli_prepare($conn, $sql);

        // Check if the statement is prepared successfully
        if($stmt) {
            // Bind parameters and execute the statement
            mysqli_stmt_bind_param($stmt, "isss", $ownerID, $newBussName, $newBio, $target);
            mysqli_stmt_execute($stmt);

            // Check if the query executed successfully
            if(mysqli_stmt_affected_rows($stmt) > 0) {
                echo 'Success';
            } else {
                echo 'Error updating profile.';
            }
        } else {
            echo 'Error preparing statement: ' . mysqli_error($conn);
        }
    } else {
        echo 'Error uploading image.';
    }
}

// Close the connection after all database operations are done
mysqli_close($conn);
?>


<link rel="stylesheet" href="../CSS/profile-setup.css">
<link rel="stylesheet" href="../CSS/profilepage.css">
<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-3 text-center">
            <?php
                echo "<img src='".$business_pfp."' class='img-fluid'>";
            ?>
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
                    <button class="nav-link active"  id="v-pills-upload-tab" data-bs-toggle="pill" data-bs-target="#v-pills-upload" type="button" role="tab" aria-controls="v-pills-upload" aria-selected="true">Upload a new post</button>
                    <button class="nav-link" id="v-pills-managePost-tab" data-bs-toggle="pill" data-bs-target="#v-pills-managePost" type="button" role="tab" aria-controls="v-pills-managePost" aria-selected="false">Manage Posts</button>
                    <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">Update Profile</button>
                    <button class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false">Messages</button>
                    <button class="nav-link" type="button"><a href="../AccPages/logout.php" class="logoutBTN btn">Log Out</a></button>
                </div>
            </div>
        </div>
        <div class="col w-100">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="v-pills-upload" role="tabpanel" aria-labelledby="v-pills-upload-tab">
                    <div class="container-fluid w-50" style="margin-top: 90px;">
                        <div class="col-md-6 container-fluid text-center">
                            <div class="container-fluid">
                                <h1>Upload a new post</h1>
                            </div>
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
                <div class="tab-pane fade" id="v-pills-managePost" role="tabpanel" aria-labelledby="v-pills-managePost-tab">Manage Post</div>
                <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                    <div class="container-fluid w-50" style="margin-top: 90px;">
                        <div class="col-md-6 container-fluid">
                            <h1>Set Up you profile</h1>
                        </div>
                    </div>
                    <div class="container-fluid d-flex align-items-center w-50 mt-5 border rounded-5 p-3 bg-white shadow box-area p-5">
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
