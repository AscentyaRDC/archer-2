<?php
require('./conn.php');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start output buffering
ob_start();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Method Not Allowed";
    exit();
} else {
    $name = $_POST["name"];
    $price = $_POST["price"];
    $watch_id = $_POST["watch_id"];

    $image_front_name = $_FILES["image_front"]["name"];
    $image_front_tmp_name = $_FILES["image_front"]["tmp_name"];
    $upload_directory = './image/chrono/';
    $image_front_path = $upload_directory . basename($image_front_name);

    $res = array("success" => false, "msg" => "", "data" => array());

    if (move_uploaded_file($image_front_tmp_name, $image_front_path)) {
        // Prepare the insert statement
        $query = "INSERT INTO chronocase (name, price, image_url, watch_id, created_by, is_available) VALUES (?, ?, ?, ?, 1, 20)";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("sdsi", $name, $price, $image_front_path, $watch_id);
            if ($stmt->execute()) {
                // Record inserted successfully
                $res["success"] = true;
                $res["msg"] = "Chronocase added successfully.";
                // Fetch the inserted record to return it
                $inserted_id = $stmt->insert_id;
                $query = "SELECT * FROM chronocase WHERE cid = ?";
                if ($stmt = $conn->prepare($query)) {
                    $stmt->bind_param("i", $inserted_id);
                    if ($stmt->execute()) {
                        $result = $stmt->get_result();
                        $inserted_record = $result->fetch_assoc();
                        $res["data"] = $inserted_record;
                    } else {
                        $res["msg"] = "Error fetching inserted record: " . $stmt->error;
                    }
                } else {
                    $res["msg"] = "Error preparing fetch statement: " . $conn->error;
                }
            } else {
                // Error executing the insert statement
                $res["msg"] = "Error inserting record: " . $stmt->error;
            }
            $stmt->close();
        } else {
            // Error preparing the insert statement
            $res["msg"] = "Error preparing statement: " . $conn->error;
        }
    } else {
        // Error uploading the image
        $res["msg"] = "Failed to upload image.";
    }

    // Clear the output buffer and send JSON response
    ob_end_clean();
    echo json_encode($res);
}

$conn->close();
?>