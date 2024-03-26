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
        } else {
            echo 'No Posted Products';
            exit(); // Stop further execution if no data found
        }

        // Check if Advertisement modules are found
        if (mysqli_num_rows($postResult) > 0){
            // Fetch Advertisement modules
            $advertisements = mysqli_fetch_all($AdvertisementResult, MYSQLI_ASSOC);
            } else {
                echo 'No Advetisement Post';
                exit(); // Stop further execution if no data found
            }

    } else {
        echo 'No business ID provided';
        exit(); // Stop further execution if no business ID provided
    }




?>

<!-- Your HTML code to display business profile and posting modules -->
<div class="container-fluid">
    <div class="row justify-contents-center align-items-center container-fluid">
        <div class="col-lg-4 col-12 text-center mb-3">
            <div>
                <img class="img-fluid img-thumbnail rounded-circle object-fit-cover" style="height: 300px; width:300px;" src="ProfileModule/img/<?php echo $business_pfp; ?>">
            </div>
            <h3 class="mt-3"><?php echo $business_name; ?></h3>
            <div class="card text-center border-0">
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
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3856.4922742192075!2d120.25691227598732!3d14.853726885663168!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x339670cefbe062b7%3A0x27605e64933d8249!2s34%20Nueva%20Ecija%20St%2C%20Olongapo%2C%20Zambales!5e0!3m2!1sen!2sph!4v1711425038865!5m2!1sen!2sph"
                 width="auto" height="auto" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>    

        <div class="col-lg-8 col-12 mt-5">
            <div class="container-fluid">
                <h1>Advertisement</h1>
                <div class="row">
                    <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <img src="ProfileModule/img/65f9bb18db98b.png" class="img-fluid object-fit-contain">
                                    </div>
                                    <div class="col-md-6 col-12 d-flex justify-content-center align-items-center">
                                        <div class="col-12 col-md-6 text-center">
                                            <h1>Mango</h1>
                                            <p class="text-center">
                                            Step right up and discover the marvel of nature's candy - the magnificent mango!
                                            Bursting with vibrant colors and dripping with sweet, juicy goodness, this tropical treasure is more than just a fruit;
                                            it's a one-way ticket to paradise in every bite.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
            <div class="container-fluid">
                <div class="row">
                    <h1>Products</h1>
                    <?php foreach($profiles as $profile) { ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                            <div class="card text-center p-3 mb-3" style="margin-right: 30px;">
                                <div class="card-body">
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
        </div>
    </div>
</div>

<?php
    include('./navbars/footer.php');
?>
