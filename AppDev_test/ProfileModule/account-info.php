<?php
    $sqlQ = "SELECT S.*, P.name as plan_name, P.price as plan_price, P.interval, P.interval_count FROM user_subscriptions as S LEFT JOIN plans as P ON P.id=S.plan_id WHERE user_id = $business_owner";
    $stmt = $conn->prepare($sqlQ);
    $stmt->execute();
    $result = $stmt->get_result();
    $subscr_data = $result->fetch_assoc();
?>
        <div class="justify-content-center align-items-center text-center">
            <div class="col-12">
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
                        <li class="list-group-item"><b>Amount:</b> <?php echo $subscr_data['plan_price'] . ' ' . $subscr_data['currency_code']; ?></li>
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
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-lg mt-3 btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Cancel Subscription
            </button>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Cancel Subscription?</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to cancel your subscription?</p>
                            <p>(Once log-out you need to subscribe again to manage your acc)</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" id="unSub" data-bs-dismiss="modal">Yes</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>      
        <script>
            const updateButton = document.getElementById('unSub');
            updateButton.addEventListener('click', function() {
                
                const newValue = "CANCELLED";
                const recordId = <?php echo $business_owner?>;
                
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'update.php'); 
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

               
                xhr.send(`newValue=${newValue}&recordId=${recordId}`);
              
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        console.log("Database updated successfully!");
                        Swal.fire({
                            title: 'Success!',
                            text: 'Subscription has been canceled!',
                            icon: 'success'
                        })
                    } else {
                        console.error("Error updating database:", xhr.statusText);
                    }
                };
            });

            
        </script>  


