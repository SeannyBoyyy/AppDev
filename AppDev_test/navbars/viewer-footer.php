<?php

// Database connection
include('./config/connectDb.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user inputs for security
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // Insert data into database
    $sql = "INSERT INTO admin_messages (email, subject, message) VALUES ('$email', '$subject', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert(Message sent successfully.)</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

?>

    <!-- MDB icon -->
    <link rel="icon" href="img/mdb-favicon.ico" type="image/x-icon" />
    <!-- Font Awesome -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    />
    <!-- Google Fonts Roboto -->
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap"
    />
    <!-- MDB -->
    <link rel="stylesheet" href="CSS/mdb.min.css" />
    

<div class="">
    <!-- Footer -->
    <footer
            class="text-center text-lg-start text-dark"
            style="background-color: #ECEFF1"
            >
        <!-- Section: Social media -->
        <section
                class="d-flex justify-content-between p-4 text-white"
                style="background-color: #21D192"
                >
        <!-- Left -->
        <div class="me-5">
            <span>Get connected with us on social networks:</span>
        </div>
        <!-- Left -->

        <!-- Right -->
        <div>
            <a href="" class="text-white me-4">
            <i class="fab fa-facebook-f"></i>
            </a>
            <a href="" class="text-white me-4">
            <i class="fab fa-twitter"></i>
            </a>
            <a href="" class="text-white me-4">
            <i class="fab fa-google"></i>
            </a>
            <a href="" class="text-white me-4">
            <i class="fab fa-instagram"></i>
            </a>
            <a href="" class="text-white me-4">
            <i class="fab fa-linkedin"></i>
            </a>
            <a href="" class="text-white me-4">
            <i class="fab fa-github"></i>
            </a>
        </div>
        <!-- Right -->
        </section>
        <!-- Section: Social media -->

        <!-- Section: Links  -->
        <section class="">
        <div class="container text-center text-md-start mt-5">
            <!-- Grid row -->
            <div class="row mt-3">
            <!-- Grid column -->
            <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
                <!-- Content -->
                <h6 class="text-uppercase fw-bold"> Zambales Local Market </h6>
                <hr
                    class="mb-4 mt-0 d-inline-block mx-auto"
                    style="width: 150px; background-color: #7c4dff; height: 2px"
                    />
                <p>
                Welcome to Zambales Local Market – your gateway to the vibrant world of locally sourced treasures! 
                Dive into a digital marketplace where every click connects you with the freshest produce, finest 
                crafts, and the heartwarming stories behind each product. Join us in celebrating Zambales' rich 
                heritage, one purchase at a time. Let's explore, connect, and support local together!
                </p>
            </div>
            <!-- Grid column -->

            <!-- Grid column -->
            <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                <!-- Links -->
                <h6 class="text-uppercase fw-bold">Products</h6>
                <hr
                    class="mb-4 mt-0 d-inline-block mx-auto"
                    style="width: 60px; background-color: #7c4dff; height: 2px"
                    />
                <p>
                <a href="#!" class="text-dark">MDBootstrap</a>
                </p>
                <p>
                <a href="#!" class="text-dark">MDWordPress</a>
                </p>
                <p>
                <a href="#!" class="text-dark">BrandFlow</a>
                </p>
                <p>
                <a href="#!" class="text-dark">Bootstrap Angular</a>
                </p>
            </div>
            <!-- Grid column -->

            <!-- Grid column -->
            <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                <!-- Links -->
                <h6 class="text-uppercase fw-bold">Useful links</h6>
                <hr
                    class="mb-4 mt-0 d-inline-block mx-auto"
                    style="width: 60px; background-color: #7c4dff; height: 2px"
                    />
                <p>
                <a href="#!" class="text-dark">Your Account</a>
                </p>
                <p>
                <a href="#!" class="text-dark">Become an Affiliate</a>
                </p>
                <p>
                <a href="#!" class="text-dark">Shipping Rates</a>
                </p>
                <p>
                <a href="#!" class="text-dark">Help</a>
                </p>
            </div>
            <!-- Grid column -->

            <!-- Grid column -->
            <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                <!-- Links -->
                <h6 class="text-uppercase fw-bold">Contact Us</h6>
                <hr
                    class="mb-4 mt-0 d-inline-block mx-auto"
                    style="width: 60px; background-color: #7c4dff; height: 2px"
                    />
                <form method="post" action="" class="contact-form">
                    <div class="mb-2">
                        <input type="text" class="form-control" placeholder="Your Email" name="email">
                    </div>
                    <div class="mb-2">
                        <input type="text" class="form-control" placeholder="Subject" name="subject">
                    </div>
                    <div class="mb-2">
                        <textarea name="message" id="" cols="30" rows="3" class="form-control" style="resize:none" placeholder="Message"></textarea>
                    </div>
                    <div class="mb-5">
                        <button type="submit" class="btn w-100 btn-md btn-success">Send</button>
                    </div>
                </form>
            </div>
            <!-- Grid column -->
            </div>
            <!-- Grid row -->
        </div>
        </section>
        <!-- Section: Links  -->

        <!-- Copyright -->
        <div
            class="text-center p-3"
            style="background-color: rgba(0, 0, 0, 0.2)"
            >
        © 2024 Copyright:
        <a class="text-dark" href="#!"
            > Zambales Local Market </a
            >
        </div>
        <!-- Copyright -->
    </footer>
    <!-- Footer -->
</div>
<!-- End of .container -->

    <!-- MDB -->
    <script type="text/javascript" src="JS/mdb.umd.min.js"></script>
    <!-- Custom scripts -->
    <script type="text/javascript"></script>