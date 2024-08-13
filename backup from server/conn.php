<?php
$servername = "localhost";
$username = "u702069051_archer";
$password = "Ascentya@2023";
$dbname = "u702069051_archer";
// Create connection
$conn = new mysqli($servername, $username, $password,$dbname);
// $base = 'https://techbylanz.com/archer-new/src/api';
// $base = 'https://techbylanz.com/archer-new/src/api';
$base = 'https://sreethaiindustries.com/archer-new/src/api';


// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully.";
?>