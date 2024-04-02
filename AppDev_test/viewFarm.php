<?php
    include('./navbars/viewer-homepage.php');
    include('config/connectDb.php');

    session_start();
    if(isset($_SESSION['ownerID'])){
        $business_owner = $_SESSION['ownerID']; 
    }else{
        echo 'no owner ';
    }

    // Check if an identifier is provided in the URL
    if(isset($_GET['business_id'])) {
        // Retrieve the business ID from the URL
        $business_id = $_GET['business_id'];

        // Fetch the business profile based on the provided ID
        $getBusinessSql = "SELECT name, text, image, address, email, contact_number FROM business_profile WHERE id = ?";
        $stmt = mysqli_prepare($conn, $getBusinessSql);
        mysqli_stmt_bind_param($stmt, "i", $business_id);
        mysqli_stmt_execute($stmt);
        $businessResult = mysqli_stmt_get_result($stmt);

        // Fetch the posting modules associated with the business
        $getPostSql = "SELECT name, text, image FROM posting_module WHERE posted_by = ?";
        $stmt = mysqli_prepare($conn, $getPostSql);
        mysqli_stmt_bind_param($stmt, "i", $business_id);
        mysqli_stmt_execute($stmt);
        $postResult = mysqli_stmt_get_result($stmt);

        // Fetch the Advertisement modules associated with the business
        $getAdvertisementSql = "SELECT name, text, image FROM business_advertisement WHERE posted_by = ?";
        $stmt = mysqli_prepare($conn, $getAdvertisementSql);
        mysqli_stmt_bind_param($stmt, "i", $business_id);
        mysqli_stmt_execute($stmt);
        $AdvertisementResult = mysqli_stmt_get_result($stmt);

        // Fetch the Farm Photos modules associated with the business
        $getPhotosSql = "SELECT image,created_at FROM business_photos WHERE posted_by = ?";
        $stmt = mysqli_prepare($conn, $getPhotosSql);
        mysqli_stmt_bind_param($stmt, "i", $business_id);
        mysqli_stmt_execute($stmt);
        $PhotosResult = mysqli_stmt_get_result($stmt);

        // Check if business profile are found
        if (mysqli_num_rows($businessResult) > 0) {
            // Fetch business profile details
            $businessProfile = mysqli_fetch_assoc($businessResult);
            $business_name = $businessProfile['name'];
            $business_bio = $businessProfile['text'];
            $business_pfp = $businessProfile['image'];
            $business_address = $businessProfile['address'];
            $business_email = $businessProfile['email'];
            $business_contact_number = $businessProfile['contact_number'];
        } else {
            echo 'Failed to retrieve information or no data found';
            exit(); // Stop further execution if no data found
        }
        
        // Check if posting modules are found
        if (mysqli_num_rows($postResult) > 0){
        // Fetch posting modules
        $profiles = mysqli_fetch_all($postResult, MYSQLI_ASSOC);
        }

        // Check if Advertisement modules are found
        if (mysqli_num_rows($AdvertisementResult) > 0){
            // Fetch Advertisement modules
            $advertisements = mysqli_fetch_all($AdvertisementResult, MYSQLI_ASSOC);
        }

        // Check if Farm Photos modules are found
        if (mysqli_num_rows($PhotosResult) > 0){
            // Fetch Farm Photos modules
            $photos = mysqli_fetch_all($PhotosResult, MYSQLI_ASSOC);
        }

    } else {
        echo 'No business ID provided';
        exit(); // Stop further execution if no business ID provided
    }

    //------------------------- messaging module -----------------------------
    $contactDeets = $message = '';
    $errors = array('deets'=>'', 'msg'=>'');
    if(isset($_POST["sendMSG"])) {
        // Retrieve the business ID from the form data
        $business_id = isset($_POST['business_id']) ? $_POST['business_id'] : null;

        if (!$business_id) {
            echo "<script> alert('Business ID not provided!'); </script>";
            exit(); // Stop further execution if no business ID provided
        }

        //--------------- send message -------------
        $contactDeets = mysqli_real_escape_string($conn, $_POST["contactDeets"]);
        $message = mysqli_real_escape_string($conn, $_POST["MSG"]);

        if(empty($_POST['contactDeets'])){
            $errors['deets'] = 'Contact details is required!';
        }
        if(empty($_POST['MSG'])){
            $errors['msg'] = 'a message is required!';
        }

        if (empty($_POST['contactDeets']) || empty($_POST['MSG'])) {
            echo "<script> alert('Contact details and message are required!'); </script>";
        } else {
            // Insert into message with the retrieved business ID
            $query = "INSERT INTO message_module (sent_by, sent_to, message) VALUES ('$contactDeets', '$business_id', '$message')";
            mysqli_query($conn, $query);
        
            echo "<script> 
                    alert('Message sent successfully'); 
                    window.location.replace('viewFarm.php?business_id=$business_id');
                </script>";
        }
    } 


?>

