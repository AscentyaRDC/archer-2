<?php
require('./conn.php');

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array

$token = $input["token"];
$orderId = $input["orderId"];
$orderStatus = $input["orderStatus"];
$remarks = $input["remarks"];

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Method Not Allowed";
}
else {
    $sql = "SELECT `token` FROM `user` WHERE `token` = '$token'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $t=date('Y-m-d h:i:s', time());

        $status = 'Ordered';
        if($orderStatus == 2)
            $status = 'Dispatched';
        if($orderStatus == 3)
            $status = 'Out for delivery';
        if($orderStatus == 4)
            $status = 'Delivered';
        if($orderStatus == 5)
            $status = 'Returned';

        $sql = "UPDATE `orders` SET `status`='$status',`remarks`='$remarks',`updated_at`='$t' WHERE `order_id` = '$orderId'";
    
        if($conn->query($sql)===TRUE){
            $res["success"] = true;
            $res["msg"] = "Order Updated Successfully";
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