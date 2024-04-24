<?php
    $sqlQ = "SELECT * FROM user_subscriptions WHERE user_id = ?";
    $stmt = $conn->prepare($sqlQ);
    $stmt->bind_param("s", $businessOwner);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $subscr_data = $result->fetch_assoc();
    }
?>
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