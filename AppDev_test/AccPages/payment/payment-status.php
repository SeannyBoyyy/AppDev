<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link rel="stylesheet" href="../CSS/view-farm.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
      .nav-link:hover{
        color:black;
        transition: color 0.3s ease, transform 0.3s ease;
        transform: translateY(-5px);
      }
      </style>
  </head>
  <body>
<?php 
// Include the configuration file  
require_once 'config.php'; 
 
// Include the database connection file  
require_once 'dbConnect.php'; 
 
$statusMsg = ''; 
$status = 'error'; 
 
// Check whether the DB reference ID is not empty 
if(!empty($_GET['checkout_ref_id'])){ 
    $paypal_order_id  = base64_decode($_GET['checkout_ref_id']); 
     
    // Fetch subscription data from the database 
    $sqlQ = "SELECT S.*, P.name as plan_name, P.price as plan_price, P.interval, P.interval_count FROM user_subscriptions as S LEFT JOIN plans as P ON P.id=S.plan_id WHERE paypal_order_id = ?"; 
    $stmt = $db->prepare($sqlQ);  
    $stmt->bind_param("s", $paypal_order_id); 
    $stmt->execute(); 
    $result = $stmt->get_result(); 
 
    if($result->num_rows > 0){ 
        $subscr_data = $result->fetch_assoc(); 
         
        $status = 'success'; 
        $statusMsg = 'Your Subscription Payment has been Successful!'; 
    }else{ 
        $statusMsg = "Subscription has been failed!"; 
    } 
}else{ 
    header("Location: index.php"); 
    exit; 
} 
?>

<?php if (!empty($subscr_data)) { ?>
  <div class="alert alert-success" role="alert">
    <h1 class="alert-heading"><?php echo $statusMsg; ?></h1>

    <h4>Payment Information</h4>
    <p><b>Reference Number:</b> #<?php echo $subscr_data['id']; ?></p>
    <p><b>Subscription ID:</b> <?php echo $subscr_data['paypal_subscr_id']; ?></p>
    <p><b>TXN ID:</b> <?php echo $subscr_data['paypal_order_id']; ?></p>
    <p><b>Paid Amount:</b> <?php echo $subscr_data['paid_amount'] . ' ' . $subscr_data['currency_code']; ?></p>
    <p><b>Status:</b> <?php echo $subscr_data['status']; ?></p>

    <h4>Subscription Information</h4>
    <p><b>Plan Name:</b> <?php echo $subscr_data['plan_name']; ?></p>
    <p><b>Amount:</b> <?php echo $subscr_data['plan_price'] . ' ' . CURRENCY; ?></p>
    <p><b>Plan Interval:</b> <?php echo $subscr_data['interval_count'] . $subscr_data['interval']; ?></p>
    <p><b>Period Start:</b> <?php echo $subscr_data['valid_from']; ?></p>
    <p><b>Period End:</b> <?php echo $subscr_data['valid_to']; ?></p>

    <h4>Payer Information</h4>
    <p><b>Name:</b> <?php echo $subscr_data['subscriber_name']; ?></p>
    <p><b>Email:</b> <?php echo $subscr_data['subscriber_email']; ?></p>
  </div>
<?php } else { ?>
  <div class="alert alert-danger" role="alert">
    <h1 class="alert-heading">Subscription Failed!</h1>
    <p><?php echo $statusMsg; ?></p>
  </div>
<?php } ?>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
 integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
 crossorigin="anonymous">
</script>