<!-- Your HTML code to display business profile and posting modules -->
<div class="container-fluid">
    <div class="row justify-contents-center align-items-center text-cnter">
        <div class="col-lg-4 col-12 text-center mb-3 mt-3">
            <div>
                <img class="img-fluid img-thumbnail rounded-circle object-fit-cover" style="height: 300px; width:300px;" src="ProfileModule/img/<?php echo $business_pfp; ?>">
            </div>
            <h3 class="mt-3"><?php echo $business_name; ?></h3>
            <div class="card text-center mb-3 border-0">
                <div class="card-body">
                    <h5 class="card-title">Bio</h5>
                    <p class="card-text"><?php 
                        echo $business_bio; 
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
            <div class="col-12">
                <iframe src="https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=<?php echo $business_address ; ?>+(My%20Business%20Name)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"
                 width="300px" height="300px" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>    

        <div class="col-lg-8 col-12 mt-3">
            <?php if (!empty($photos)): ?>
            <div class="container-fluid text-center">
                <h1>Farm Photos</h1>
                <div class="row">
                    <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php foreach($photos as $key => $photo) { ?>
                                <div class="carousel-item <?php if($key === 0) echo 'active'; ?>">
                                    <div class="">
                                        <div class="">
                                            <img style="height: 400px;" src="ProfileModule/img/<?php echo $photo['image']; ?>" class="img-fluid object-fit-contain">
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>    
                    </div>
                    
                </div>    
            </div>
            <?php else: ?>
                <p>No Farm Photos found.</p>
            <?php endif; ?>                    

            <?php if (!empty($profiles)): ?>
            <div class="container-fluid text-center">
                <div class="row">
                    <h1 class="mb-3">Products</h1>
                    <?php foreach($profiles as $profile) { ?>
                        <div class="col-12 col-md-6 col-lg-6 col-xl-3 text-center">
                            <div class="card p-3 mb-3 border-0 border-rounded shadow-lg p-3 mb-5 bg-body rounded" style="margin: auto;">
                                <div class="card-body overflow-auto">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <img style="width: 150px; height: 150px;" class=" card-img img-fluid img-thumbnail rounded-circle object-fit-cover mx-auto" src="ProfileModule/img/<?php echo $profile['image']; ?>">
                                        </li>
                                        <li class="list-group-item">
                                            <h5 class="card-title"><?php echo $profile['name']; ?></h5>
                                        </li>
                                        <li class="list-group-item">
                                            <p class="card-text" style="height: 60px;"><?php echo $profile['text']; ?></p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>        
                    <?php } ?>
                </div>
            </div> 
            <?php else: ?>
                <p>No Product found.</p>
            <?php endif; ?>             

            <?php if (!empty($advertisements)): ?>
            <div class="container-fluid text-center">
                <h1>Advertisement</h1>
                <div class="row">
                    <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php foreach($advertisements as $key => $advertisement) { ?>
                                <div class="carousel-item <?php if($key === 0) echo 'active'; ?>">
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <img style="height: 400px;" src="ProfileModule/img/<?php echo $advertisement['image']; ?>" class="img-fluid object-fit-contain">
                                        </div>
                                        <div class="col-md-6 col-12 d-flex justify-content-center align-items-center">
                                            <div class="col-12 col-md-6 text-center">
                                                <h1><?php echo $advertisement['name']; ?></h1>
                                                <p class="text-center"><?php echo $advertisement['text']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>    
                    </div>        
                </div>    
            </div>
            <?php else: ?>
                <p>No Advertisement found.</p>
            <?php endif; ?> 

        </div>
    </div>

    <div class="sticky-bottom">
        <button type="button"  class="btn btn-lg position-absolute bottom-0 end-0" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <small>Message us!</small>
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#90EE90" class="bi bi-chat-fill" viewBox="0 0 16 16">
                <path d="M8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6-.097 1.016-.417 2.13-.771 2.966-.079.186.074.394.273.362 2.256-.37 3.597-.938 4.18-1.234A9 9 0 0 0 8 15"/>
            </svg>
        </button>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">New message</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="viewFarm.php?business_id=<?php echo $business_id; ?>" enctype="multipart/form-data">
                <!-- Include business_id field -->
                <input type="hidden" name="business_id" value="<?php echo $business_id; ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="staticEmail" class="col-form-label">Recipient:</label>
                        <input type="text" type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?php echo $business_name ?>">
                    </div>

                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Your email or contact number:</label>
                        <input type="text" name="contactDeets" class="form-control" value="<?php echo $contactDeets ?>">
                    </div>
                    <div class="row">
                        <small class="text-red mb-2 " style=" color:red"><?php echo $errors['deets'] ?></small>
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Message:</label>
                        <textarea name="MSG" class="form-control" id="message-text"></textarea>
                    </div>
                    <div class="row">
                        <small class="text-red mb-2 " style=" color:red"><?php echo $errors['msg'] ?></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button name="sendMSG" type="submit" class="btn btn-primary">Send message</button>
                </div>
                </form>
                </div>
            </div>
        </div>
</div>




<?php
    include('./navbars/footer.php');
?>


