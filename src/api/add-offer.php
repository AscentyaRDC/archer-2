<?php
require('./conn.php');

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array

$token = $input["token"];
$offerCode = $input["offerCode"];
$offerDesc = $input["offerDescription"];
$offerType = $input["offerType"];
$offerValue = $input["offerValue"];
$validFrom = $input["validFrom"];
$validTo = $input["validTo"];


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Method Not Allowed";
}
else {
    $sql = "SELECT `token` FROM `user` WHERE `token` = '$token'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
       
        $sql = "INSERT INTO `offers`( `offer_code`, `offer_description`, `offer_type`, `offer_value`, 
        `offer_valid_from`, `offer_valid_to`) VALUES 
        ('$offerCode','$offerDesc','$offerType','$offerValue','$validFrom','$validTo')";
    
        if($conn->query($sql)===TRUE){
            $res["success"] = true;
            $res["msg"] = "Offer Added Successfully";
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