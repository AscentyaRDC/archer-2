<?php
require('./conn.php');

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); // Convert JSON into an array
$wid = $input["wid"];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $t = date('Y-m-d h:i:s', time());

    // Delete the watch and associated images
    $sql = "DELETE FROM `watch` WHERE `wid` = '$wid'";

    if ($conn->query($sql) === TRUE) {
        // Optionally, delete associated images here

        $res["success"] = true;
        $res["data"] = [];
        $res["msg"] = "Watch deleted successfully";

        $out = json_encode($res);
        echo $out;
    } else {
        $res["success"] = false;
        $res["data"] = [];
        $res["msg"] = "Error: " . $sql . "<br>" . $conn->error . "<br>";

        $out = json_encode($res);
        echo $out;
    }
} else {
    echo "Method Not Allowed";
}

$conn->close();
?>
