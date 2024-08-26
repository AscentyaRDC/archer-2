<?php
require("./conn.php");

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); // Convert JSON into an array
$token = $input["token"];

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Method Not Allowed";
    exit();
}

// Verify the token
$sql = "SELECT `token` FROM `user` WHERE `token` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = array();
    $tables = ["dial", "strap", "bezel", "caseback", "chronocase", "crowns", "hands", "movement", "seconds"];
    
    foreach ($tables as $table) {
        $sql = "SELECT * FROM `$table` WHERE `is_available` > 1";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $tableData = array();
            while ($row = $result->fetch_assoc()) {
                $row["image_url"] = $base . substr($row["image_url"], 1);
                array_push($tableData, $row);
            }
            $data[$table] = $tableData;
        } else {
            $data[$table] = [];
        }
    }
    
    $res["success"] = true;
    $res["msg"] = "List of available items from all tables";
    $res["data"] = $data;
} else {
    $res["success"] = false;
    $res["msg"] = "Invalid token";
    $res["data"] = [];
}

echo json_encode($res);
$conn->close();
?>
