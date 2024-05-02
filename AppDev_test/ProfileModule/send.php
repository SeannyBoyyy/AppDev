<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
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
        $mail->Subject = 'FROM ZAMBALES LOCAL MARKET - DO NOT REPLY...     ' . '     From -     ' . '     Email:     ' . $_POST['my_email'] . "     " . '     Contact Number:     ' . $_POST['my_number'];
        $mail->Body    = $message;

        // Send email
        $mail->send();
        echo"...";
        echo "
            <script>
                Swal.fire({
                title: 'Success!',
                text: 'Message has been sent!',
                icon: 'success'
            }).then(function() {
                window.location = 'profile-page.php?active=messages';
            });
            </script>";
        
    } catch (Exception $e) {
        echo"...";
        echo "
            <script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Message could not be sent. Mailer Error: {$mail->ErrorInfo}',
                    icon: 'error'
                }).then(function() {
                    window.location = 'profile-page.php?active=messages';
                });
            </script>";
        
            exit();
    }
}
?>
