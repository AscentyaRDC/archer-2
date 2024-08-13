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
        $data = array();
        $sql = "SELECT * FROM `feedback`";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                    // $row["image_url"] = $base . substr($row["image_url"],1);
                    array_push($data,$row);
            }
            $res["success"] = true;
            $res["msg"] = "List of Feedbacks";
            $res["data"] = $data;

            $out = json_encode($res);

            echo $out;

        }
        else{

            $res["success"] = false;
            $res["msg"] = "no feedback found";
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