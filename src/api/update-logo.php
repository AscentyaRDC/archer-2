<?php 
require('./conn.php');
$token = $_POST["token"];
$image_front_name = $_FILES["logo"]["name"];
$image_front_tmp_name = $_FILES["logo"]["tmp_name"];

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Method Not Allowed";
}
else{
    $sql = "SELECT `token` FROM `user` WHERE `token` = '$token'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $folder_front = "../assets/images/logos/" . "favicon.png";
        if (move_uploaded_file($image_front_tmp_name,$folder_front)) {
            $res ["success"] = true;
            $res ["data"] = [];
            $res ["msg"] = "Logo updated successfully";  

            $out = json_encode($res);
            echo $out;
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
        $res["success"] = false;
        $res["msg"] = "Not a Valid token";
        $res["data"] = [];

        $out = json_encode($res);
        echo $out;
    }
}

?>