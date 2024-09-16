<?php
require('./conn.php');

// Collect POST data for dropdown selections
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

// Collect image paths for dropdown images
$dial_image = isset($_POST["dial_image"]) ? $_POST["dial_image"] : '';
$strap_image = isset($_POST["strap_image"]) ? $_POST["strap_image"] : '';
$chronocase_image = isset($_POST["chronocase_image"]) ? $_POST["chronocase_image"] : '';
$movement_image = isset($_POST["movement_image"]) ? $_POST["movement_image"] : '';
$bezel_image = isset($_POST["bezel_image"]) ? $_POST["bezel_image"] : '';
$hand_image = isset($_POST["hands_image"]) ? $_POST["hands_image"] : '';
$second_image = isset($_POST["seconds_image"]) ? $_POST["seconds_image"] : '';
$crown_image = isset($_POST["crown_image"]) ? $_POST["crown_image"] : '';
$caseback_image = isset($_POST["caseback_image"]) ? $_POST["caseback_image"] : '';

// Collect uploaded files
$image_front_name = $_FILES["image_front"]["name"];
$image_front_tmp_name = $_FILES["image_front"]["tmp_name"];
$folder_front = "./image/watchface/" . $image_front_name;

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

// Move uploaded files to target directories
move_uploaded_file($image_front_tmp_name, $folder_front);
move_uploaded_file($image_back_tmp_name, $folder_back);
move_uploaded_file($image_up_tmp_name, $folder_up);
move_uploaded_file($image_down_tmp_name, $folder_down);
move_uploaded_file($image_left_tmp_name, $folder_left);
move_uploaded_file($image_right_tmp_name, $folder_right);

// Fetch the `uid` based on the provided token
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Method Not Allowed";
} else {
    $sql = "SELECT `token`, `uid` FROM `user` WHERE `token` = '$token'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $res1 = $result->fetch_assoc();
        $uid = $res1["uid"]; // Use the fetched `uid`

        // Insert into database with `created_by`
        $sql = "INSERT INTO `watch_face` (`wid`, `dial_id`, `strap_id`, `bezel_id`, `caseback_id`, `chronocase_id`, 
            `crown_id`, `hand_id`, `second_id`, `movement_id`, `image_front`, `image_back`, `image_up`, 
            `image_down`, `image_left`, `image_right`, `price`, `dial_image`, `strap_image`, `chronocase_image`, 
            `movement_image`, `bezel_image`, `hand_image`, `second_image`, `crown_image`, `caseback_image`, 
            `created_by`
        ) VALUES ('$wid', '$dial_id', '$strap_id', '$bezel_id', '$caseback_id', '$chronocase_id', '$crown_id',
        '$hands_id', '$seconds_id', '$movement_id', '$folder_front', '$folder_back', '$folder_up', '$folder_down',
        '$folder_left', '$folder_right', '$price', '$dial_image', '$strap_image', '$chronocase_image', 
        '$movement_image', '$bezel_image', '$hand_image', '$second_image', '$crown_image', '$caseback_image', '$uid'
        )";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["success" => true, "msg" => "Watch face added successfully"]);
        } else {
            echo json_encode(["success" => false, "msg" => "Error: " . $sql . "<br>" . $conn->error]);
        }
    } else {
        echo json_encode(["success" => false, "msg" => "Invalid token"]);
    }
}

$conn->close();
?>
