<?php 

define('PAYPAL_SANDBOX', TRUE); //TRUE=Sandbox | FALSE=Production 
define('PAYPAL_SANDBOX_CLIENT_ID', 'AShgmK15SmjAg1vKUORlT_DThBVAef2Dtnmz4Q92q5OI-HPtMsd1FM4zl-svWMH_EakIe9QF0A4OdcSE'); 
define('PAYPAL_SANDBOX_CLIENT_SECRET', 'EBzk47VgqjRqlR-Mj48oe4eRURA_UqMHUuuM0oUiYnvyoh1wuxpbxNjRkD37_pol5Q4P0puRuWnZLoJW'); 
define('PAYPAL_PROD_CLIENT_ID', 'AVGcKndr4QhBmwE3Cic0xoutALeeZniBPPUrcEPBqfVJyFUvliFaFo1r-tz1s2HdE7pdjwNJIRTPBerq'); 
define('PAYPAL_PROD_CLIENT_SECRET', 'EAQeamP7AypLuSadayYonClDXNlijXn4EnwCf-NYHllY2t5N9X34S0ZWKQirgI7OV-8yMpDGXdfQZEbB'); 
 
define('CURRENCY', 'USD');  
 
// Database configuration  
define('DB_HOST', 'localhost'); 
define('DB_USERNAME', 'dan'); 
define('DB_PASSWORD', 'test1234'); 
define('DB_NAME', 'product_promotion'); 
 
 
// Start session 
if(!session_id()){ 
    session_start(); 
}  
 