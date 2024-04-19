<?php
    include('../navbars/viewer-homepage.php');

?>


<script src="https://www.paypal.com/sdk/js?client-id=AbdlSzZ0J70opXKcPiycBq3Ga7uHpvuc8SJzFaMswLt-tKFksDEKpzqwxWPO8Vhf53IVLczFFmHK6Alh&vault=true&components=buttons"></script>
    
<div id="subscriber-name"></div>
<div>
    <div class="d-flex justify-content-center align-items-center">
        <div class="row justify-content-center align-items-center">
            <div class=" col-xl-6 col-12  container-fluid justify-content-center align-items-center mt-5 rounded-5 bg-white shadow box-area p-5">
                <form class="row w-100 g-3" action="" method="post" enctype="multipart/form-data">
                    <h3>Subscription Type</h3>
                    <div class="btn-group" role="group" aria-label="Basic radio toggle button group" id="subscriptionPlans">
                    </div>
                    <h3 class="mt-3">Payment information</h3>
                    <div class="col-12">
                        <div id="paypal-button-container"></div>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>

<form id="paypal_form" action="">
    <input id="paypal_subscription" type="hidden" name="paypal_subscription">
</form>

<script>
       paypal.Buttons({
        createSubscription: function(data, actions) {
        var planId = document.querySelector('.paypal-subscription:checked').value;
          return actions.subscription.create({
           'plan_id': planId // Creates the subscription
           });
         },
         onApprove: function(data, actions) {
           alert('You have successfully subscribed to ' + data.subscriptionID); // Optional message given to subscriber
           // todo: what to do after successful subscription
           const input = document.getElementById('paypal_subscription');
           const paypalForm = document.getElementById('paypal_form');
           console.log(data.subscriptionID);
           input.value = true;
           paypalForm.submit();

         }
       }).render('#paypal-button-container'); // Renders the PayPal button

       const handleApproved = async (data) => {
        const payload = {
            subscriptionId: data.subscriptionID,
            expiration: data.expiry,
        };
        const response = await fetch('/payment-approved.php', {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
                // 'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: JSON.stringify(payload),
        })
       } 

       let headers = {
    "Content-Type": "application/json",
    "Authorization": "Basic QWJkbFN6WjBKNzBvcFhLY1BpeWNCcTNHYTd1SHB2dWM4U0p6RmFNc3dMdC10S0Zrc0RFS3B6cXd4V1BPOFZoZjUzSVZMY3pGRm1ISzZBbGg6RUV6MEE5Yjh1Q0xtZW95Z3JwcHZHcDY3SGhSVi1FZVVxMlRlSlZVOEM4Q1lINm1rdzM0YmIxY0dmMnZNQWVtQ3RRckRqUlQ3WTdhT2dGSmQ="
    }

</script>

<script>
    let headersList = {
        "Accept": "application/json",
        "Content-Type": "application/json",
        "Authorization": "Basic QWJkbFN6WjBKNzBvcFhLY1BpeWNCcTNHYTd1SHB2dWM4U0p6RmFNc3dMdC10S0Zrc0RFS3B6cXd4V1BPOFZoZjUzSVZMY3pGRm1ISzZBbGg6RUV6MEE5Yjh1Q0xtZW95Z3JwcHZHcDY3SGhSVi1FZVVxMlRlSlZVOEM4Q1lINm1rdzM0YmIxY0dmMnZNQWVtQ3RRckRqUlQ3WTdhT2dGSmQ="
    }

    async function getPlans() {
        let response = await fetch("https://api-m.sandbox.paypal.com/v1/billing/plans", { 
            method: "GET",
            headers: headersList
        });

        let data = await response.json();
        const plansContainer = document.getElementById('subscriptionPlans');
        
        data.plans.forEach(plan => {
            if (plan.status=='ACTIVE'){
                plansContainer.insertAdjacentHTML('beforeend', `<input type="radio" class="btn-check paypal-subscription" name="btnradio" id="${plan.id}" autocomplete="off" value="${plan.id}" checked>
                <label class="btn btn-outline-primary" for="${plan.id}">${plan.name}</label>`)
            }
        });
    }

    getPlans();
</script>



<?php
    include('../navbars/footer.php')
?>