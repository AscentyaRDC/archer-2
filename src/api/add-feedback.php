<?php
require('./conn.php');

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array

$token = $input["token"];
$userName = $input["name"];
$userMobile = $input["phone"];
$userMail = $input["email"];
$feedback = $input["feedback"];

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Method Not Allowed";
}
else {
    $sql = "SELECT `token` FROM `user` WHERE `token` = '$token'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
       
        $sql = "INSERT INTO `feedback`( `user_name`, `user_mobile`, `user_mail`, `feedback`)
         VALUES ('$userName','$userMobile','$userMail','$feedback')";
    
        if($conn->query($sql)===TRUE){
            $res["success"] = true;
            $res["msg"] = "Feedback Added Successfully";
            $res["data"] = [];
    
            $out = json_encode($res);
            echo $out;
        }
        else{
            $res["success"] = true;
            $res["msg"] = "something went wrong";
            $res["data"] = [];
    
            $out = json_encode($res);
            echo $out;
        }
    }
    else{
        $res["success"] = false;
        $res["msg"] = "Not a Valid token";
        $res["data"] = [];

        $out = json_encode($res);
        echo $out;
    }  
}
$conn->close();

?>