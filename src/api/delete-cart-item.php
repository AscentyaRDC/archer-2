<?php

require("./conn.php");
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array
$token = $input["token"];
$mobile = $input["user_id"];
$wid = $input["wid"];
$wfid = $input["wfid"];

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Method Not Allowed";
} else {

    $sql = "SELECT `uid`,`token` FROM `user` WHERE `token` = '$token'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $data = array();
        $row0 = $result->fetch_assoc();
        $uid = $row0["uid"];
        $sql = "SELECT * FROM `cart_items` where `user_id` = '$uid'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                $array = array();
                $array2 = array();

                $temp = 0;
                $t = json_decode($row["cart_detail"], true);
               
                while (count($t) > $temp) {
                    $f = $t[$temp];
                    $u = json_encode($f);
                    $jArr = json_decode($u, true);

                    if ($jArr["wid"] == $wid && $jArr["wfid"] == $wfid) {
                        unset($t[$temp]);
                    }

                    $temp += 1;
                }

                $t = array_values($t);
                $t = json_encode($t);
            

                $sql = "UPDATE `cart_items` SET `cart_detail`='$t' WHERE `user_id` = '$uid'";

                if ($conn->query($sql) === TRUE) {
                    $res["success"] = true;
                    $res["msg"] = "Item Deleted Successfully";
                    $res["data"] = [];

                    $out = json_encode($res);
                    echo $out;
                } else {
                    $res["success"] = true;
                    $res["msg"] = "something went wrong";
                    $res["data"] = [];

                    $out = json_encode($res);
                    echo $out;
                }




            }

        } else {

            $res["success"] = false;
            $res["msg"] = "no cart items found";
            $res["data"] = [];

            $out = json_encode($res);

            echo $out;
        }
    } else {
        $res["success"] = false;
        $res["msg"] = "Not a Valid token";
        $res["data"] = [];

        $out = json_encode($res);
        echo $out;
    }
}

$conn->close();

?>