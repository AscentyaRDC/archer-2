<?php 

require("./conn.php");

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array

$token = $input["token"];

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $res["success"] = false;
        $res["msg"] = "Method Not Allowed";
        $res["data"] = [];

        $out = json_encode($res);
        echo $out;
}
else{
    $sql = "SELECT `token` FROM `user` WHERE `token` = '$token'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $data = array();
        $sql = "SELECT * FROM `dial`";
        $result = $conn->query($sql);
        $data["dial"] =  $result->fetch_all(MYSQLI_ASSOC);
        // $object = (object) $array_name;
        //2
        $sql = "SELECT * FROM `bezel`";
        $result = $conn->query($sql);
        $data["bezel"] = $result->fetch_all(MYSQLI_ASSOC);
        //3
        $sql = "SELECT * FROM `chronocase`";
        $result = $conn->query($sql);
        $data["chronocase"] = $result->fetch_all(MYSQLI_ASSOC);
        //4
        $sql = "SELECT * FROM `crowns`";
        $result = $conn->query($sql);
        $data["crowns"] = $result->fetch_all(MYSQLI_ASSOC);
         //5
        $sql = "SELECT * FROM `movement`";
        $result = $conn->query($sql);
        $data["movement"] = $result->fetch_all(MYSQLI_ASSOC);
        //6
        $sql = "SELECT * FROM `strap`";
        $result = $conn->query($sql);
        $data["strap"] = $result->fetch_all(MYSQLI_ASSOC);
        //7
        $sql = "SELECT * FROM `hands`";
        $result = $conn->query($sql);
        $data["hands"] = $result->fetch_all(MYSQLI_ASSOC);
        //8
        $sql = "SELECT * FROM `caseback`";
        $result = $conn->query($sql);
        $data["caseback"] = $result->fetch_all(MYSQLI_ASSOC);
        //9
        $sql = "SELECT * FROM `seconds`";
        $result = $conn->query($sql);
        $data["seconds"] = $result->fetch_all(MYSQLI_ASSOC);

        $res["success"] = true;
        $res["msg"] = "watch customization fetched successfully";
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

?>