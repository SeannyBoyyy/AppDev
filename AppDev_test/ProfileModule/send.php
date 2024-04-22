<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php'; 
require 'phpmailer/src/SMTP.php'; 

// Check if form is submitted
if(isset($_POST['sendMSG'])) {
    // Get recipient email and message from the form
    $recipient = $_POST['to_sent'];
    $message = $_POST['message'];

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'seamdesagun@gmail.com'; // your gmail 
        $mail->Password = 'hfyqnninbpvfdpfk'; // your gmail password
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        //Recipients
        $mail->setFrom('seamdesagun@gmail.com'); // your gmail   
        $mail->addAddress($recipient); // Add recipient email

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'FROM ZAMBALES LOCAL MARKET - DO NOT REPLY'; // + add new line for the text from $email and $
        $mail->Body    = $message;

        // Send email
        $mail->send();
        echo "<script> 
                alert('Message has been sent');
                window.location.replace('profile-page.php');
            </script>";
        
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
