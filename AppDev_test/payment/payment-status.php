<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subscription Page</title>
    <link rel="icon" type="image/x-icon" href="logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>

    <?php
    include('../navbars/subs-navbar.php');

    session_start();
    if (isset($_SESSION['ownerID'])) {
        $business_owner = $_SESSION['ownerID'];
    } else {
        echo 'no owner ';
        header('Location: ../AccPages/login-page.php');
        exit();
    }

    // Include the configuration file  
    require_once 'config.php';

    // Include the database connection file  
    require_once 'dbConnect.php';

    $statusMsg = '';
    $status = 'error';

    // Check whether the DB reference ID is not empty
    if (!empty($_GET['checkout_ref_id'])) {
        $paypal_order_id = base64_decode($_GET['checkout_ref_id']);

        // Fetch subscription data from the database
        $sqlQ = "SELECT S.*, P.name as plan_name, P.price as plan_price, P.interval, P.interval_count FROM user_subscriptions as S LEFT JOIN plans as P ON P.id=S.plan_id WHERE paypal_order_id = ?";
        $stmt = $db->prepare($sqlQ);
        $stmt->bind_param("s", $paypal_order_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $subscr_data = $result->fetch_assoc();

            $status = 'success';
            $statusMsg = 'Your Subscription Payment has been Successful!';
        } else {
            $statusMsg = "Subscription failed!";
        }
    } else {
        header("Location: index.php");
        exit;
    }
    ?>

    <div class="container my-5">
        <?php if (!empty($subscr_data)) { ?>
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-sm-12 rounded-5 bg-white shadow p-4">
                <h1 class="alert alert-<?php echo $status; ?>"><?php echo $statusMsg; ?></h1>
                <h4>Payment Information</h4>
                <ul class="list-group">
                    <li class="list-group-item"><b>Reference Number:</b> #<?php echo $subscr_data['id']; ?></li>
                    <li class="list-group-item"><b>Subscription ID:</b> <?php echo $subscr_data['paypal_subscr_id']; ?></li>
                    <li class="list-group-item"><b>TXN ID:</b> <?php echo $subscr_data['paypal_order_id']; ?></li>
                    <li class="list-group-item"><b>Paid Amount:</b> <?php echo $subscr_data['paid_amount'] . ' ' . $subscr_data['currency_code']; ?></li>
                    <li class="list-group-item"><b>Status:</b> <?php echo $subscr_data['status']; ?></li>
                </ul>

                <h4 class="mt-4">Subscription Information</h4>
                <ul class="list-group">
                    <li class="list-group-item"><b>Plan Name:</b> <?php echo $subscr_data['plan_name']; ?></li>
                    <li class="list-group-item"><b>Amount:</b> <?php echo $subscr_data['plan_price'] . ' ' . CURRENCY; ?></li>
                    <li class="list-group-item"><b>Plan Interval:</b> <?php echo $subscr_data['interval_count'] . ' ' . $subscr_data['interval']; ?></li>
                    <li class="list-group-item"><b>Period Start:</b> <?php echo $subscr_data['valid_from']; ?></li>
                    <li class="list-group-item"><b>Period End:</b> <?php echo $subscr_data['valid_to']; ?></li>
                </ul>

                <h4 class="mt-4">Payer Information</h4>
                <ul class="list-group">
                    <li class="list-group-item"><b>Name:</b> <?php echo $subscr_data['subscriber_name']; ?></li>
                    <li class="list-group-item"><b>Email:</b> <?php echo $subscr_data['subscriber_email']; ?></li>
                </ul>
            </div>
        </div>
        <?php } else { ?>
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-sm-12 text-center">
                <h1 class="alert alert-danger">Your Subscription failed!</h1>
                <p class="alert alert-danger"><?php echo $statusMsg; ?></p>
            </div>
        </div>
        <?php } ?>
    </div>

    <div class="container text-center my-5">
        <?php
            // Query to check if business profile exists for the owner
            $sql = "SELECT * FROM business_profile WHERE owner = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("i", $business_owner);  // Assuming `owner` is an integer (use "s" if it's a string)
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if any rows are returned
            if ($result->num_rows > 0) {
                // Business profile exists, show Profile Page button
                echo '<a class="btn btn-success mx-2" href="../ProfileModule/profile-page.php?active=profile">Profile Page</a>';
            } else {
                // No business profile, show SetUp Profile button
                echo '<a class="btn btn-success mx-2" href="../ProfileModule/profile-setup.php">SetUp Profile</a>';
            }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+DJAMxyPu22j6cKJ6PB4P8iwJu1X4" crossorigin="anonymous"></script>
</body>

</html>
