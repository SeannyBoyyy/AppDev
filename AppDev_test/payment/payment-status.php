<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subscription Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <nav class="navbar navbar sticky-top p-3 mb-5" style="background-color: whitesmoke;">
      <div class="container-fluid  align-items-center justify-content-center">
          <a class="navbar-brand fs-3" >ZDeals<img src="http://localhost/AppDev/AppDev_test/ProfileModule/img/logo.png" alt="FarmDeals Logo" width="60" height="60"></a>
      </div>
    </nav>
    <?php
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

        <?php if (!empty($subscr_data)) { ?>
        <div class="container">
            <div class="col-12 rounded-5 bg-white shadow box-area p-3 justify-content-center align-items-center text-center">
                <h1 class="rounded-5 alert alert-<?php echo $status; ?>"><?php echo $statusMsg; ?></h1>
                <h4>Payment Information</h4>
                <ul class="list-group text-center">
                    <li class="list-group-item"><b>Reference Number:</b> #<?php echo $subscr_data['id']; ?></li>
                    <li class="list-group-item"><b>Subscription ID:</b> <?php echo $subscr_data['paypal_subscr_id']; ?></li>
                    <li class="list-group-item"><b>TXN ID:</b> <?php echo $subscr_data['paypal_order_id']; ?></li>
                    <li class="list-group-item"><b>Paid Amount:</b> <?php echo $subscr_data['paid_amount'] . ' ' . $subscr_data['currency_code']; ?></li>
                    <li class="list-group-item"><b>Status:</b> <?php echo $subscr_data['status']; ?></li>
                </ul>

                <h4>Subscription Information</h4>
                <ul class="list-group text-center">
                    <li class="list-group-item"><b>Plan Name:</b> <?php echo $subscr_data['plan_name']; ?></li>
                    <li class="list-group-item"><b>Amount:</b> <?php echo $subscr_data['plan_price'] . ' ' . CURRENCY; ?></li>
                    <li class="list-group-item"><b>Plan Interval:</b> <?php echo $subscr_data['interval_count'] . $subscr_data['interval']; ?></li>
                    <li class="list-group-item"><b>Period Start:</b> <?php echo $subscr_data['valid_from']; ?></li>
                    <li class="list-group-item"><b>Period End:</b> <?php echo $subscr_data['valid_to']; ?></li>
                </ul>

                <h4>Payer Information</h4>
                <ul class="list-group text-center">
                    <li class="list-group-item"><b>Name:</b> <?php echo $subscr_data['subscriber_name']; ?></li>
                    <li class="list-group-item"><b>Email:</b> <?php echo $subscr_data['subscriber_email']; ?></li>
                </ul>
            </div>
        </div>
        <?php } else { ?>
        <div class="container">
            <h1 class="alert alert-danger">Your Subscription failed!</h1>
            <p class="alert alert-danger"><?php echo $statusMsg; ?></p>
        </div>
        <?php } ?>
  </body>
</html>