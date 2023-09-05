<?php
// dbconection.php
// This file is used to connect to the database

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vakantiepark";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>