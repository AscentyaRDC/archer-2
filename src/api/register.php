<?php
require('./conn.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './phpmailer/PHPMailer/src/Exception.php';
require './phpmailer/PHPMailer/src/PHPMailer.php';
require './phpmailer/PHPMailer/src/SMTP.php';
$mail = new PHPMailer(true); //create mailer

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array

$name = $input["name"];
$mobile = $input['mobile'];
$email = $input['email'];
$password = $input['password'];
$userId = $input['userId'];


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Method Not Allowed";
}
else{
  $sql = "SELECT `mobile`,`email`,`user_id` FROM `user`";
  $result = $conn->query($sql);
  $temp = 0;
  $repeatValue = "";
  if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      if($row["mobile"]==$mobile){
        $temp += 1;
        $repeatValue = "Mobile Number";
        break;
      }
      if($row["user_id"]==$userId){
        $temp += 1;
        $repeatValue = "User ID";
        break;
      }
      if($row["email"]==$email){
        $temp += 1;
        $repeatValue = "E-Mail Address";
        break;
      }
    }
  }
  if($temp==0) {
    $maliOtp = rand(1111,9999);
    $phoneOtp = 1234;
    $sql = "INSERT INTO `user`( `user_id`, `u_name`, `email`, `mobile`, `password`, `u_type`, `u_access`,`mobile_otp`,`mail_otp`)
     VALUES ('$userId','$name','$email','$mobile','$password',1,0,'$phoneOtp','$maliOtp')";

    if ($conn->query($sql) == TRUE) {
      try {
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
        $mail->Subject = 'Archer Mail Verification';
        $mail->Body    = 'Hi '.$name. ', Greetings from Archer Pvt. Ltd. Your Registration is highly appreciated. Your requested E-mail OTP is : '.$maliOtp.
        '<br><br><br>Thanks & Regards,<br>Archer Team. <br>Better Environment, Better Tomorrow.';
        $mail->AltBody = 'Verification Mail';
        $mail->send();

       
        $res["success"] = true;
        $res["msg"] = "User Created Successfully, Please Enter Otp to Continue";
        $res["data"] = [];


        $out = json_encode($res);
        echo $out;
      } catch ( Exception $e) {
        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        $res["success"] = false;
        $res["msg"] = "something went wrong. please try again later.";
        $res["data"] = [];

        $out = json_encode($res);
        echo $out;
      }
    }

  }
  else{
    $res ["success"] = false;
    $res ["data"] = [];
    $res ["msg"] = $repeatValue . " Already Exists";
    $out = json_encode($res);
    echo $out;
  }
}

$conn->close();

?>
