<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './phpmailer/PHPMailer/src/Exception.php';
require './phpmailer/PHPMailer/src/PHPMailer.php';
require './phpmailer/PHPMailer/src/SMTP.php';

//Load Composer's autoloader
require 'C:\xampp\phpMyAdmin\vendor\autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);
$temp = 0;

try {
    //Server settings
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->SMTPDebug = 0;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'mail.ascentya.in';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'sjk@ascentya.in';                     //SMTP username
    $mail->Password   = '';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
    // $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('sjk@ascentya.in', 'Technical Head');
    // $mail->addAddress('jeevasasikumar87@gmail.com', 'Jeeva S');     //Add a recipient
    $mail->addAddress('kamalesh.k@ascentya.in');               //Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC('sjk@ascentya.in');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Reset Password Link';
    $mail->Body    = 'As per your request you can able to reset your password in the below link:'.
    '<br><a href="https://www.techbylanz.com/archer/src/login.html?resetToken=1233y8ckdnjbzccgyu3ecjbscb37ruygs">Click Here to Reset Password</a>'.
    '<br><b>Note: The link is valid only for 20 mins from the mail received</b>'.
    '<br><br><br>Thanks & Regards,<br>Archer Team. <br>Better Environment, Better Tomorrow.';
    $mail->AltBody = 'Reset Link Mail';

    $mail->send();

    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}