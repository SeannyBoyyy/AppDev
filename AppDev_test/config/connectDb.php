<?php
//connect database 
$conn = mysqli_connect('localhost', 'dan' , 'test1234', 'product_promotion');

//validation if database is not connected
if(!$conn){
    echo 'Connection error: ' . mysqli_connect_error();
}
?>