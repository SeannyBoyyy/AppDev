<?php
    include('../navbars/admin-navbar.php'); 
?>

<head>
    <style>
        .nav-link {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        padding: 10px 15px;
        margin-bottom: 10px;
        transition: color 0.3s ease;
        border: none !important;
    }

    .nav-link i {
        margin-right: 10px;
    }
    </style>
</head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-3">
                <h1 class="nav-brand">Welcome to <br> Admin Panel</h1>
                <div class="col-12">
                    <div class="d-flex align-items-start">
                        <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true" style="color: black;"><i class="fas fa-users"></i>Manage Users</button>
                            <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false" style="color: black;"><i class="fas fa-user-circle"></i>Manage Farm Profiles</button>
                            <button class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false" style="color: black;"><i class="fas fa-pen"></i>Manage Posts</button>
                            <button class="nav-link" id="v-pills-advertisement-tab" data-bs-toggle="pill" data-bs-target="#v-pills-advertisement" type="button" role="tab" aria-controls="v-pills-advertisement" aria-selected="false" style="color: black;"><i class="fas fa-ad"></i>Manage Advertisement</button>
                            <button class="nav-link" id="v-pills-photos-tab" data-bs-toggle="pill" data-bs-target="#v-pills-photos" type="button" role="tab" aria-controls="v-pills-photos" aria-selected="false" style="color: black;"><i class="fas fa-image"></i>Manage Farm Photos</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-9">
                <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab" tabindex="0"><?php include('./manage_user.php')?></div>
                        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab" tabindex="0"><?php include('./manage_business_profile.php')?></div>
                        <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab" tabindex="0"><?php include('./manage_post.php')?></div>
                        <div class="tab-pane fade" id="v-pills-advertisement" role="tabpanel" aria-labelledby="v-pills-advertisement-tab" tabindex="0"><?php include('./manage_advertisement.php')?></div>
                        <div class="tab-pane fade" id="v-pills-photos" role="tabpanel" aria-labelledby="v-pills-photos-tab" tabindex="0"><?php include('./manage_photos.php')?></div>
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