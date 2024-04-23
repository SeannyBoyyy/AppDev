<?php  
// Include the configuration file  
require_once 'config.php';  
  
// Include the database connection file  
include_once 'dbConnect.php';  
  
// Include the PayPal API library  
require_once 'PaypalCheckout.class.php';  
$paypal = new PaypalCheckout;  
  
$response = array('status' => 0, 'msg' => 'Request Failed!');  
$api_error = '';  

if(!empty($_POST['request_type']) && $_POST['request_type'] == 'create_plan'){  
    $plan_id = $_POST['plan_id'];  

    // Fetch plan details from the database  
    $sqlQ = "SELECT `name`,`price`,`interval`,`interval_count` FROM plans WHERE id=?";  
    $stmt = $db->prepare($sqlQ);  
    $stmt->bind_param("i", $plan_id);  
    $stmt->execute();  
    $stmt->bind_result($planName, $planPrice, $planInterval, $intervalCount);  
    $stmt->fetch();  

    $plan_data = array( 
        'name' => $planName,  
        'price' => $planPrice,  
        'interval' => $planInterval,  
        'interval_count' => $intervalCount,  
    );  

    // Create plan with PayPal API  
    try {  
        $subscr_plan = $paypal->createPlan($plan_data);  
    } catch(Exception $e) {   
        $api_error = $e->getMessage();   
    }  
      
    if(!empty($subscr_plan)){  
        $response = array(  
            'status' => 1,   
            'data' => $subscr_plan  
        );  
    } else {  
        $response['msg'] = $api_error;  
    }  
} elseif(!empty($_POST['request_type']) && $_POST['request_type'] == 'capture_subscr'){  
    // Your code for capturing subscription here
    // ...
}  

// Ensure that only JSON data is outputted
header('Content-Type: application/json');
echo json_encode($response);
exit;
?>
