<?php
require('./conn.php');

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array


$name = $input["name"];
$model = $input["model"];
$price = $input["price"];
$charges = $input["charges"];
$stock = $input["stock"];
$wid = $input["watchId"];
$token = $input["token"];
$catId = $input["catId"];
$status = $input["status"];
//newly added
$dialSize = $input["dialSize"];
$color = $input["color"];
$weight = $input["weight"];
$bodyType = $input["bodyType"];
$brand = $input["brand"];
$batteriesIncluded = $input["batteriesIncluded"];
$yearOfManufacture = $input["yearOfManufacture"];
$warrantyPeriod = $input["warrantyPeriod"];
$itemCode = $input["itemCode"];
$displayType = $input["displayType"];
$waterResistant = $input["waterResistant"];
$ipRating = $input["ipRating"];
$shape = $input["shape"];
$inside = $input["insideTheBox"];
$deliveryFee = $input["deliveryFee"];


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Method Not Allowed";
}
else{
    $sql = "SELECT `token` FROM `user` WHERE `token` = '$token'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $t=date('Y-m-d h:i:s', time());
        $sql = "UPDATE `watch` SET `cat_id`='$catId',`name`='$name',`model`='$model',`base_price`='$price',`aaditional_charges`='$charges',
        `is_available`='$stock',
        `dial_size`='$dialSize',
        `color`='$color',
        `weight`='$weight',
        `body_type`='$bodyType',
        `brand`='$brand',
        `batteries_included`='$batteriesIncluded',
        `year_of_manufacture`='$yearOfManufacture',
        `warranty_period`='$warrantyPeriod',
        `item_code`='$itemCode',
        `display_type`='$displayType',
        `water_resistant`='$waterResistant',
        `ip_rating`='$ipRating',
        `shape`='$shape',
        `inside_the_box`='$inside',
        `delivery_fee`='$deliveryFee',
        `status`='$status',
        `updated_at`='$t' WHERE `wid`='$wid'";
        if($conn->query($sql)===TRUE){
            $res["success"] = true;
            $res["msg"] = "Watch details edited successfully";
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