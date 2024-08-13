<?php

require ("./conn.php");

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array

$wid = $input["wid"];
$token = $input["token"];

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Method Not Allowed";
} else {
    $sql = "SELECT `token` FROM `user` WHERE `token` = '$token'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $data = array();
        $watchfaceData = array();
        $sql = "SELECT * FROM `watch` where `wid`='$wid'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $row["image_front"] = $base . substr($row["image_front"], 1);
                $row["image_back"] = $base . substr($row["image_back"], 1);
                $row["image_left"] = $base . substr($row["image_left"], 1);
                $row["image_right"] = $base . substr($row["image_right"], 1);
                $row["image_up"] = $base . substr($row["image_up"], 1);
                $row["image_down"] = $base . substr($row["image_down"], 1);
                $row["combination"] = json_decode($row["combination"]);
                $row["status"] = ($row["status"]);
                $row["description"] = ($row["description"]);

                $sql = "SELECT * FROM `watch_face` where `wid`='$wid'";
                $result = $conn->query($sql);
                while ($row1 = $result->fetch_assoc()) {
                    $row1["image_front"] = $base . substr($row1["image_front"], 1);
                    $row1["image_back"] = $base . substr($row1["image_back"], 1);
                    $row1["image_left"] = $base . substr($row1["image_left"], 1);
                    $row1["image_right"] = $base . substr($row1["image_right"], 1);
                    $row1["image_up"] = $base . substr($row1["image_up"], 1);
                    $row1["image_down"] = $base . substr($row1["image_down"], 1);
                    // $row["combination"] = json_decode($row["combination"]);
                    $watchfaceData[] = $row1;
                }
                $row["WatchFaceData"] = $watchfaceData;
                array_push($data, $row);

            }
            $res["success"] = true;
            $res["msg"] = "List of watches";
            $res["data"] = $data;

            $out = json_encode($res);

            echo $out;

        } else {

            $res["success"] = false;
            $res["msg"] = "no watches found";
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