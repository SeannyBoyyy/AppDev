<?php
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

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <!-- Your HTML code to display business profile and posting modules -->
    <div class="container-fluid" >
        <div class="row justify-contents-center text-cnter">
            <div class="col-lg-4 col-12 text-center mb-3 mt-3 overflow-auto" style="height: 850px;">
                <div>
                    <img class="img-fluid img-thumbnail rounded-circle object-fit-cover" style="height: 300px; width:300px;" src="ProfileModule/img/<?php echo $business_pfp; ?>">
                </div>
                <h3 class="mt-3" style="color:black; font-size:40px;"><i class="fas fa-building" style="margin-right: 15px;"></i><?php echo $business_name; ?></h3>
                <div class="card shadow-none text-center mb-3 border-0">
                    <div class="card-body">
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
                <div class="col-12">
                    <iframe src="https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=<?php echo $business_address ; ?>+(My%20Business%20Name)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"
                    width="300px" height="300px" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>    

            <div class="col-lg-8 col-12 mt-3">
                <?php if (!empty($photos)): ?>
                <div class="container-fluid text-center">
                <div style="height: 50px;"></div>
                    <h1 style="font-size: 50px;color:black;font-weight: bold;"><i class="fas fa-image p-4"></i>Farm Photos</h1><div style="height: 30px;"></div>
                    <div class="row">
                        <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <?php foreach($photos as $key => $photo) { ?>
                                    <div class="carousel-item <?php if($key === 0) echo 'active'; ?>">
                                        <div class="">
                                            <div class="">
                                                <img style="height: 400px; max-width: 850px" src="ProfileModule/img/<?php echo $photo['image']; ?>" class="img-fluid object-fit-contain">
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev" style="width:70px;">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next" style="width:70px;margin-right:80px;">
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
                            <div style="height: 50px;"></div>
                            <h1 class="mb-3" style="font-size: 50px; color: black; font-weight: bold;"><i class="fas fa-shopping-basket p-4"></i>Products</h1>
                            <div style="height: 30px;"></div>
                            <?php foreach($profiles as $profile) { ?>
                                <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-4">
                                    <div class="card border-0 rounded shadow">
                                        <img style="height: 300px;" class="object-fit-cover" src="ProfileModule/img/<?php echo $profile['image']; ?>" class="card-img-top" alt="<?php echo $profile['name']; ?>">
                                        <div class="card-body" style="height: 150px;">
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

        <div class="container-fluid mt-3">
            <?php if (!empty($advertisements)): ?>
            <div class="container-fluid text-center">
                <div style="height: 50px;"></div>
                <h1 style="font-size: 50px;color:black;font-weight: bold;"><i class="fas fa-ad p-4"></i>Advertisement</h1><div style="height: 30px;"></div>
                <div class="row">
                    <div id="carouselExampleCaptions" class="carousel slide w-100"  data-bs-ride="carousel" style=" height: 500px;">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                        </div>
                        <div class="carousel-inner">
                            <?php foreach($advertisements as $key => $advertisement) { ?>
                                <div class="carousel-item <?php if($key === 0) echo 'active'; ?>">
                                    <img style="height: 500px;" src="ProfileModule/img/<?php echo $advertisement['image']; ?>" class="d-block w-100 object-fit-cover" alt="...">
                                    <div class="carousel-caption align-items-start justify-content-start">
                                        <h1 style="color:black;"><?php echo $advertisement['name']; ?></h1>
                                        <p class="" style="color: black;"><?php echo $advertisement['text']; ?></p>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>    
                </div>
            </div>
            <?php else: ?>
                <p>No Advertisement found.</p>
            <?php endif; ?> 
            <hr>    
        </div>
    <div class="row justify-contents-center align-items-center text-cnter">
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
            <div class="mt-5" id="review_content">
            <?php
            // Query to fetch reviews based on business_id
            $query = "SELECT * FROM review_table WHERE business_id = '$business_id' ORDER BY review_id DESC";
            $result = mysqli_query($conn, $query);

            // Check if there are any reviews
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // Convert timestamp to human-readable date
                    $review_date = date("l jS, F Y h:i:s A", $row['datetime']);
            ?>
                    <div class="row mb-3">
                        <div class="col-sm-1">
                            <div class="rounded-circle bg-danger text-white pt-2 pb-2 d-flex justify-content-center align-items-center" style="height: 60px; width: 60px;">
                                <h3 style="margin: 0;"><?php echo strtoupper(substr($row['user_name'], 0, 1)); ?></h3>
                            </div>
                        </div>
                        <div class="col-sm-11">
                            <div class="card">
                                <div class="card-header"><b><?php echo $row['user_name']; ?></b></div>
                                <div class="card-body">
                                    <?php
                                    // Display star ratings
                                    for ($star = 1; $star <= 5; $star++) {
                                        $class_name = ($row['user_rating'] >= $star) ? 'text-warning' : 'star-light';
                                    ?>
                                        <i class="fas fa-star <?php echo $class_name; ?> mr-1"></i>
                                    <?php } ?>
                                    <br />
                                    <?php echo $row['user_review']; ?>
                                </div>
                                <div class="card-footer text-right">On <?php echo $review_date; ?></div>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                // No reviews found
                echo "<p>No reviews found.</p>";
            }
            ?>


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
                        <div class="form-group">
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

        <div class="sticky-bottom">
            <button type="button"  class="btn btn-lg bg-white position-absolute bottom-0 end-0" data-bs-toggle="modal" data-bs-target="#exampleModal">
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
                data:{rating_data:rating_data, user_name:user_name, business_id:business_id, user_review:user_review},
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


