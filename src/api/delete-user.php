<?php
require('./conn.php');

header("Content-Type: application/json");

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); // Convert JSON into an array
$uid = $input["mobile"];
$password = $input["password"];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Prepare the SQL statement
    $stmt = $conn->prepare("DELETE FROM `user` WHERE `mobile` = ? AND `password` = ?");
    $stmt->bind_param("ss", $uid, $password);
    $stmt->execute();

    // Check the number of affected rows
    if ($stmt->affected_rows > 0) {
        $res["success"] = true;
        $res["data"] = [];
        $res["msg"] = "User deleted successfully";
    } else {
        $res["success"] = false;
        $res["data"] = [];
        $res["msg"] = "Incorrect password";
    }

    $stmt->close();
} else {
    $res["success"] = false;
    $res["data"] = [];
    $res["msg"] = "Method Not Allowed";
}

$conn->close();

$out = json_encode($res);
echo $out;
?>