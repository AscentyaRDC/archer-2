<?php

require('./conn.php');
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array

$userId = $input["userId"];
$uName = $input["name"];
$email = $input["email"];
$mobile = $input["mobile"];
$token = $input["token"];
$time = date('Y-m-d h:i:s', time());

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Method Not Allowed";
}
else{
  $sql = "SELECT `mobile`,`email`,`user_id` FROM `user` where `token`!='$token'";
  $result = $conn->query($sql);
  $temp = 0;
  $repeatValue = "";
  if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      if($row["mobile"]==$mobile){
        $temp += 1;
        $repeatValue = "Mobile Number";
        break;
      }
      if($row["user_id"]==$userId){
        $temp += 1;
        $repeatValue = "User ID";
        break;
      }
      if($row["email"]==$email){
        $temp += 1;
        $repeatValue = "E-Mail Address";
        break;
      }
    }
  }
  if($temp==0){
    $sql1 = "UPDATE `user` SET `user_id`='$userId',`u_name`='$uName',`email`='$email',`mobile`='$mobile',`updated_at`='$time' WHERE `token`='$token'";

    if ($conn->query($sql1) === TRUE) {
        $res["success"] = true;
        $res["data"] = [];
        $res["msg"] = "User profile updated successfully";
        $out = json_encode($res);
        echo $out;
    }
    else{
        $res ["success"] = false;
        $res ["data"] = [];
        $res ["msg"] = "Error: " . $sql1 . "<br>" . $conn->error . "<br>";
    
        $out = json_encode($res);
        echo $out;
    }
   }
  else{
    $res ["success"] = false;
    $res ["data"] = [];
    $res ["msg"] = $repeatValue . " Already Exists";

    $out = json_encode($res);
    echo $out;
  }

}
$conn->close();
?>