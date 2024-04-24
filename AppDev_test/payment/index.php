<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subscription Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body class="d-flex justify-content-center align-items-center">
  <?php 
    // Include configuration file  
    require_once 'config.php'; 
    
    // Include the database connection file 
    include_once 'dbConnect.php'; 
    
    // Fetch plans from the database 
    $sqlQ = "SELECT * FROM plans"; 
    $stmt = $db->prepare($sqlQ); 
    $stmt->execute(); 
    $result = $stmt->get_result(); 
    
    // Get logged-in user ID from sesion 
    // Session name need to be changed as per your system 
    $loggedInUserID = !empty($_SESSION['ownerID'])?$_SESSION['ownerID']:0; 
    ?>
    
    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo PAYPAL_SANDBOX?PAYPAL_SANDBOX_CLIENT_ID:PAYPAL_PROD_CLIENT_ID; ?>&vault=true&intent=subscription"></script>
    <div class="container-fluid">
        <div class="container-fluid justify-content-center align-items-center">
            <div class="panel">
            </div>
            <div class="overlay hidden" style="display: none;">
                <div class="overlay-content">
                <img src="" alt="Processing..." />
                </div>
            </div>

            <div class="container mt-5"> 
                <div class="row d-flex justify-content-center align-items-center">
                    <div class="col-xl-6 col-12 rounded-5 bg-white shadow box-area p-5 justify-content-center align-items-center">
                        <div class="panel-heading">
                            <h1 class="panel-title">Subscribe with PayPal</h1>
                        </div>
                        <div class="form-group">
                        <label for="subscr_plan"><h3>Select Subscription Plan:</h3></label>
                        <select id="subscr_plan" class="form-select form-select-lg mb-3 custom-select" aria-label="Default select example">
                            <?php
                            if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $interval = $row['interval'];
                                $interval_count = $row['interval_count'];
                                $interval_str = ($interval_count > 1) ? $interval_count . ' ' . $interval . 's' : $interval;
                                ?>
                                <option value="<?php echo $row['id']; ?>">
                                <?php echo $row['name'] . ' [$' . $row['price'] . '/' . $interval_str . ']'; ?>
                                </option>
                            <?php
                            }
                            }
                            ?>
                        </select>
                        </div>

                        <div id="paymentResponse" class="hidden"></div>

                        <div id="paypal-button-container"></div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    paypal.Buttons({
        createSubscription: async (data, actions) => {

            // Get the selected plan ID
            let subscr_plan_id = document.getElementById("subscr_plan").value;
            
            // Send request to the backend server to create subscription plan via PayPal API
            let postData = {request_type: 'create_plan', plan_id: subscr_plan_id};
            const PLAN_ID = await fetch("paypal_checkout_init.php", {
                method: "POST",
                headers: {'Accept': 'application/json'},
                body: encodeFormData(postData)
            })
            .then((res) => {
                return res.json();
            })
            .then((result) => {
                if(result.status == 1){
                    return result.data.id;
                }else{
                    resultMessage(result.msg);
                    return false;
                }
            });

            // Creates the subscription
            return actions.subscription.create({
                'plan_id': PLAN_ID,
                'custom_id': '<?php echo $loggedInUserID; ?>'
            });
        },
        onApprove: (data, actions) => {

            // Send request to the backend server to validate subscription via PayPal API
            var postData = {request_type:'capture_subscr', order_id:data.orderID, subscription_id:data.subscriptionID, plan_id: document.getElementById("subscr_plan").value};
            fetch('paypal_checkout_init.php', {
                method: 'POST',
                headers: {'Accept': 'application/json'},
                body: encodeFormData(postData)
            })
            .then((response) => response.json())
            .then((result) => {
                if(result.status == 1){
                    // Redirect the user to the status page
                    window.location.href = "payment-status.php?checkout_ref_id="+result.ref_id;
                }else{
                    resultMessage(result.msg);
                }
            })
            .catch(error => console.log(error));
        }
    }).render('#paypal-button-container');

    // Helper function to encode payload parameters
    const encodeFormData = (data) => {
    var form_data = new FormData();

    for ( var key in data ) {
        form_data.append(key, data[key]);
    }
    return form_data;   
    }



    // Display status message
    const resultMessage = (msg_txt) => {
        const messageContainer = document.querySelector("#paymentResponse");

        messageContainer.classList.remove("hidden");
        messageContainer.textContent = msg_txt;
        
        setTimeout(function () {
            messageContainer.classList.add("hidden");
            messageContainer.textContent = "";
        }, 5000);
    }    
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>


