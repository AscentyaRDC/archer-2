<?php
require('./conn.php');

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array

$wid = $input["watchId"];
$token = $input["token"];
$operation = $input["operation"];


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Method Not Allowed";
}
else{
    $sql = "SELECT `token`,`mobile` FROM `user` WHERE `token` = '$token'";
    $result = $conn->query($sql);
    $data = $result->fetch_assoc();
    $mobile = $data["mobile"];

    if($result->num_rows > 0){
        $sql = "SELECT `user_fav` FROM `watch` WHERE `wid` = '$wid'";
        $result = $conn->query($sql);
        $data = $result->fetch_assoc();
        $userFav = $data["user_fav"];
        $tes = array();
        if($userFav == null || $userFav == ""){
            array_push($tes,$mobile);
            // echo json_encode($tes);
        }
        else{
            if($operation == 0){
                $result = [];
                $t=json_decode($userFav,true);
                foreach ($t as $key => $value) {
                    if($value != $mobile) {
                        $result[] = $value;
                    }
                }
                $result[] = $mobile;
                $tes = $result;
            }
            else{
                $result = [];
                $t=json_decode($userFav,true);
                foreach ($t as $key => $value) {
                    if($value != $mobile) {
                        $result[] = $value;
                    }
                }
                $tes = $result;
            }
           
        }
        
       $test = json_encode($tes);

        // die();
       

        $t=date('Y-m-d h:i:s', time());
        $sql = "UPDATE `watch` SET `user_fav`='$test',`updated_at`='$t' WHERE `wid`='$wid'";
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