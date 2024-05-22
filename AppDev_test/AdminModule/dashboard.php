<?php
// Connect to your database
include('../config/connectDb.php');

// Fetch total count of user accounts
$total_users = mysqli_query($conn, "SELECT COUNT(*) as total_users FROM user_accounts");
$total_users_count = mysqli_fetch_assoc($total_users)['total_users'];

// Fetch total count of business profiles
$total_profiles = mysqli_query($conn, "SELECT COUNT(*) as total_profiles FROM business_profile");
$total_profiles_count = mysqli_fetch_assoc($total_profiles)['total_profiles'];

// Fetch total count of advertisements
$total_ads = mysqli_query($conn, "SELECT COUNT(*) as total_ads FROM business_advertisement");
$total_ads_count = mysqli_fetch_assoc($total_ads)['total_ads'];

// Fetch total count of product post
$total_prods = mysqli_query($conn, "SELECT COUNT(*) as total_prods FROM posting_module");
$total_prods_count = mysqli_fetch_assoc($total_prods)['total_prods'];

// Fetch total count of farm photos
$total_farmPhotos = mysqli_query($conn, "SELECT COUNT(*) as total_farmPhotos FROM business_photos");
$total_farmPhotos_count = mysqli_fetch_assoc($total_farmPhotos)['total_farmPhotos'];

// Fetch total count of Subscriber
$total_subscriber = mysqli_query($conn, "SELECT COUNT(*) as total_subscriber FROM user_subscriptions");
$total_subscriber_count = mysqli_fetch_assoc($total_subscriber)['total_subscriber'];
?>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">


    <style>
        .body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .dashboard-item {
            border: 1px solid #ccc;
            padding: 20px;
            margin-bottom: 20px;
            width: 100%;
            text-align: center;
        }
        .count {
            font-size: 36px;
            font-weight: bold;
            color: #007BFF;
            border-radius: 5px;
            background-color: #f1f1f1;
            padding: 10px 0;
            margin-top: 10px;
        }
        .card{
            background:rgba(255,255,255, 0.7)
        }
        .card i{
            margin-right:20px;
        }
    </style>



<div class="container mt-5">
    <div class="border 2px solid black p-5" style="border-radius:20px;background-color:rgba(192,192,192, 0.6)">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center mb-4" style="font-size: 5vw; font-family: impact;">
                    <i class="fas fa-desktop" style="margin-right: 20px;"></i>Dashboard
                </h1>
            </div>
        </div>

        <div class="row">

            <div class="col-md-4">
                <div class="card dashboard-item" id="totalUsers">
                    <h2><i class="fas fa-user"></i>Total User Accounts</h2>
                    <p class="count"><?php echo $total_users_count; ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card dashboard-item" id="totalProfiles">
                    <h2><i class="fas fa-user-circle"></i>Total Business Profiles</h2>
                    <p class="count"><?php echo $total_profiles_count; ?></p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card dashboard-item" id="totalAds">
                    <h2><i class="fas fa-ad"></i>Total Advertisements</h2>
                    <p class="count"><?php echo $total_ads_count; ?></p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card dashboard-item" id="totalProds">
                    <h2><i class="fas fa-pen"></i>Total Product Posts</h2>
                    <p class="count"><?php echo $total_prods_count; ?></p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card dashboard-item" id="totalFarmPhotos">
                    <h2><i class="fas fa-image"></i>Total Farm Photos</h2>
                    <p class="count"><?php echo $total_farmPhotos_count; ?></p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card dashboard-item" id="totalSubscriber">
                    <h2><i class="fas fa-drivers-license"></i>Total Subscriber</h2>
                    <p class="count"><?php echo $total_subscriber_count; ?></p>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<script>
    // Fetch data from server
    function fetchData(url, elementId) {
        fetch(url)
            .then(response => response.json())
            .then(data => {
                document.getElementById(elementId).querySelector('p').innerText = data.count;
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    }

    // Update dashboard with fetched data
    fetchData('../api/getTotalUsers.php', 'totalUsers');
    fetchData('../api/getTotalProfiles.php', 'totalProfiles');
    fetchData('../api/getTotalAds.php', 'totalAds');
    fetchData('../api/getTotalProds.php', 'totalProds');
    fetchData('../api/getTotalFarmPhotos.php', 'totalFarmPhotos');
</script>

</div>
