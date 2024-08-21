<?php
    session_start();
    include('./navbars/viewer-homepage.php');
    include('config/connectDb.php');

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
            echo "
            <script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Business ID not provided!',
                    icon: 'error'
                }).then(function() {
                    window.location = 'viewFarm.php?business_id=$business_id';
                });
            </script>";
        
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
            echo "
            <script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Contact details and message are required!',
                    icon: 'error'
                }).then(function() {
                    window.location = 'viewFarm.php?business_id=$business_id';
                });
            </script>";
        
            exit();
        } else {
            // Insert into message with the retrieved business ID
            $query = "INSERT INTO message_module (sent_by, sent_to, message) VALUES ('$contactDeets', '$business_id', '$message')";
            mysqli_query($conn, $query);
        
            echo "
            <script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Message sent successfully!',
                    icon: 'success'
                }).then(function() {
                    window.location = 'viewFarm.php?business_id=$business_id';
                });
            </script>";
        
            exit();
        }
    } 


?>
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<style>
        .card-body h5 {
        font-weight: bold;
        font-size: 20px;
        }
        .card-body h5 i {
        margin-right: 10px;
        }
        .cardUnderLine{
             transition: box-shadow 0.3s ease;
        }
       .cardUnderLine:hover{
        text-decoration: underline;
        transform: translateY(-5px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
       }
       .msgBtn{
        background-color: #e4e6eb;
        font-size: 15px;
       }
       .msgBtn:hover{
        filter: brightness(90%);
       }
       .carousel-caption {
        text-shadow: 0 2px 4px rgba(0, 0, 0, 2);
        color: white;
        }
        .avatar-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: #007bff;
        color: white;
        font-size: 36px;
        }
        .initials {
            font-size: 36px;
            font-weight: bold;
        }
    </style>
    <!-- Your HTML code to display business profile and posting modules -->
    <body style="background-color: #f0f2f5;">
        <div class="container-fluid">
            <div class="row justify-contents-center text-center">
                <div class="col-lg-3 col-12 text-center overflow-auto bg-white p-0 m-0">
                    <div>
                        <img class="img-fluid img-thumbnail rounded-circle object-fit-cover" style="height: 300px; width:300px;" src="ProfileModule/img/<?php echo $business_pfp; ?>">
                    </div>
                    <h3 class="mt-3" style="color:black; font-size:40px;"><i class="fas fa-building"></i><?php echo $business_name; ?></h3>
                    <button type="button"  class="msgBtn btn-lg border-0 px-5" data-bs-toggle="modal" data-bs-target="#exampleModal">  <small>Message</small> 
                    </button>
                    <div class="card text-center mb-3 shadow-none bg-transparent">
                        <div class="card-body text-dark">
                            <h5 class="card-title"><i class="fas fa-user"></i>Bio</h5>
                            <p class="card-text"><?php 
                                echo $business_bio; 
                            ?></p>
                            <h5 class="card-title"><i class="fas fa-map-marker-alt"></i>Address</h5>
                            <p class="card-text"><?php
                                echo $business_address
                            ?></p>
                            <h5 class="card-title"><i class="fas fa-envelope"></i>Email</h5>
                            <p class="card-text"><?php
                                echo $business_email
                            ?></p>
                            <h5 class="card-title"><i class="fas fa-phone"></i>Contact Number</h5>
                            <p class="card-text"><?php
                                echo $business_contact_number
                            ?></p>
                        </div>
                    </div>
                    <div class="col-12 mb-5">
                        <iframe src="https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=<?php echo $business_address ; ?>+(My%20Business%20Name)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"
                        width="300px" height="300px" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>    
                            
                <div class="col-lg-9 col-12 p-0">
                    <?php if (!empty($photos)): ?>
                        <div class="container-fluid text-center p-0">
                            <div class="row">
                                <div id="carouselExampleCaptions" class="carousel slide w-100"  data-bs-ride="carousel" style=" height: 500px;">
                                <div class="carousel-indicators">
                                    <?php foreach($photos as $key => $photo) { ?>
                                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="<?php echo $key; ?>" class="<?php if($key === 0) echo 'active'; ?>" aria-current="true" aria-label="Slide <?php echo $key + 1; ?>"></button>
                                    <?php } ?>
                                </div>
                                    <div class="carousel-inner">
                                        <?php foreach($photos as $key => $photo) { ?>
                                            <div class="carousel-item <?php if($key === 0) echo 'active'; ?>">
                                                <img style="height: 500px;"  src="ProfileModule/img/<?php echo $photo['image']; ?>" class="d-block w-100 object-fit-cover" alt="...">
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <button class="carousel-control-prev custom-control-dark" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next custom-control-dark" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>    
                                </div>
                                <?php else: ?>
                                    <p>No Farm Photos found.</p>
                                <?php endif; ?> 
                                <?php if (!empty($profiles)): ?>
                                <div class="container-fluid text-center">
                                    <div class="row">
                                        <h1 class="mb-3" style="font-size: 50px; color: black; font-weight: bold;"><i class="fas fa-shopping-basket p-4"></i>Products</h1>
                                        <div style="height: 30px;"></div>
                                        <?php foreach($profiles as $profile) { ?>
                                            <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-4 cardContainer p-0 mx-auto" style="height: 399.17px;width:288.2px;">
                                                <div class="card border-0 text-left m-0 cardUnderLine" style="height: 399.17px;width:288.2px;padding:4px;">
                                                    <img style="height: 280px;width:280px;" class="object-fit-cover card-img-top m-0" src="ProfileModule/img/<?php echo $profile['image']; ?>" alt="<?php echo $profile['name']; ?>">
                                                    <div class="card-body mt-3" style="height: 100px;width:280px; padding:4px;">
                                                        <h5 class="card-title"><?php echo $profile['name']; ?></h5>
                                                        <p class="card-text"><?php echo $profile['text']; ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div> 
                                <?php else: ?>
                                    <p>No Products found.</p>
                                <?php endif; ?>
                    </div>                    
                </div>
            </div>
        </div>
    <div class="container-fluid mt-3 p-0">
        <?php if (!empty($advertisements)): ?>
            <div class="container-fluid text-center p-0">
                <h1 style="font-size: 50px;color:black;font-weight: bold;"><i class="fas fa-ad p-4"></i>Advertisement</h1><div style="height: 30px;"></div>
                <div class="row">
                    <div id="carouselExampleCaptions1" class="carousel slide w-100"  data-bs-ride="carousel" style=" height: 500px;">
                        <div class="carousel-indicators">
                            <?php foreach($advertisements as $key => $advertisement) { ?>
                                <button type="button" data-bs-target="#carouselExampleCaptions1" data-bs-slide-to="<?php echo $key; ?>" class="<?php if($key === 0) echo 'active'; ?>" aria-current="true" aria-label="Slide <?php echo $key + 1; ?>"></button>
                            <?php } ?>
                        </div>
                        <div class="carousel-inner">
                            <?php foreach($advertisements as $key => $advertisement) { ?>
                                <div class="carousel-item <?php if($key === 0) echo 'active'; ?>">
                                    <img style="height: 500px;" src="ProfileModule/img/<?php echo $advertisement['image']; ?>" class="d-block w-100 object-fit-cover" alt="...">
                                    <div class="carousel-caption align-items-start justify-content-start">
                                        <h1 class="text-white"><?php echo $advertisement['name']; ?></h1>
                                        <p class="text-white"><?php echo $advertisement['text']; ?></p>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions1" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions1" data-bs-slide="next">
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
    <div class="row justify-contents-center align-items-center text-cnter mt-5">
        <div class="container" style="max-width: 70%;">
            <div class="card">
                <div class="card-header"><h1 class="mt-3 mb-3">Review & Rating</h1></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4 text-center">
                            <h1 class="text-warning mt-4 mb-4">
                                <b><span id="average_rating">0.0</span> / 5</b>
                            </h1>
                            <div class="mb-3">
                                <i class="fas fa-star star-light mr-1 main_star"></i>
                                <i class="fas fa-star star-light mr-1 main_star"></i>
                                <i class="fas fa-star star-light mr-1 main_star"></i>
                                <i class="fas fa-star star-light mr-1 main_star"></i>
                                <i class="fas fa-star star-light mr-1 main_star"></i>
                            </div>
                            <h3><span id="total_review">0</span> Review</h3>
                        </div>
                        <div class="col-sm-4">
                            <p>
                                <div class="progress-label-left"><b>5</b> <i class="fas fa-star text-warning"></i></div>

                                <div class="progress-label-right">(<span id="total_five_star_review">0</span>)</div>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="five_star_progress"></div>
                                </div>
                            </p>
                            <p>
                                <div class="progress-label-left"><b>4</b> <i class="fas fa-star text-warning"></i></div>
                                
                                <div class="progress-label-right">(<span id="total_four_star_review">0</span>)</div>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="four_star_progress"></div>
                                </div>               
                            </p>
                            <p>
                                <div class="progress-label-left"><b>3</b> <i class="fas fa-star text-warning"></i></div>
                                
                                <div class="progress-label-right">(<span id="total_three_star_review">0</span>)</div>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="three_star_progress"></div>
                                </div>               
                            </p>
                            <p>
                                <div class="progress-label-left"><b>2</b> <i class="fas fa-star text-warning"></i></div>
                                
                                <div class="progress-label-right">(<span id="total_two_star_review">0</span>)</div>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="two_star_progress"></div>
                                </div>               
                            </p>
                            <p>
                                <div class="progress-label-left"><b>1</b> <i class="fas fa-star text-warning"></i></div>
                                
                                <div class="progress-label-right">(<span id="total_one_star_review">0</span>)</div>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="one_star_progress"></div>
                                </div>               
                            </p>
                        </div>
                        <div class="col-sm-4 text-center">
                            <h3 class="mt-4 mb-3">Write Review Here</h3>
                            <button type="button" name="add_review" id="add_review" class="btn btn-primary">Review</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center my-5">
                <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                        // Fetch reviews from the database
                        $query = "SELECT * FROM review_table WHERE business_id = '$business_id' AND status = 'APPROVE' ORDER BY review_id DESC";
                        $result = mysqli_query($conn, $query);
                        $counter = 0;

                        // Check if there are any reviews
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $review_date = date("l jS, F Y h:i:s A", $row['datetime']);
                                $counter++;
                                $activeClass = ($counter == 1) ? 'active' : ''; // Set the first item as active

                                // Start a new carousel item every 3 reviews
                                if ($counter % 3 == 1) {
                                    echo '<div class="carousel-item ' . $activeClass . '"><div class="row justify-content-center">';
                                }

                                // Get the first letter of the user's name
                                $initial = strtoupper($row['user_name'][0]);
                                ?>
                                <div class="col-md-3">
                                    <div class="card testimonial-card mb-4">
                                        <div class="card-body text-center">
                                            <div class="avatar-circle mx-auto mb-4 d-flex align-items-center justify-content-center">
                                                <span class="initials"><?php echo $initial; ?></span>
                                            </div>
                                            <h4 class="font-weight-bold mb-3"><?php echo $row['user_name']; ?></h4>
                                            <ul class="list-unstyled d-flex justify-content-center mb-4">
                                                <?php
                                                // Display star ratings
                                                for ($star = 1; $star <= 5; $star++) {
                                                    $class_name = ($row['user_rating'] >= $star) ? 'text-warning' : 'star-light';
                                                    ?>
                                                    <li><i class="fas fa-star <?php echo $class_name; ?>"></i></li>
                                                <?php } ?>
                                            </ul>
                                            <p class="testimonial-text"><?php echo $row['user_review']; ?></p>
                                            <small class="text-muted">Reviewed on <?php echo $review_date; ?></small>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                // Close the row and carousel item after 3 items
                                if ($counter % 3 == 0 || $counter == mysqli_num_rows($result)) {
                                    echo '</div></div>';
                                }
                            }
                        } else {
                            echo "<p class='text-center'>No reviews found.</p>";
                        }
                        ?>
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev" style="color: black;">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next" style="color: black;">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>

        </div>

        <div id="review_modal" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Submit Review</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h4 class="text-center mt-2 mb-4">
                            <i class="fas fa-star star-light submit_star mr-1" id="submit_star_1" data-rating="1"></i>
                            <i class="fas fa-star star-light submit_star mr-1" id="submit_star_2" data-rating="2"></i>
                            <i class="fas fa-star star-light submit_star mr-1" id="submit_star_3" data-rating="3"></i>
                            <i class="fas fa-star star-light submit_star mr-1" id="submit_star_4" data-rating="4"></i>
                            <i class="fas fa-star star-light submit_star mr-1" id="submit_star_5" data-rating="5"></i>
                        </h4>
                        <div class="form-group">
                            <input type="text" name="user_name" id="user_name" class="form-control" placeholder="Enter Your Name" />
                        </div>
                        <div class="form-group mt-3">
                            <input type="text" name="busines_id" id="business_id" class="form-control" hidden />
                        </div>
                        <div class="form-group">
                            <textarea name="user_review" id="user_review" class="form-control" placeholder="Type Review Here"></textarea>
                        </div>
                        <div class="form-group text-center mt-4">
                            <button type="button" class="btn btn-primary" id="save_review">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
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
                            <label for="message-text" class="col-form-label">Your email:</label>
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
</body>


<style>
.progress-label-left
{
    float: left;
    margin-right: 0.5em;
    line-height: 1em;
}
.progress-label-right
{
    float: right;
    margin-left: 0.3em;
    line-height: 1em;
}
.star-light
{
	color:#e9ecef;
}
</style>

<script>

$(document).ready(function(){

	var rating_data = 0;

    $('#add_review').click(function(){

        $('#review_modal').modal('show');

    });

    $(document).on('mouseenter', '.submit_star', function(){

        var rating = $(this).data('rating');

        reset_background();

        for(var count = 1; count <= rating; count++)
        {

            $('#submit_star_'+count).addClass('text-warning');

        }

    });

    function reset_background()
    {
        for(var count = 1; count <= 5; count++)
        {

            $('#submit_star_'+count).addClass('star-light');

            $('#submit_star_'+count).removeClass('text-warning');

        }
    }

    $(document).on('mouseleave', '.submit_star', function(){

        reset_background();

        for(var count = 1; count <= rating_data; count++)
        {

            $('#submit_star_'+count).removeClass('star-light');

            $('#submit_star_'+count).addClass('text-warning');
        }

    });

    $(document).on('click', '.submit_star', function(){

        rating_data = $(this).data('rating');

    });

    $('#save_review').click(function(){

        var user_name = $('#user_name').val();
        var business_id = "<?php echo $business_id; ?>";
        var user_review = $('#user_review').val();

        if(user_name == '' || user_review == '')
        {
            Swal.fire({
                title: 'Error!',
                text: 'Please fill in all the fields.',
                icon: 'error'
            });
            return false;
        }
        else
        {
            $.ajax({
                url:"submit_rating.php?business_id=<?php echo $business_id;?>",
                method:"POST",
                data:{rating_data:rating_data, user_name:user_name, business_id:business_id, user_review:user_review, status:'PENDING'}, // Add status field
                success:function(data)
                {
                    $('#review_modal').modal('hide');

                    load_rating_data();

                    Swal.fire({
                        title: 'Success!',
                        text: 'Your Review & Rating Successfully Submitted!',
                        icon: 'success'
                    }).then(function() {
                        window.location = 'viewFarm.php?business_id=<?php echo $business_id;?>';
                    });
                }
            })
        }

    });

    load_rating_data();

    function load_rating_data()
    {
        $.ajax({
            url:"submit_rating.php?business_id=<?php echo $business_id;?>",
            method:"POST",
            data:{action:'load_data', business_id: '<?php echo $business_id; ?>'},
            dataType:"JSON",
            success:function(data)  
            {
                $('#average_rating').text(data.average_rating);
                $('#total_review').text(data.total_review);

                var count_star = 0;

                $('.main_star').each(function(){
                    count_star++;
                    if(Math.ceil(data.average_rating) >= count_star)
                    {
                        $(this).addClass('text-warning');
                        $(this).addClass('star-light');
                    }
                });

                $('#total_five_star_review').text(data.five_star_review);

                $('#total_four_star_review').text(data.four_star_review);

                $('#total_three_star_review').text(data.three_star_review);

                $('#total_two_star_review').text(data.two_star_review);

                $('#total_one_star_review').text(data.one_star_review);

                $('#five_star_progress').css('width', (data.five_star_review/data.total_review) * 100 + '%');

                $('#four_star_progress').css('width', (data.four_star_review/data.total_review) * 100 + '%');

                $('#three_star_progress').css('width', (data.three_star_review/data.total_review) * 100 + '%');

                $('#two_star_progress').css('width', (data.two_star_review/data.total_review) * 100 + '%');

                $('#one_star_progress').css('width', (data.one_star_review/data.total_review) * 100 + '%');

            }
        })
    }

});

</script>

<?php
    include('./navbars/viewer-footer.php');
    include('./navbars/footer.php');
?>