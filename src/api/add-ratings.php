<?php
require('./conn.php');

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array


$token = $input["token"];
$rating = $input["rating"];

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Method Not Allowed";
}
else {
    $sql = "SELECT `u_name`,`mobile`,`token` FROM `user` WHERE `token` = '$token'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $tes = $result->fetch_assoc();
        $name = $tes["u_name"];
        $mobile = $tes["mobile"];
        $sql = "SELECT * FROM `ratings` WHERE `mobile` = '$mobile'";
        $t=date('Y-m-d h:i:s', time());
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $sql = "UPDATE `ratings` SET `name`='$name',`rating`='$rating',`updated_at`='$t' WHERE `mobile` = '$mobile'";
    
            if($conn->query($sql)===TRUE){
                $res["success"] = true;
                $res["msg"] = "Ratings Updated Successfully";
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
            $sql = "INSERT INTO `ratings`(`name`,`mobile`, `rating`) VALUES ('$name','$mobile','$rating')";
    
            if($conn->query($sql)===TRUE){
                $res["success"] = true;
                $res["msg"] = "Ratings Added Successfully";
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