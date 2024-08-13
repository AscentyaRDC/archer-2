<?php

require("./conn.php");
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array
$token = $input["token"];

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Method Not Allowed";
}
else{
    $sql = "SELECT `token` FROM `user` WHERE `token` = '$token'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
    
        $sql = "SELECT * FROM `tbl_states`";
        $result = $conn->query($sql);
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $res["success"] = true;
        $res["msg"] = "List of States";
        $res["data"] = $data;

        $out = json_encode($res);

        echo $out;
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