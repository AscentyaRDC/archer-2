<?php

require("./conn.php");

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array

$token = $input["token"];
$currPassword = $input["currentPassword"];
$newPassword = $input["newPassword"];

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Method Not Allowed";
}
else{
    $tempId = 0;
    $getData = "SELECT * from `user`";
    $result = $conn->query($getData);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
                if($row["password"]==$currPassword && $row["token"]==$token){
                    $tempId = $row["uid"];
                }
        }
        if($tempId == 0){
            $res["success"] = false;
            $res["msg"] = "Please check the current password. Passwords not match";
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