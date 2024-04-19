let headersList = {
    "Accept": "application/json",
    "Accept": "application/json",
    "PayPal-Request-Id": "SubMonthly-01",
    "Authorization": "Basic QWJkbFN6WjBKNzBvcFhLY1BpeWNCcTNHYTd1SHB2dWM4U0p6RmFNc3dMdC10S0Zrc0RFS3B6cXd4V1BPOFZoZjUzSVZMY3pGRm1ISzZBbGg6RUV6MEE5Yjh1Q0xtZW95Z3JwcHZHcDY3SGhSVi1FZVVxMlRlSlZVOEM4Q1lINm1rdzM0YmIxY0dmMnZNQWVtQ3RRckRqUlQ3WTdhT2dGSmQ=",
    "Content-Type": "application/json"
   }
   
   let bodyContent = JSON.stringify({
       "name": "Promotion Monthly Subscription",
       "descripton": "Subscription to promote.",
       "type": "SERVICE",
       "category": "SOFTWARE",
       "image_url": "https://example.com/streaming.jpg",
       "home_url": "https://example.com/home"
   });
   
   async function createProducts() {
    let response = await fetch("https://api-m.sandbox.paypal.com/v1/catalogs/products", { 
     method: "POST",
     body: bodyContent,
     headers: headersList
   });
   
   let data = await response.text();
   console.log(data);
   }
   
   createProducts();