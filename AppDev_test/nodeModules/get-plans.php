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
   console.log(data);
   }

   getPlans();
</script>