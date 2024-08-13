<?php

require("./conn.php");
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array
$token = $input["token"];

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Method Not Allowed";
} else {

    $sql = "SELECT `uid`,`token` FROM `user` WHERE `token` = '$token'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $data = array();
        $row0 = $result->fetch_assoc();
        $uid = $row0["uid"];
        $sql = "SELECT * FROM `cart_items` where `user_id` = '$uid'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                $array = array();
                $array2 = array();

                $temp = 0;
                $t =  json_decode($row["cart_detail"],true);
                
                while (count($t) > $temp) {
                    $f = $t[$temp];
                    $u = json_encode($f);
                    $jArr = json_decode($u, true);

                    
                    $wid = $jArr["wid"];
                    $sql1 = "SELECT * FROM `watch` where `wid`='$wid'";
                    $result1 = $conn->query($sql1);
                    $watch["watchDetail"] = $result1->fetch_assoc();
                    

                    $wid = $jArr["wfid"];
                    $sql1 = "SELECT * FROM `watch_face` where `watch_face_id`='$wid'";
                    $result1 = $conn->query($sql1);
                    $watch["watchFaceDetail"] = $result1->fetch_assoc();

                    array_push($array2, $watch);
                    $temp += 1;

                }

                $row["CartDetails"] = $array2;
                unset($row["user_id"]);
                unset($row["cart_detail"]);
                array_push($data, $row);

            }
            $res["success"] = true;
            $res["msg"] = "List of cart items";
            $res["data"] = $data;
            $out = json_encode($res);
            echo $out;

        } else {

            $res["success"] = false;
            $res["msg"] = "no cart items found";
            $res["data"] = [];

            $out = json_encode($res);

            echo $out;
        }
    } else {
        $res["success"] = false;
        $res["msg"] = "Not a Valid token";
        $res["data"] = [];

        $out = json_encode($res);
        echo $out;
    }
}

$conn->close();

?>