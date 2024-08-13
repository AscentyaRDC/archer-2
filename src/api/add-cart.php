<?php
// require('./conn.php');

// $inputJSON = file_get_contents('php://input');
// $input = json_decode($inputJSON, TRUE); //convert JSON into array


// $token = $input["token"];
// $wid = $input["wid"];
// $wfid = $input["wfid"];
// $price = $input["price"];

// if ($_SERVER['REQUEST_METHOD'] != 'POST') {
//     echo "Method Not Allowed";
// } else {
//     $sql = "SELECT `uid`,`token` FROM `user` WHERE `token` = '$token'";
//     $result = $conn->query($sql);
//     if ($result->num_rows > 0) {
//         $tes = $result->fetch_assoc();
//         $mobile = $tes["uid"];

//         $sql = "SELECT * FROM `cart_items` WHERE `user_id` = '$mobile'";
//         $result = $conn->query($sql);

//         if ($result->num_rows > 0) {
//             $current = $result->fetch_assoc();
//             $cartDetail = json_decode($current["cart_detail"], true);
//             $test["user_id"] = $mobile;
//             $test["wid"] = $wid;
//             $test["wfid"] = $wfid;
//             $test["price"] = $price;


//             array_push($cartDetail, $test);
//             $cartDetail = json_encode($cartDetail);


//             $sql = "UPDATE `cart_items` SET `cart_detail`='$cartDetail' WHERE `user_id` = '$mobile'";

//             if ($conn->query($sql) === TRUE) {
//                 $res["success"] = true;
//                 $res["msg"] = "Item Added to Cart Successfully";
//                 $res["data"] = [];

//                 $out = json_encode($res);
//                 echo $out;
//             } else {
//                 $res["success"] = true;
//                 $res["msg"] = "something went wrong";
//                 $res["data"] = [];

//                 $out = json_encode($res);
//                 echo $out;
//             }
//         } else {
//             $test["user_id"] = $mobile;
//             $test["wid"] = $wid;
//             $test["wfid"] = $wfid;
//             $test["price"] = $price;

//             $array = array();
//             array_push($array, $test);
//             $array = json_encode($array);

//             $sql = "INSERT INTO `cart_items`(`user_id`,`cart_detail`) VALUES ('$mobile','$array')";

//             if ($conn->query($sql) === TRUE) {
//                 $res["success"] = true;
//                 $res["msg"] = "Item Added to Cart Successfully";
//                 $res["data"] = [];

//                 $out = json_encode($res);
//                 echo $out;
//             } else {

//                 $res["success"] = true;
//                 $res["msg"] = "something went wrong";
//                 $res["data"] = [];

//                 $out = json_encode($res);
//                 echo $out;
//             }
//         }




//     } else {
//         $res["success"] = false;
//         $res["msg"] = "Not a Valid token";
//         $res["data"] = [];

//         $out = json_encode($res);
//         echo $out;
//     }



// }

require ('./conn.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode([
        'success' => false,
        'msg' => 'Method Not Allowed',
        'data' => []
    ]);
    exit;
}

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array

$token = $input["token"];
$wid = $input["wid"];
$wfid = $input["wfid"];
$price = $input["price"];

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT `uid`, `token` FROM `user` WHERE `token` = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $tes = $result->fetch_assoc();
    $mobile = $tes["uid"];

    $stmt = $conn->prepare("SELECT * FROM `cart_items` WHERE `user_id` = ?");
    $stmt->bind_param("s", $mobile);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $current = $result->fetch_assoc();
        $cartDetail = json_decode($current["cart_detail"], true);

        // Check if the item is already in the cart
        $itemExists = false;
        foreach ($cartDetail as $item) {
            if ($item['wid'] == $wid) {
                $itemExists = true;
                break;
            }
        }

        if ($itemExists) {
            echo json_encode([
                'success' => false,
                'msg' => 'Item already in cart',
                'data' => []
            ]);
        } else {
            $test = [
                "user_id" => $mobile,
                "wid" => $wid,
                "wfid" => $wfid,
                "price" => $price
            ];

            array_push($cartDetail, $test);
            $cartDetail = json_encode($cartDetail);

            $stmt = $conn->prepare("UPDATE `cart_items` SET `cart_detail`=? WHERE `user_id` = ?");
            $stmt->bind_param("ss", $cartDetail, $mobile);

            if ($stmt->execute()) {
                echo json_encode([
                    'success' => true,
                    'msg' => 'Item Added to Cart Successfully',
                    'data' => []
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'msg' => 'Something went wrong',
                    'data' => []
                ]);
            }
        }
    } else {
        $test = [
            "user_id" => $mobile,
            "wid" => $wid,
            "wfid" => $wfid,
            "price" => $price
        ];

        $array = [$test];
        $array = json_encode($array);

        $stmt = $conn->prepare("INSERT INTO `cart_items`(`user_id`, `cart_detail`) VALUES (?, ?)");
        $stmt->bind_param("ss", $mobile, $array);

        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'msg' => 'Item Added to Cart Successfully',
                'data' => []
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'msg' => 'Something went wrong',
                'data' => []
            ]);
        }
    }
} else {
    echo json_encode([
        'success' => false,
        'msg' => 'Not a Valid token',
        'data' => []
    ]);
}
$conn->close();
?>