let headersList = {
    "Content-Type": "application/json",
    "Prefer": "return=minimal",
    "PayPal-Request-Id": "Plan-01",
    "Authorization": "Basic QWJkbFN6WjBKNzBvcFhLY1BpeWNCcTNHYTd1SHB2dWM4U0p6RmFNc3dMdC10S0Zrc0RFS3B6cXd4V1BPOFZoZjUzSVZMY3pGRm1ISzZBbGg6RUV6MEE5Yjh1Q0xtZW95Z3JwcHZHcDY3SGhSVi1FZVVxMlRlSlZVOEM4Q1lINm1rdzM0YmIxY0dmMnZNQWVtQ3RRckRqUlQ3WTdhT2dGSmQ="
   }
   
   let bodyContent = JSON.stringify({
     "product_id": "PROD-76R759866A8186604",
     "name": "Advertisement Plan",
     "status": "ACTIVE",
     "decription": "1 Month Free Trial Advertisement Plan",
     "billing_cycles":[
         {
           "frequency":{
             "interval_unit": "MONTH",
             "interval_count": 1
           },
           "tenure_type": "TRIAL",
           "sequence": 1,
           "total_cycles": 2,
           "pricing_scheme": {
             "fixed_price": {
               "value": "3",
               "currency_code": "USD"
             }
           }
         },
         {
           "frequency": {
             "interval_unit": "MONTH",
             "interval_count": 1
           },
           "tenure_type": "REGULAR",
           "sequence": 2,
           "total_cycles": 12,
           "pricing_scheme": {
             "fixed_price": {
               "value": "20",
               "currency_code": "USD"
             }
           }
         }
       ],
       "payment_preferences": {
         "auto_bill_outstanding": true,
         "setup_fee": {
           "value": "20",
           "currency_code": "USD"
         },
         "setup_fee_failure_action": "CONTINUE",
         "payment_failure_threshold": 3,
         "pay_method_selection": "PAYPAL"
       },
         "taxes": {
         "percentage": "10",
         "inclusive": false
     }
   });
   
   async function createPlan(){
    let response = await fetch("https://api-m.sandbox.paypal.com/v1/billing/plans", { 
     method: "POST",
     body: bodyContent,
     headers: headersList
    });

    let data = await response.text();
    console.log(data);
   }
   
   createPlan();
   