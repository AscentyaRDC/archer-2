<?php
require('./conn.php');

$dial_id = $_POST["dial_id"];
$strap_id = $_POST["strap_id"];
$chronocase_id = $_POST["chronocase_id"];
$movement_id = $_POST["movement_id"];
$bezel_id = $_POST["bezel_id"];
$hands_id = $_POST["hands_id"];
$seconds_id = $_POST["seconds_id"];
$crown_id = $_POST["crown_id"];
$caseback_id = $_POST["caseback_id"];
$wid = $_POST["wid"];
$price = $_POST["price"];
$token = $_POST["token"];

$image_front_name = $_FILES["image_front"]["name"];
$image_front_tmp_name = $_FILES["image_front"]["tmp_name"];
$folder_front = "./image/watchface/" . $image_front_name;

// Define the remaining image paths and tmp names
$image_back_name = $_FILES["image_back"]["name"];
$image_back_tmp_name = $_FILES["image_back"]["tmp_name"];
$folder_back = "./image/watchface/" . $image_back_name;

$image_up_name = $_FILES["image_up"]["name"];
$image_up_tmp_name = $_FILES["image_up"]["tmp_name"];
$folder_up = "./image/watchface/" . $image_up_name;

$image_down_name = $_FILES["image_down"]["name"];
$image_down_tmp_name = $_FILES["image_down"]["tmp_name"];
$folder_down = "./image/watchface/" . $image_down_name;

$image_left_name = $_FILES["image_left"]["name"];
$image_left_tmp_name = $_FILES["image_left"]["tmp_name"];
$folder_left = "./image/watchface/" . $image_left_name;

$image_right_name = $_FILES["image_right"]["name"];
$image_right_tmp_name = $_FILES["image_right"]["tmp_name"];
$folder_right = "./image/watchface/" . $image_right_name;

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Method Not Allowed";
} else {
    $sql = "SELECT `token`,`uid` FROM `user` WHERE `token` = '$token'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $res1 = $result->fetch_assoc();
        $uid = $res1["uid"]; // Use the fetched uid

        // Insert into the watch_face table
        $sql = "INSERT INTO `watch_face`( `wid`, `dial_id`, `strap_id`, `bezel_id`, `caseback_id`, `chronocase_id`,
         `crown_id`, `hand_id`, `second_id`, `movement_id`, `image_front`, `image_back`, `image_up`, `image_down`,
         `image_left`, `image_right`, `created_by`,`price` 
         ) VALUES ('$wid','$dial_id','$strap_id','$bezel_id','$caseback_id','$chronocase_id','$crown_id',
         '$hands_id','$seconds_id','$movement_id','$folder_front','$folder_back'
         ,'$folder_up','$folder_down','$folder_left','$folder_right','$uid',
         '$price'
        )";

        if ($conn->query($sql) === TRUE) {
            $folderArr = array($folder_front, $folder_back, $folder_up, $folder_down, $folder_left, $folder_right);
            $nameArr = array(
                $image_front_tmp_name,
                $image_back_tmp_name,
                $image_up_tmp_name,
                $image_down_tmp_name,
                $image_left_tmp_name,
                $image_right_tmp_name
            );

            $count = count($folderArr);
            $added_file = 0;

            // Move each uploaded file to its folder
            for ($i = 0; $i < $count; $i++) {
                if (move_uploaded_file($nameArr[$i], $folderArr[$i])) {
                    $added_file += 1;
                } else {
                    echo $added_file;
                }
            }

            if ($added_file == 6) {
                $t = 0;
                $sql = "SELECT * FROM `watch_face`";
                $data = array();
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        if ($t == $result->num_rows - 1) {
                            array_push($data, $row);
                        }
                        $t += 1;
                    }
                    $res["success"] = true;
                    $res["data"] = $data;
                    $res["msg"] = "Watch face added successfully";

                    $out = json_encode($res);
                    echo $out;
                }
            } else {
                $res["success"] = false;
                $res["data"] = [];
                $res["msg"] = "Something went wrong";

                $out = json_encode($res);
                echo $out;
            }
        } else {
            $res["success"] = false;
            $res["data"] = [];
            $res["msg"] = "Error: " . $sql . "<br>" . $conn->error . "<br>";

            $out = json_encode($res);
            echo $out;
        }
    } else {
        $res["success"] = false;
        $res["msg"] = "Invalid token";
        $res["data"] = [];

        $out = json_encode($res);
        echo $out;
    }
}
$conn->close();
?>