<?php

require ("./conn.php");
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array
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
        $sql = "SELECT * FROM `orders` WHERE `user_id`='$uid'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $t = json_decode($row["watch_id"], true);
                $te = json_decode($row["watch_face_id"], true);
                $index = 0;
                $array = array();
                foreach ($t as $key => $val) {
                    $watch = array();

                    // Get watch details
                    $wid = $t[$index];
                    $sql1 = "SELECT * FROM `watch` where `wid`='$wid'";
                    $result1 = $conn->query($sql1);
                    $watch["watchDetails"] = $result1->fetch_assoc();

                    // Get watch face details if available
                    if (!is_null($te) && array_key_exists($index, $te)) {
                        $wid = $te[$index];
                        $sql1 = "SELECT * FROM `watch_face` where `watch_face_id`='$wid'";
                        $result1 = $conn->query($sql1);
                        $watch["watchFaceDetails"] = $result1->fetch_assoc();
                    } else {
                        $watch["watchFaceDetails"] = null;
                    }

                    // Get user details
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

                // Format the ordered_at date and time with the correct timezone
                $date = new DateTime($row["ordered_at"], new DateTimeZone('UTC'));
                $date->setTimezone(new DateTimeZone('Asia/Kolkata')); // Set to Indian Standard Time (IST)
                $row["ordered_at"] = $date->format('d-m-Y Time: H:i');

                array_push($data, $row);
            }
            $res["success"] = true;
            $res["msg"] = "List of orders";
            $res["data"] = $data;
            echo json_encode($res);
        } else {
            $res["success"] = false;
            $res["msg"] = "No orders found";
            $res["data"] = [];
            echo json_encode($res);
        }
    } else {
        $res["success"] = false;
        $res["msg"] = "Not a valid token";
        $res["data"] = [];
        echo json_encode($res);
    }
}
$conn->close();

?>