<?php
    include('../navbars/viewer-homepage.php');
?>
<div id="subscriber-name">
    
</div>
<script>

    let headersList = {
    "Content-Type": "application/json",
    "Authorization": "Basic QWJkbFN6WjBKNzBvcFhLY1BpeWNCcTNHYTd1SHB2dWM4U0p6RmFNc3dMdC10S0Zrc0RFS3B6cXd4V1BPOFZoZjUzSVZMY3pGRm1ISzZBbGg6RUV6MEE5Yjh1Q0xtZW95Z3JwcHZHcDY3SGhSVi1FZVVxMlRlSlZVOEM4Q1lINm1rdzM0YmIxY0dmMnZNQWVtQ3RRckRqUlQ3WTdhT2dGSmQ="
    }

    async function subdeets(){
        let response = await fetch("https://api-m.sandbox.paypal.com/v1/billing/subscriptions/I-UDUYH00E5XNF", { 
            method: "GET",
            headers: headersList
        });

        let data = await response.json();
        console.log(data.subscriber.name);
        const subsrbrName = document.getElementById('subscriber-name');
        if (data.subscriber && data.subscriber.name) {
        subsrbrName.insertAdjacentHTML("beforeend", `<h1>${data.subscriber.name.given_name} ${data.subscriber.name.surname}</h1>`)
        } else {
        console.warn("Subscriber name not found in data");
        }

    }
    subdeets();

</script>



<?php
    include('../navbars/footer.php');
?>


