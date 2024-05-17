<?php 
    include('../navbars/subs-navbar.php');
    
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

                        <!-- Button for GCash modal -->
                        <button type="button" class="btn btn-secondary mt-3" data-bs-toggle="modal" data-bs-target="#gcashModal">
                            Pay with GCash
                        </button> 

                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for GCash payment -->
    <div class="modal fade" id="gcashModal" tabindex="-1" aria-labelledby="gcashModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gcashModalLabel">Pay with GCash</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- GCash payment instructions -->
                    <p>Please scan the QR code below or transfer the amount to the provided GCash number.</p>
                    <!-- Insert QR code image or GCash number here -->
                    <img src="gcash_qr_code.jpg" alt="GCash QR Code" class="img-fluid">
                    <p>GCash Number: 09171025899</p>
                    
                    <!-- Form for uploading GCash receipt -->
                    <form action="gcash_checkout_init.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="loggedInUserID" value="<?php echo $loggedInUserID; ?>">
                        <div class="mb-3">
                            <label for="gcashReceipt" class="form-label">Upload GCash Receipt</label>
                            <input type="file" class="form-control" id="gcashReceipt" name="gcashReceipt" accept="image/*" required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit Receipt</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <!-- Add additional buttons if needed -->
                </div>
                    </form>
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


