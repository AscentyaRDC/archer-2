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
// require 'C:\xampp\phpMyAdmin\vendor\autoload.php';

require("./conn.php");

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);
$temp = 0;


$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array

$email = $input["email"];

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Method Not Allowed";
}
else{

    try {

        $token = md5($email . '/Archer' . '/Jeva mail');
        $t=date('Y-m-d h:i:s', time());
        // echo "$token";
        $sql = "SELECT * from `user` WHERE `email`='$email'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $ql = "UPDATE `user` SET `reset_token`='$token',`reset_time`='$t' WHERE  `email`='$email'";
            if($conn->query($ql) === TRUE){
                //Server settings
                // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $mail->SMTPDebug = 0;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'mail.ascentya.in';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'sjk@ascentya.in';                     //SMTP username
                $mail->Password   = '#SubhaJeeva87';                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            
                // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
                // $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            
                //Recipients
                $mail->setFrom('sjk@ascentya.in', 'Technical Head');
                // $mail->addAddress('jeevasasikumar87@gmail.com', 'Jeeva S');     //Add a recipient
                $mail->addAddress($email);               //Name is optional
                // $mail->addReplyTo('info@example.com', 'Information');
                // $mail->addCC('sjk@ascentya.in');
                // $mail->addBCC('bcc@example.com');
            
                //Attachments
                // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
                // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            
            
                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'Reset Password Link';
                $mail->Body    = 'As per your request you can able to reset your password in the below link:'.
                '<br><a href="https://techbylanz.com/archer-new/src/admin/change-password.html?resetToken='.$token.'">Click Here to Reset Password</a>'.
                '<br><b>Note: The link is valid only for 20 mins from the mail received</b>'.
                '<br><br><br>Thanks & Regards,<br>Archer Team. <br>Better Environment, Better Tomorrow.';
                $mail->AltBody = 'Reset Link Mail';
            
                $mail->send();

                $data = array();
                $json["link"] = "https://techbylanz.com/archer-new/src/admin/change-password.html?resetToken='.$token.'";
                array_push($data,$json);
            
                $res["success"] = true;
                $res["msg"] = "reset link successfully sent to your mail. It is valid for 20 mins";
                $res["data"] = $data;



                $out = json_encode($res);
                echo $out;
            }
            else{
                $res["success"] = false;
                $res["msg"] = "something went wrong";
                $res ["error"] = "Error: " . $sql . "<br>" . $conn->error . "<br>";
                $res["data"] = [];
        
                $out = json_encode($res);
                echo $out;
                die();
            }
        }   
        else{
            $res["success"] = false;
            $res["msg"] = "no user found";
            $res["data"] = [];
    
            $out = json_encode($res);
            echo $out;
            die();
        }

        
    } catch (Exception $e) {
        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        $res["success"] = true;
        $res["msg"] = "something went wrong. please try again later.";
        $res["data"] = [];

        $out = json_encode($res);
        echo $out;
    }
}


$conn->close();

?>