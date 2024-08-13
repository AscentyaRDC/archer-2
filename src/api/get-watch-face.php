<?php

require("./conn.php");

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array

$wid = $input["wid"];

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Method Not Allowed";
}
else{

    $data = array();
    $sql = "SELECT * FROM `watch_face` WHERE `wid`='$wid'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
                $row["image_front"] = $base . substr($row["image_front"],1);
                $row["image_back"] = $base. substr($row["image_back"],1);
                $row["image_left"] = $base. substr($row["image_left"],1);
                $row["image_right"] = $base. substr($row["image_right"],1);
                $row["image_up"] = $base. substr($row["image_up"],1);
                $row["image_down"] = $base. substr($row["image_down"],1);
                array_push($data,$row);   
        }
        $res["success"] = true;
        $res["msg"] = "List of watch faces";
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

$conn->close();

?>