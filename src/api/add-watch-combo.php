<?php

require('./conn.php');

// Collect data from POST request
$combo_number = $_POST['combo_number']; // Which combo is this? (1 to 8)
$dial_id = $_POST['did'];
$strap_id = $_POST['sid'];
$chronocase_id = $_POST['cid'];
$movement_id = $_POST['mid'];
$bezel_id = $_POST['bid'];
$hands_id = $_POST['hid'];
$seconds_id = $_POST['sid'];
$crown_id = $_POST['cid'];
$caseback_id = $_POST['cid'];
$wid = $_POST['wid'];
$price = $_POST['price'];
$token = $_POST['token'];

$image_front_name = $_FILES["image_front"]["name"];
$image_front_tmp_name = $_FILES["image_front"]["tmp_name"];
$folder_front = "./image/watchface/" . $image_front_name;

$created_by = $_POST["user_id"];

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode(["success" => false, "msg" => "Method Not Allowed"]);
    exit();
}

// Validate the token
$sql = "SELECT `token`, `uid` FROM `user` WHERE `token` = '$token'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $res1 = $result->fetch_assoc();
    $uid = $res1["uid"];

    // Determine if we are inserting into watch_face or watch table
    if ($combo_number >= 1 && $combo_number <= 7) {
        // Insert into watch_face table
        $sql = "INSERT INTO `watch_face` (`wid`, `dial_id`, `strap_id`, `bezel_id`, `caseback_id`, `chronocase_id`, 
                 `crown_id`, `hand_id`, `second_id`, `movement_id`, `image_front`, `created_by`, `price`) 
                VALUES ('$wid', '$dial_id', '$strap_id', '$bezel_id', '$caseback_id', '$chronocase_id', 
                        '$crown_id', '$hands_id', '$seconds_id', '$movement_id', '$folder_front', '$uid', '$price')";
    } elseif ($combo_number == 8) {
        // Insert into watch table
        $sql = "INSERT INTO `watch` (`wid`, `dial_id`, `strap_id`, `bezel_id`, `caseback_id`, `chronocase_id`, 
                 `crown_id`, `hand_id`, `second_id`, `movement_id`, `image_front`, `created_by`, `price`) 
                VALUES ('$wid', '$dial_id', '$strap_id', '$bezel_id', '$caseback_id', '$chronocase_id', 
                        '$crown_id', '$hands_id', '$seconds_id', '$movement_id', '$folder_front', '$uid', '$price')";
    } else {
        echo json_encode(["success" => false, "msg" => "Invalid combo number"]);
        exit();
    }

    // Execute the SQL query
    if ($conn->query($sql) === TRUE) {
        if (move_uploaded_file($image_front_tmp_name, $folder_front)) {
            echo json_encode(["success" => true, "msg" => "Data saved successfully"]);
        } else {
            echo json_encode(["success" => false, "msg" => "File upload failed"]);
        }
    } else {
        echo json_encode(["success" => false, "msg" => "Error: " . $sql . "<br>" . $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "msg" => "Invalid token"]);
}

$conn->close();
?>
