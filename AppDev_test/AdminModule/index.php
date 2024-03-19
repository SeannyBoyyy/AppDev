<?php
    include('../config/connectDb.php');
    include('../navbars/profilepage-nav.php'); 

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form data
    $userId = $_POST['user_id'];

    // Delete user from business_profile table
    $sql = "DELETE FROM business_profile WHERE id = $userId";

    if ($conn->query($sql) === TRUE) {
        echo "User deleted successfully";
    } else {
        echo "Error deleting user: " . $conn->error;
    }
}
?>


<body>

    <div class="container-fluid">
        <div class="row">
            <div class="col-3 mt-5">
                <H1>Admin</H1>
                <div class="d-flex align-items-start">
                    <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true">Manage User</button>
                        <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">Manage Business Profile</button>
                        <button class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false">Manage Post</button>
                    </div>
                </div>
            </div>
            <div class="col-7 mt-5">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab" tabindex="0">
                        <?php
                            include('manage_user.php');
                        ?>
                    </div>
                    <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab" tabindex="0">
                        <?php
                            include('manage_business_profile.php');
                        ?>
                    </div>
                    <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab" tabindex="0">
                        <?php
                            include('manage_post.php');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
