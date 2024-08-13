<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "archer";
// Create connection
$conn = new mysqli($servername, $username, $password,$dbname);
// $base = 'https://techbylanz.com/archer-new/src/api';
// $base = 'https://sreethaiindustries.com/archer-new/src/api';
// $base = 'http://localhost/archer-new/src/api';
$base = 'http://localhost/archer-2/src/api';



// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully.";
?>