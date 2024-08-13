<?php

require ("./conn.php");
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); // Convert JSON into array
$token = $input["token"];

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Method Not Allowed";
} else {
    $sql = "SELECT `uid`,`token` FROM `user` WHERE `token` = '$token'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row0 = $result->fetch_assoc();
        $uid = $row0["uid"];
        $data = array();
        $sql = "SELECT * FROM `orders`";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $temp = $row["watch_id"];
                $t = json_decode($temp);
                $temp1 = $row["watch_face_id"];
                $te = json_decode($temp1);
                $index = 0;
                $array = array();
                foreach ($t as $key => $val) {

                    $wid = $t[$index];
                    $sql1 = "SELECT * FROM `watch` where `wid`='$wid'";
                    $result1 = $conn->query($sql1);
                    $watch["watchDetails"] = $result1->fetch_assoc();

                    if ($te !== null && isset($te[$index])) {
                        $wid = $te[$index];
                        $sql1 = "SELECT * FROM `watch_face` where `watch_face_id`='$wid'";
                        $result1 = $conn->query($sql1);
                        $watch["watchFaceDetails"] = $result1->fetch_assoc();
                    } else {
                        $watch["watchFaceDetails"] = null;
                    }

                    $wid = $row["user_id"];
                    $sql1 = "SELECT * FROM `user` where `uid`='$wid'";
                    $result1 = $conn->query($sql1);
                    $watch["userDetails"] = $result1->fetch_assoc();
                    array_push($array, $watch);
                    $index++;
                }

                $row["productDetails"] = $array;
                unset($row["user_id"]);
                $row["price"] = json_decode($row["price"], true);
                $row["watch_id"] = json_decode($row["watch_id"], true);
                $row["watch_face_id"] = json_decode($row["watch_face_id"], true);
                $row["cgst"] = json_decode($row["cgst"], true);
                $row["sgst"] = json_decode($row["sgst"], true);
                $row["qty"] = json_decode($row["qty"], true);
                array_push($data, $row);
            }
            $res["success"] = true;
            $res["msg"] = "List of orders";
            $res["data"] = $data;
            $out = json_encode($res);
            echo $out;
        } else {

            $res["success"] = false;
            $res["msg"] = "no orders found";
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