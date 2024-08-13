<?php

// require("./conn.php");
// $inputJSON = file_get_contents('php://input');
// $input = json_decode($inputJSON, TRUE); 
// $token = $input["token"];
// $watchId = json_encode($input["wid"]);
// $watchFaceId = json_encode($input["wfid"]);
// $price = json_encode($input["price"]);
// $cgst = json_encode($input["cgst"]);
// $sgst = json_encode($input["sgst"]);
// $qty = json_encode($input["qty"]);


// if ($_SERVER['REQUEST_METHOD'] != 'POST') {
//     echo "Method Not Allowed";
// } else {

//     $sql = "SELECT uid, token, shipping_address FROM user WHERE token = '$token'";
//     $result = $conn->query($sql);
//     if ($result->num_rows > 0) {
//         $row0 = $result->fetch_assoc();
//         $uid = $row0["uid"];
//         $address = $row0["shipping_address"];

//         $saveQuery = "INSERT INTO orders(watch_id, watch_face_id, price, cgst, sgst, qty, user_id, status, shipping_address)
//                       VALUES ('$watchId', '$watchFaceId', '$price', '$cgst', '$sgst', '$qty', '$uid', 'Ordered', '$address')";
        
//         if ($conn->query($saveQuery) === TRUE) {
//             // Update the quantity in the watch table
//             $updateQuery = "UPDATE watch SET is_available = is_available - $qty WHERE watch_id = $watchId";
            
//             if ($conn->query($updateQuery) === TRUE) {
//                 $res["success"] = true;
//                 $res["msg"] = "Order Placed Successfully and Quantity Updated";
//                 $res["data"] = [];
//             } else {
//                 $res["success"] = true; // Order placed, but quantity update failed
//                 $res["msg"] = "Order Placed Successfully, but failed to update quantity";
//                 $res["data"] = [];
//             }

//             $out = json_encode($res);
//             echo $out;
//         } else {
//             $res["success"] = false;
//             $res["msg"] = "Something went wrong";
//             $res["data"] = [];
//             $out = json_encode($res);
//             echo $out;
//         }

//     } else {
//         $res["success"] = false;
//         $res["msg"] = "Not a Valid token";
//         $res["data"] = [];

//         $out = json_encode($res);
//         echo $out;
//     }
// }
// $conn->close();


require ("./conn.php");
date_default_timezone_set('Asia/Kolkata');
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array
$token = $input["token"];
$watchId = json_encode($input["wid"]);
$watchFaceId = json_encode($input["wfid"]);
$price = json_encode($input["price"]);
$cgst = json_encode($input["cgst"]);
$sgst = json_encode($input["sgst"]);
$qty = json_encode($input["qty"]);

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Method Not Allowed";
} else {
$idd=0;
    $sql = "SELECT uid,token,shipping_address FROM user WHERE token = '$token'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row0 = $result->fetch_assoc();
        $uid = $row0["uid"];
        $address = $row0["shipping_address"];



        $saveQuery = "INSERT INTO orders(watch_id, watch_face_id, price, cgst, sgst, qty, user_id, status,shipping_address)
         VALUES ('$watchId','$watchFaceId','$price','$cgst','$sgst','$qty','$uid','Ordered','$address')";
        if ($conn->query($saveQuery) === TRUE) {
            $idd = $conn->insert_id;

            $widArray = json_decode($watchId, TRUE);
            $qtyArray = json_decode($qty, TRUE);
            $count = 0;
            for ($i = 0; $i < sizeof($widArray); $i++) {
                $getSQL = "SELECT is_available FROM watch WHERE wid=?";
                $query = $conn->prepare($getSQL);
                $query->bind_param("i", $widArray[$i]);
                try {
                    $query->execute();
                    $t = date("Y-m-d H:i:s");
                    $result = $query->get_result();
                    $is_available = $result->fetch_assoc();
                    $is_available = $is_available["is_available"] - $qtyArray[$i];
                    $sql = "UPDATE watch SET is_available=?,updated_at=? WHERE wid= ?";
                    $query = $conn->prepare($sql);
                    $query->bind_param("isi", $is_available, $t, $widArray[$i]);

                    $query->execute();
                    $count++;
                } catch (\Throwable $throwable) {
                    echo $query->error;
                }

            }
            if ($count == sizeof($widArray)) {
                $res["success"] = true;
                $res["msg"] = "Order Placed Successfully";
                $res["order_id"]= $idd;
                $res["data"] = [];

                $out = json_encode($res);
                echo $out;
            } else {
                $res["success"] = false;
                $res["msg"] = "Something went wrong";
                $res["data"] = [];
                $out = json_encode($res);
                echo $out;
            }

        } else {
            $res["success"] = false;
            $res["msg"] = "Something went wrong";
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
// $t = json_decode(json_encode($price));

// $index = 0;
// foreach ($t as $key => $val) {
//     echo "data is ".$t[$index]."\n";
//     $index++;
// }
?>
