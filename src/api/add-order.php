<?php
require('./conn.php');

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array


$wid = $input["wid"];
$price = $input["price"];
$cgst = $input["cgst"];
$sgst = $input["sgst"];
$userId = $input["userId"];
$wfid = $input["wfid"];
$qty = $input["qty"];
$token = $input["token"];

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Method Not Allowed";
}
else {
    $sql = "SELECT `token`,`shipping_address` FROM `user` WHERE `token` = '$token'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $t=date('Y-m-d h:i:s', time());
        $tes = $result->fetch_assoc();
        $address = $tes["shipping_address"];

        $sql = "INSERT INTO `orders`( `watch_id`, `price`, `cgst`, `sgst`, `user_id`, `watch_face_id`, `qty`, `status`, `shipping_address`)
         VALUES ('$wid','$price','$cgst','$sgst','$userId','$wfid','$qty','ordered','$address')";
    
        if($conn->query($sql)===TRUE){
            $res["success"] = true;
            $res["msg"] = "Order Added Successfully";
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