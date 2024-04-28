<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
    include('../navbars/admin-navbar.php'); 

    // Retrieve active page from query parameter
    $activePage = isset($_GET['active']) ? $_GET['active'] : '';

    // Define function to add 'active' class to the button
    function isActive($page, $activePage) {
        return $page === $activePage ? 'active' : '';
    }

    // Retrieve active page from query parameter
    $activePage = isset($_GET['active']) ? $_GET['active'] : '';

    // Define function to add 'active' class to the button
    function isShowActive($page, $activePage) {
            return $page === $activePage ? 'show active' : '';
    }

    $countSql = "SELECT id, COUNT(*) AS occurrences
        FROM admin_messages";

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
?>

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
        padding: 10px 15px;
        border-radius: 5px;
    }
    .nav-link:focus {
        outline: none !important;
        box-shadow: none !important;
    }
    </style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-3">
                <h1 class="nav-brand">Welcome to <br> Admin Panel</h1>
                <div class="col-12">
                    <div class="d-flex align-items-start">
                        <div class="nav flex-column nav-pills text-start align-items-start" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <button class="nav-link <?php echo isActive('dashboard', $activePage); ?>" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true" style="color: black;"><i class="fas fa-dashboard"></i>Dashboard</button>
                            <button class="nav-link <?php echo isActive('users', $activePage); ?>" id="v-pills-user-tab" data-bs-toggle="pill" data-bs-target="#v-pills-user" type="button" role="tab" aria-controls="v-pills-user" aria-selected="true" style="color: black;"><i class="fas fa-users"></i>Manage Users</button>
                            <button class="nav-link <?php echo isActive('profiles', $activePage); ?>" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false" style="color: black;"><i class="fas fa-user-circle"></i>Manage Business Profiles</button>
                            <button class="nav-link <?php echo isActive('posts', $activePage); ?>" id="v-pills-posts-tab" data-bs-toggle="pill" data-bs-target="#v-pills-posts" type="button" role="tab" aria-controls="v-pills-posts" aria-selected="false" style="color: black;"><i class="fas fa-pen"></i>Manage Posts</button>
                            <button class="nav-link <?php echo isActive('advertisement', $activePage); ?>" id="v-pills-advertisement-tab" data-bs-toggle="pill" data-bs-target="#v-pills-advertisement" type="button" role="tab" aria-controls="v-pills-advertisement" aria-selected="false" style="color: black;"><i class="fas fa-ad"></i>Manage Advertisement</button>
                            <button class="nav-link <?php echo isActive('photos', $activePage); ?>" id="v-pills-photos-tab" data-bs-toggle="pill" data-bs-target="#v-pills-photos" type="button" role="tab" aria-controls="v-pills-photos" aria-selected="false" style="color: black;"><i class="fas fa-image"></i>Manage Business Photos</button>
                            <button class="nav-link <?php echo isActive('messages', $activePage); ?>" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false"><i class="fas fa-envelope" style="margin-right: 8px;"></i>Message 
                                <span class="badge rounded-pill bg-primary">
                                    <?php echo $msgCount ?>
                                </span>
                            </button>
                            <button class="nav-link <?php echo isActive('subscriber', $activePage); ?>" id="v-pills-subscriber-tab" data-bs-toggle="pill" data-bs-target="#v-pills-subscriber" type="button" role="tab" aria-controls="v-pills-subscriber" aria-selected="false" style="color: black;"><i class="fas fa-drivers-license"></i>Manage Subscriber</button>
                            <button class="nav-link <?php echo isActive('plans', $activePage); ?>" id="v-pills-plans-tab" data-bs-toggle="pill" data-bs-target="#v-pills-plans" type="button" role="tab" aria-controls="v-pills-plans" aria-selected="false" style="color: black;"><i class="fas fa-edit"></i>Manage Plans</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-9">
                <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade <?php echo isShowActive('dashboard', $activePage); ?>" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab"><?php include('./dashboard.php')?></div>
                        <div class="tab-pane fade <?php echo isShowActive('users', $activePage); ?>" id="v-pills-user" role="tabpanel" aria-labelledby="v-pills-user-tab"><?php include('./manage_user.php')?></div>
                        <div class="tab-pane fade <?php echo isShowActive('profiles', $activePage); ?>" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab"><?php include('./manage_business_profile.php')?></div>
                        <div class="tab-pane fade <?php echo isShowActive('posts', $activePage); ?>" id="v-pills-posts" role="tabpanel" aria-labelledby="v-pills-posts-tab"><?php include('./manage_post.php')?></div>
                        <div class="tab-pane fade <?php echo isShowActive('advertisement', $activePage); ?>" id="v-pills-advertisement" role="tabpanel" aria-labelledby="v-pills-advertisement-tab"><?php include('./manage_advertisement.php')?></div>
                        <div class="tab-pane fade <?php echo isShowActive('photos', $activePage); ?>" id="v-pills-photos" role="tabpanel" aria-labelledby="v-pills-photos-tab"><?php include('./manage_photos.php')?></div>
                        <div class="tab-pane fade <?php echo isShowActive('messages', $activePage); ?>" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab"><?php include('./manage_messages.php')?></div>
                        <div class="tab-pane fade <?php echo isShowActive('subscriber', $activePage); ?>" id="v-pills-subscriber" role="tabpanel" aria-labelledby="v-pills-subscriber-tab"><?php include('./manage_subscriber.php')?></div>
                        <div class="tab-pane fade <?php echo isShowActive('plans', $activePage); ?>" id="v-pills-plans" role="tabpanel" aria-labelledby="v-pills-plans-tab"><?php include('./manage_plans.php')?></div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        $('#v-pills-tab button').click(function(){
            // Set display to none for all tab panes
            $('.tab-pane').css('display', 'none');
            
            // Set display to block for the selected tab pane
            $($(this).data('bs-target')).css('display', 'block');
        });
    });
</script>
<?php
include('../navbars/footer.php'); 
?>