<?php

require('./conn.php');

$dial_id = $_POST["dial_id"];
$strap_id = $_POST["strap_id"];
$chronocase_id = $_POST["chronocase_id"];
$movement_id = $_POST["movement_id"];
$bezel_id = $_POST["bezel_id"];
$hands_id = $_POST["hands_id"];
$seconds_id = $_POST["seconds_id"];
$crown_id = $_POST["crown_id"];
$caseback_id = $_POST["caseback_id"];
$wid = $_POST["wid"];
$price = $_POST["price"];
$token = $_POST["token"];

$image_front_name = $_FILES["image_front"]["name"];
$image_front_tmp_name = $_FILES["image_front"]["tmp_name"];

$created_by = $_POST["user_id"];
$folder_front = "./image/watchface/" . $image_front_name;


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Method Not Allowed"; 
}
else{

    $sql = "SELECT `token`,`uid` FROM `user` WHERE `token` = '$token'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $res1 =  $result->fetch_assoc();
        $uid = $res1["uid"];
       
        $sql = "INSERT INTO `watch_face`( `wid`,`dial_id`, `strap_id`, `bezel_id`, `caseback_id`, `chronocase_id`,
         `crown_id`, `hand_id`, `second_id`, `movement_id`, `image_front`, `image_back`, `image_up`, `image_down`,
          `image_left`, `image_right`, `created_by`,`watch_face_name`,`price`,`description`,
          `dial_size`, `color`, `weight`, `body_type`, `brand`, `batteries_included`, 
            `year_of_manufacture`, `warranty_period`, `item_code`, `display_type`, `water_resistant`,
             `ip_rating`, `shape`, `inside_the_box`, `delivery_fee` 
          ) VALUES ('$wid','$dial_id','$strap_id','$bezel_id','$caseback_id','$chronocase_id','$crown_id',
          '$hands_id','$seconds_id','$movement_id','$folder_front','$folder_back'
          ,'$folder_up','$folder_down','$folder_left','$folder_right','$uid',
          '$watchFaceName','$price','$description',
          '$dialSize','$color','$weight','$bodyType','$brand','$batteriesIncluded','$yearOfManufacture',
            '$warrantyPeriod','$itemCode','$displayType','$waterResistant','$ipRating','$shape','$inside','$deliveryFee'
            )";
        
            if ($conn->query($sql) === TRUE) {
        
                $folderArr = array($folder_front, $folder_back, $folder_up,$folder_down,$folder_left,$folder_right);
                $nameArr = array($image_front_tmp_name,$image_back_tmp_name,$image_up_tmp_name,$image_down_tmp_name,$image_left_tmp_name,
                $image_right_tmp_name);
        
                $count = count($folderArr);
                $added_file = 0;
                for($i=0;$i < $count;$i++){
        
                    if (move_uploaded_file($nameArr[$i],$folderArr[$i] )) {
                    
                        $added_file += 1;
                    } 
                    else{
                        echo $added_file;
                    }
                }
        
                if($added_file == 6){
                    $t = 0;
                    $sql = "SELECT * FROM `watch_face`";
                    $data =array();
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                                if($t == $result->num_rows-1)
                                    array_push($data,$row);
                                $t += 1;
                        }
                        $res ["success"] = true;
                        $res ["data"] = $data;
                        $res ["msg"] = "watch face added successfully";
                    
                        $out = json_encode($res);
                        echo $out;
                    }

                }
                else{
                    $res ["success"] = false;
                    $res ["data"] = [];
                    $res ["msg"] = "something went wrong";
        
                    $out = json_encode($res);
                    echo $out;
                }
        
            }
            else{
                $res ["success"] = false;
                $res ["data"] = [];
                $res ["msg"] = "Error: " . $sql . "<br>" . $conn->error . "<br>";
            
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