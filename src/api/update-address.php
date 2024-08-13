<?php
require('./conn.php');

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array


$shipping = $input["address"];
$token = $input["token"];




if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Method Not Allowed";
}
else{
  
    $t=date('Y-m-d h:i:s', time());

    $sql = "UPDATE `user` SET `shipping_address`='$shipping' WHERE `token`='$token'";

    if($conn->query($sql)===TRUE){
        $res["success"] = true;
        $res["msg"] = "Address updated Successfully";
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
$conn->close();

?>