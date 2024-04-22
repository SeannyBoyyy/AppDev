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
?>

<p>Total User Acounts: <?php echo $total_users_count; ?></p> <!-- Display total count -->
<p>Total Business Profiles: <?php echo $total_profiles_count; ?></p> <!-- Display total count -->
<p>Total Advertisements: <?php echo $total_ads_count; ?></p> <!-- Display total count -->
<p>Total Product Post: <?php echo $total_prods_count; ?></p> <!-- Display total count -->
<p>Total Farm Photos: <?php echo $total_farmPhotos_count; ?></p> <!-- Display total count -->