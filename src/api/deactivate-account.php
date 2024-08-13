<?php
require('./conn.php');

header("Content-Type: application/json");

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); // Convert JSON into an array
$mobile = $input["mobile"];
$password = $input["password"];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Prepare the SQL statement to check user credentials
    $stmt = $conn->prepare("SELECT * FROM user WHERE mobile = ? AND password = ?");
    $stmt->bind_param("ss", $mobile, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User exists, proceed to deactivate
        $deactivation_date = date('Y-m-d H:i:s');
        $stmt = $conn->prepare("UPDATE user SET deactivated = 1, deactivation_date = ? WHERE mobile = ? AND password = ?");
        $stmt->bind_param("sss", $deactivation_date, $mobile, $password);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $res["success"] = true;
            $res["data"] = [];
            $res["msg"] = "Your account will be deleted after 30 days. You can log in to reactivate it.";
        } else {
            $res["success"] = false;
            $res["data"] = [];
            $res["msg"] = "Failed to deactivate account.";
        }
    } else {
        $res["success"] = false;
        $res["data"] = [];
        $res["msg"] = "Incorrect password.";
    }

    $stmt->close();
} else {
    $res["success"] = false;
    $res["data"] = [];
    $res["msg"] = "Method Not Allowed.";
}

$conn->close();

$out = json_encode($res);
echo $out;
?>