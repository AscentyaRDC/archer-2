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
        $sql = "SELECT * FROM `watch`";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {  
                    $row["image_front"] = $base. substr($row["image_front"],1);
                    $row["image_back"] = $base. substr($row["image_back"],1);
                    $row["image_left"] = $base. substr($row["image_left"],1);
                    $row["image_right"] = $base. substr($row["image_right"],1);
                    $row["image_up"] = $base. substr($row["image_up"],1);
                    $row["image_down"] = $base. substr($row["image_down"],1);
                    $row["combination"] = json_decode($row["combination"]);
                    $row["user_fav"] = json_decode($row["user_fav"]);
                    // if($row["is_available"] > 0)
                        array_push($data,$row);       
            }
            $res["success"] = true;
            $res["msg"] = "List of watches";
            $res["data"] = $data;

            $out = json_encode($res);

            echo $out;

        }
        else{

            $res["success"] = false;
            $res["msg"] = "no watches found";
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