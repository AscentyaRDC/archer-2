<?php
require("./conn.php");

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); // Convert JSON into an array
$token = $input["token"];

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Method Not Allowed";
    exit();
}

$sql = "SELECT `token` FROM `user` WHERE `token` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = array();
    // Query to fetch available dials only
    $sql = "SELECT * FROM `bezel` WHERE `is_available` > 1";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $row["image_url"] = $base . substr($row["image_url"], 1);
            array_push($data, $row);
        }
        $res["success"] = true;
        $res["msg"] = "List of available bezels";
        $res["data"] = $data;
    } else {
        $res["success"] = false;
        $res["msg"] = "No available bezels found";
        $res["data"] = [];
    }
} else {
    $res["success"] = false;
    $res["msg"] = "Invalid token";
    $res["data"] = [];
}

echo json_encode($res);
$conn->close();
?>
