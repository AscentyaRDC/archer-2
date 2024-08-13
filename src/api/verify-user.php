<?php
require('./conn.php');


$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array
$email = $input["email"];
$mailOtp = $input["mailOtp"];
$phoneOtp = $input["phoneOtp"];


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Method Not Allowed";
}
else{
    $sql = "SELECT * FROM `user` WHERE `mail_otp`='$mailOtp' and `mobile_otp`='$phoneOtp' and `email`='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $t = 0;
        // output data of each row
       

        while($row = $result->fetch_assoc()) {
            
            $temp = $row["uid"];
            $sql1 = "UPDATE `user` SET `u_access` = 1 WHERE `uid`='$temp'";
            if($conn->query($sql1)==TRUE){
                
                $t += 1;
                $res["success"] = true;
                $res["msg"] = "User Activated successfully";
                $res["data"] = [];
                
                $out = json_encode($res);
                echo $out;
            }
            else{
                $res ["success"] = false;
                $res ["data"] = [];
                $res ["msg"] = "Error: " . $sql1 . "<br>" . $conn->error . "<br>";

                $out = json_encode($res);
                echo $out;
            }
        }
    
        if($t==0){
            $res["success"] = false;
            $res["msg"] = "Invalid OTP";
            $res["data"] = [];

            $out = json_encode($res);

            echo $out;
        }
    }
    else{
        $res["success"] = false;
        $res["msg"] = "Invalid OTP. Please try again.";
        $res["data"] = [];

        $out = json_encode($res);
        echo $out;
    }
}

$conn->close();

?>