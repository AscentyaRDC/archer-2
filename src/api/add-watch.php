<?php

require('./conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Set default values for fields that might not be provided by the frontend
    $name = isset($_POST['name']) ? $_POST['name'] : 'Custom Watch';
    $model = isset($_POST['model']) ? $_POST['model'] : 'Model-X123';
    $base_price = isset($_POST['base_price']) ? $_POST['base_price'] : 1000;
    $created_by = isset($_POST['created_by']) ? $_POST['created_by'] : 1;
    $is_available = isset($_POST['is_available']) ? $_POST['is_available'] : 20;
    $aaditional_charges = isset($_POST['aaditional_charges']) ? $_POST['aaditional_charges'] : 10;

    // Directories for file uploads
    $watch_upload_dir = './image/watch/';

    // Check if the directories exist, and if not, create them
    if (!is_dir($watch_upload_dir)) {
        mkdir($watch_upload_dir, 0777, true);
    }

    // Image variables and file paths
    $image_paths = [];
    $image_names = ['image_front', 'image_back', 'image_up', 'image_down', 'image_left', 'image_right'];

    // Handle the upload of each image and store the file paths
    foreach ($image_names as $image_name) {
        if (isset($_FILES[$image_name]) && $_FILES[$image_name]['error'] == UPLOAD_ERR_OK) {
            $file_name = basename($_FILES[$image_name]['name']);
            $file_tmp_name = $_FILES[$image_name]['tmp_name'];
            $file_path = $watch_upload_dir . $file_name;

            if (move_uploaded_file($file_tmp_name, $file_path)) {
                $image_paths[$image_name] = $file_path;
            } else {
                die("Failed to upload $image_name.");
            }
        } else {
            $image_paths[$image_name] = '';
        }
    }

    // Prepare the SQL query
    $sql = "INSERT INTO `watch`(`name`, `model`, `base_price`, `dial_id`, `strap_id`, `chronocase_id`, 
        `movement_id`, `bezel_id`, `hands_id`, `seconds_id`, `crown_id`, `caseback_id`, `image_front`, 
        `image_back`, `image_left`, `image_right`, `image_up`, `image_down`, `aaditional_charges`, `created_by`, 
        `is_available`, `dial_size`, `color`, `weight`, `body_type`, `brand`, `batteries_included`, 
        `year_of_manufacture`, `warranty_period`, `item_code`, `display_type`, `water_resistant`, 
        `ip_rating`, `shape`, `inside_the_box`, `delivery_fee`) 
        VALUES (
        '$name', 
        '$model', 
        '$base_price', 
        '" . $_POST['dial_dropdown_8'] . "', 
        '" . $_POST['strap_dropdown_8'] . "', 
        '" . $_POST['chronocase_dropdown_8'] . "', 
        '" . $_POST['movement_dropdown_8'] . "', 
        '" . $_POST['bezel_dropdown_8'] . "', 
        '" . $_POST['hand_dropdown_8'] . "', 
        '" . $_POST['second_dropdown_8'] . "', 
        '" . $_POST['crown_dropdown_8'] . "', 
        '" . $_POST['caseback_dropdown_8'] . "', 
        '" . $image_paths['image_front'] . "', 
        '" . $image_paths['image_back'] . "', 
        '" . $image_paths['image_left'] . "', 
        '" . $image_paths['image_right'] . "', 
        '" . $image_paths['image_up'] . "', 
        '" . $image_paths['image_down'] . "', 
        '$aaditional_charges', 
        '$created_by', 
        '$is_available', 
        '" . $_POST['dial_size_8'] . "', 
        '" . $_POST['color_8'] . "', 
        '" . $_POST['weight_8'] . "', 
        '" . $_POST['body_type_8'] . "', 
        '" . $_POST['brand_8'] . "', 
        '" . $_POST['batteries_included_8'] . "', 
        '" . $_POST['year_of_manufacture_8'] . "', 
        '" . $_POST['warranty_period_8'] . "', 
        '" . $_POST['item_code_8'] . "', 
        '" . $_POST['display_type_8'] . "', 
        '" . $_POST['water_resistant_8'] . "', 
        '" . $_POST['ip_rating_8'] . "', 
        '" . $_POST['shape_8'] . "', 
        '" . $_POST['inside_the_box_8'] . "', 
        '" . $_POST['delivery_fee_8'] . "')";

    if ($conn->query($sql) === TRUE) {
        $last_d = $conn->insert_id;
        $array = array();
        $data["last_inserted_id"] = $last_d;
        array_push($array, $data);
        $res["success"] = true;
        $res["data"] = $array;
        $res["msg"] = "watch details added successfully";

        $out = json_encode($res);
        echo $out;
    } else {
        $res["success"] = false;
        $res["data"] = [];
        $res["msg"] = "Error: " . $sql . "<br>" . $conn->error . "<br>";

        $out = json_encode($res);
        echo $out;
    }
}

$conn->close();

?>
