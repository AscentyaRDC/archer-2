<?php

require('./conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Set default values for fields that might not be provided by the frontend
        $name = isset($_POST['name']) ? $_POST['name'] : 'Custom Watch';
        $model = isset($_POST['model']) ? $_POST['model'] : 'Model-X123';
        $base_price = isset($_POST['base_price']) ? $_POST['base_price'] : 1000;
        $created_by = isset($_POST['created_by']) ? $_POST['created_by'] : 1;
        $is_available = isset($_POST['is_available']) ? $_POST['is_available'] : 20;
        $aaditional_charges = isset($_POST['aaditional_charges']) ? $_POST['aaditional_charges'] : 10;

        // Directories for file uploads
        $watch_upload_dir = './image/watch/';
        $watchface_upload_dir = './image/watchface/';

        // Check if the directories exist, and if not, create them
        if (!is_dir($watch_upload_dir)) {
            if (!mkdir($watch_upload_dir, 0777, true)) {
                die('Failed to create watch upload directory.');
            }
        }
        if (!is_dir($watchface_upload_dir)) {
            if (!mkdir($watchface_upload_dir, 0777, true)) {
                die('Failed to create watchface upload directory.');
            }
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

        // Insert data into the `watch` table for the 8th combo first
        $stmt_watch = $pdo->prepare("
            INSERT INTO watch (name, model, base_price, dial_id, strap_id, chronocase_id, movement_id, bezel_id, hands_id, seconds_id, crown_id, caseback_id, image_front, image_back, image_left, image_right, image_up, image_down, aaditional_charges, created_by, is_available, dial_size, color, weight, body_type, brand, batteries_included, year_of_manufacture, warranty_period, item_code, display_type, water_resistant, ip_rating, shape, inside_the_box, delivery_fee)
            VALUES (:name, :model, :base_price, :dial_id, :strap_id, :chronocase_id, :movement_id, :bezel_id, :hands_id, :seconds_id, :crown_id, :caseback_id, :image_front, :image_back, :image_left, :image_right, :image_up, :image_down, :aaditional_charges, :created_by, :is_available, :dial_size, :color, :weight, :body_type, :brand, :batteries_included, :year_of_manufacture, :warranty_period, :item_code, :display_type, :water_resistant, :ip_rating, :shape, :inside_the_box, :delivery_fee)
        ");

        $stmt_watch->execute([
            ':name' => $name,
            ':model' => $model,
            ':base_price' => $base_price,
            ':dial_id' => $_POST['dial_dropdown_8'],
            ':strap_id' => $_POST['strap_dropdown_8'],
            ':chronocase_id' => $_POST['chronocase_dropdown_8'],
            ':movement_id' => $_POST['movement_dropdown_8'],
            ':bezel_id' => $_POST['bezel_dropdown_8'],
            ':hands_id' => $_POST['hand_dropdown_8'],
            ':seconds_id' => $_POST['second_dropdown_8'],
            ':crown_id' => $_POST['crown_dropdown_8'],
            ':caseback_id' => $_POST['caseback_dropdown_8'],
            ':image_front' => $image_paths['image_front'],
            ':image_back' => $image_paths['image_back'],
            ':image_left' => $image_paths['image_left'],
            ':image_right' => $image_paths['image_right'],
            ':image_up' => $image_paths['image_up'],
            ':image_down' => $image_paths['image_down'],
            ':aaditional_charges' => $aaditional_charges,
            ':created_by' => $created_by,
            ':is_available' => $is_available,
            ':dial_size' => $_POST['dial_size_8'],
            ':color' => $_POST['color_8'],
            ':weight' => $_POST['weight_8'],
            ':body_type' => $_POST['body_type_8'],
            ':brand' => $_POST['brand_8'],
            ':batteries_included' => $_POST['batteries_included_8'],
            ':year_of_manufacture' => $_POST['year_of_manufacture_8'],
            ':warranty_period' => $_POST['warranty_period_8'],
            ':item_code' => $_POST['item_code_8'],
            ':display_type' => $_POST['display_type_8'],
            ':water_resistant' => $_POST['water_resistant_8'],
            ':ip_rating' => $_POST['ip_rating_8'],
            ':shape' => $_POST['shape_8'],
            ':inside_the_box' => $_POST['inside_the_box_8'],
            ':delivery_fee' => $_POST['delivery_fee_8']
        ]);

        // Get the last inserted wid
        $wid = $pdo->lastInsertId();

        // Combo variables and values
        $combo_values = [];
        $combo_names = ['combo1', 'combo2', 'combo3', 'combo4', 'combo5', 'combo6', 'combo7'];
       
        // Handle the combo values and store them in the array
        foreach ($combo_names as $combo_name) {
            $combo_values[$combo_name] = isset($_POST[$combo_name]) ? $_POST[$combo_name] : '';
        }
        $js = array();
        // Handle the combo values and store them in the array
        foreach ($combo_names as $combo_name) {
            $json[$combo_name] = $combo_name;   
        }
        array_push($js,$json);
         

        // Image handling for combos
        $combo_image_paths = [];
        $combo_image_names = ['combo_image_1', 'combo_image_2', 'combo_image_3', 'combo_image_4', 'combo_image_5', 'combo_image_6', 'combo_image_7'];

        foreach ($combo_image_names as $image_name) {
            if (isset($_FILES[$image_name]) && $_FILES[$image_name]['error'] == UPLOAD_ERR_OK) {
                $file_name = basename($_FILES[$image_name]['name']);
                $file_tmp_name = $_FILES[$image_name]['tmp_name'];
                $file_path = $watchface_upload_dir . $file_name;

                if (move_uploaded_file($file_tmp_name, $file_path)) {
                    $combo_image_paths[$image_name] = $file_path;
                } else {
                    die("Failed to upload $image_name.");
                }
            } else {
                $combo_image_paths[$image_name] = ''; // Set to empty if no file was uploaded
            }
        }

        // Function to insert data into watch_face table
        function insertWatchFace($pdo, $wid, $dial_id, $strap_id, $bezel_id, $caseback_id, $chronocase_id, $crown_id, $hand_id, $second_id, $movement_id, $combo, $created_by)
        {
            $stmt_face = $pdo->prepare("
                INSERT INTO watch_face (wid, dial_id, strap_id, bezel_id, caseback_id, chronocase_id, crown_id, hand_id, second_id, movement_id, combo, created_by)
                VALUES (:wid, :dial_id, :strap_id, :bezel_id, :caseback_id, :chronocase_id, :crown_id, :hand_id, :second_id, :movement_id, :combo, :created_by)
            ");
            $stmt_face->execute([
                ':wid' => $wid,
                ':dial_id' => $dial_id,
                ':strap_id' => $strap_id,
                ':bezel_id' => $bezel_id,
                ':caseback_id' => $caseback_id,
                ':chronocase_id' => $chronocase_id,
                ':crown_id' => $crown_id,
                ':hand_id' => $hand_id,
                ':second_id' => $second_id,
                ':movement_id' => $movement_id,
                ':combo' => $combo,
                ':created_by' => $created_by
            ]);

        }
        

        // Insert data for each combo (1 to 7)
        insertWatchFace($pdo, $wid, $_POST['dial_dropdown_1'] ?? null, $_POST['strap_dropdown_1'] ?? null, null, null, null, null, null, null, null,$js[0]['combo1'], $created_by);
        insertWatchFace($pdo, $wid, $_POST['dial_dropdown_2'] ?? null, $_POST['strap_dropdown_2'] ?? null, $_POST['bezel_dropdown_2'] ?? null, null, null, null, null, null, null, $js[0]['combo2'], $created_by);
        insertWatchFace($pdo, $wid, $_POST['dial_dropdown_3'] ?? null, $_POST['strap_dropdown_3'] ?? null, $_POST['bezel_dropdown_3'] ?? null, $_POST['caseback_dropdown_3'] ?? null, null, null, null, null, null, $js[0]['combo3'], $created_by);
        insertWatchFace($pdo, $wid, $_POST['dial_dropdown_4'] ?? null, $_POST['strap_dropdown_4'] ?? null, $_POST['bezel_dropdown_4'] ?? null, $_POST['caseback_dropdown_4'] ?? null, $_POST['chronocase_dropdown_4'] ?? null, null, null, null, null, $js[0]['combo4'], $created_by);
        insertWatchFace($pdo, $wid, $_POST['dial_dropdown_5'] ?? null, $_POST['strap_dropdown_5'] ?? null, $_POST['bezel_dropdown_5'] ?? null, $_POST['caseback_dropdown_5'] ?? null, $_POST['chronocase_dropdown_5'] ?? null, $_POST['crown_dropdown_5'] ?? null, null, null, null, $js[0]['combo5'], $created_by);
        insertWatchFace($pdo, $wid, $_POST['dial_dropdown_6'] ?? null, $_POST['strap_dropdown_6'] ?? null, $_POST['bezel_dropdown_6'] ?? null, $_POST['caseback_dropdown_6'] ?? null, $_POST['chronocase_dropdown_6'] ?? null, $_POST['crown_dropdown_6'] ?? null, $_POST['hand_dropdown_6'] ?? null, null, null, $js[0]['combo6'], $created_by);
        insertWatchFace($pdo, $wid, $_POST['dial_dropdown_7'] ?? null, $_POST['strap_dropdown_7'] ?? null, $_POST['bezel_dropdown_7'] ?? null, $_POST['caseback_dropdown_7'] ?? null, $_POST['chronocase_dropdown_7'] ?? null, $_POST['crown_dropdown_7'] ?? null, $_POST['hand_dropdown_7'] ?? null, $_POST['second_dropdown_7'] ?? null, $_POST['movement_dropdown_7'] ?? null, $js[0]['combo7'], $created_by);

        echo json_encode([
            'success' => true,
            'message' => 'Data saved successfully!'
        ]);
    } catch (Exception $e) {
        // Ensure that errors are captured and returned as JSON
        echo json_encode([
            'success' => false,
            'message' => 'An error occurred: ' . $e->getMessage()
        ]);
    }
}

$conn->close();
?>