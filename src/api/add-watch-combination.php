<?php
require ('./conn.php');

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array

$combination = $input["combination"];
$wid = $input["wid"];
$token = $input["token"];

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Method Not Allowed";
} else {
    $sql = "SELECT `token` FROM `user` WHERE `token` = '$token'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $sql = "SELECT `combination` FROM `watch` WHERE `wid`='$wid'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $existingCombination = json_decode($row['combination'], true);

            // Merge the existing combination with the new combination, ensuring no duplicates
            foreach ($combination as $key => $value) {
                if (!isset($existingCombination[$key])) {
                    $existingCombination[$key] = [];
                }
                foreach ($value as $newItem) {
                    if (!in_array($newItem, $existingCombination[$key])) {
                        $existingCombination[$key][] = $newItem;
                    }
                }
            }

            $t = date('Y-m-d h:i:s', time());
            $data = json_encode($existingCombination);

            $sql = "UPDATE `watch` SET `combination`='$data', `updated_at`='$t' WHERE `wid`='$wid'";

            if ($conn->query($sql) === TRUE) {
                $res["success"] = true;
                $res["data"] = [];
                $res["msg"] = "Watch combinations updated successfully";

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
            $res["success"] = false;
            $res["msg"] = "Watch not found";
            $res["data"] = [];

            $out = json_encode($res);
            echo $out;
        }
    } else {
        $res["success"] = false;
        $res["msg"] = "Not a valid token";
        $res["data"] = [];

        $out = json_encode($res);
        echo $out;
    }
}
$conn->close();
?>