<?php
include('../config/connectDb.php');
session_start();
if(isset($_SESSION['ownerID'])){
    $business_owner = $_SESSION['ownerID']; 
}else{
    echo 'no owner ';
    header('Location: ../AccPages/login-page.php');
    exit();
}
    $newValue = $_POST['newValue'];
    $sqlQ = "UPDATE user_subscriptions SET status = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sqlQ);
    $stmt->bind_param("si", $newValue, $business_owner);
    $stmt->execute();
    if ($stmt->execute()) {
        echo "Database updated successfully";  
    } else {
        echo "Error updating database: " . $conn->error;  
    }
    
    $stmt->close();
    $conn->close();
?>