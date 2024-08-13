<?php
require('./conn.php');


$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array
$mobile = $input["mobile"];
$password = $input['password'];


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Method Not Allowed";
}
else{
    $sql = "SELECT * FROM `user` WHERE `u_access` = '1'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $t = 0;
        // output data of each row
        while($row = $result->fetch_assoc()) {
            if(($row["mobile"]==$mobile || $row["email"]==$mobile || $row["user_id"]==$mobile) && $row["password"]==$password){


                $access_token = md5(uniqid().rand(1000000, 9999999));
                
                $temp = $row["uid"];
                $sql1 = "UPDATE `user` SET `token`='$access_token' WHERE `uid`='$temp'";
                if($conn->query($sql1)==TRUE){
                    unset($row["password"]);
                    unset($row["uid"]);
                    $t += 1;
                
                    $row["token"] = $access_token;
                    $arr =array($row);
    
                    $res["success"] = true;
                    $res["msg"] = "login successfully";
                    $res["data"] = $arr;
                    
    
                    $out = json_encode($res);
                    echo $out;
                }
                else{
                    $res ["success"] = false;
                    $res ["data"] = [];
                    $res ["msg"] = "Error: " . $sql1 . "<br>" . $conn->error . "<br>";

                    $out = json_encode($res);
                    echo $out;
                }
                
            }
        }
        if($t==0){
            $res["success"] = false;
            $res["msg"] = "Invalid Credentials";
            $res["data"] = [];

            $out = json_encode($res);

            echo $out;
        }
    }
    else{
        $res["success"] = false;
        $res["msg"] = "no user found";
        $res["data"] = [];

        $out = json_encode($res);

        echo $out;
    }
}

$conn->close();

?>