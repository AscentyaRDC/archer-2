<?php
require("./conn.php");
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array
$token = $input["token"];

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Method Not Allowed";
} else {

    $sql = "SELECT `token` FROM `user` WHERE `token` = '$token'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {

        $data = array();
        $sql = "SELECT * FROM `orders`";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                $order_id = $row["order_id"];
                $query = "SELECT * FROM `order_details` WHERE `order_id` = '$order_id'";
                $queryResult = $conn->query($query);
                $array = array();
                if ($queryResult->num_rows > 0) {
                    while ($row1 = $queryResult->fetch_assoc()) {
                       
                        $wid = $row1["watch_id"];
                        $sql1 = "SELECT * FROM `watch` where `wid`='$wid'";
                        $result1 = $conn->query($sql1);
                        $watch["watchDetail"] = $result1->fetch_assoc();

                        $wid = $row1["watch_face_id"];
                        $sql1 = "SELECT * FROM `watch_face` where `watch_face_id`='$wid'";
                        $result1 = $conn->query($sql1);
                        $watch["watchFaceDetail"] = $result1->fetch_assoc();

                        $wid = $row1["user_phone"];
                        $sql1 = "SELECT * FROM `user` where `mobile`='$wid'";
                        $result1 = $conn->query($sql1);
                        $watch["userDetail"] = $result1->fetch_assoc();
                        array_push($array, $watch);
                        
                    }
                }

                $row["details"] = $array;
                unset($row["user_id"]);
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