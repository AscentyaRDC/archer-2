<?php

require("./conn.php");

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array

$token = $input["token"];
$email = $input["email"];
$newPassword = $input["newPassword"];

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Method Not Allowed";
}
else{
    $tempId = 0;
    $token = md5($email . '/Archer' . '/Jeva mail');
    $t=date('Y-m-d h:i:s', time());
    $getData = "SELECT * from `user`";
    $result = $conn->query($getData);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {

                if($row["email"]==$email && $row["reset_token"]==$token){
                    $tokenTime = $row["reset_time"];
                    $seconds = strtotime($t) - strtotime($tokenTime);
                    $days    = floor($seconds / 86400);
                    $hours   = floor(($seconds - ($days * 86400)) / 3600);
                    $minutes = floor(($seconds - ($days * 86400) - ($hours * 3600))/60);
                    // echo "$minutes";
                    // die();
                    if($minutes<=20)
                        $tempId = $row["uid"];
                    else{
                        $res["success"] = false;
                        $res["msg"] = "Reset Link Expired";
                        $res["data"] = [];
                        $out = json_encode($res);
                        echo $out;
                        die();  
                    }
                }
        }
        if($tempId == 0){
            $res["success"] = false;
            $res["msg"] = "Authentication failed. Please enter valid credentials";
            $res["data"] = [];
            $out = json_encode($res);
            echo $out;
            die();
        }

    }
    else{

        $res["success"] = false;
        $res["msg"] = "no user found. Please Signup an account.";
        $res["data"] = [];

        $out = json_encode($res);

        echo $out;
        die();
    }


    $sql = "UPDATE `user` SET `password`='$newPassword' WHERE `uid`='$tempId'";
    if ($conn->query($sql) === TRUE) {
        $res ["success"] = true;
        $res ["data"] = [];
        $res ["msg"] = "Password changed Successfully";
    
        $out = json_encode($res);
        echo $out;
        
           
       } else {
        $res ["success"] = false;
        $res ["data"] = [];
        $res ["msg"] = "Error: " . $sql . "<br>" . $conn->error . "<br>";
    
        $out = json_encode($res);
        echo $out;
       }
}


$conn->close();

?>