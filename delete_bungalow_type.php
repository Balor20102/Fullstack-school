<?php
    session_start();
    require 'dbconection.php';

if (!isset($_SESSION["username"]) || $_SESSION["admin"] != 1) {
    header("Location: home.php");
    exit();
}


$id = $_GET["id"];

$stmt = $conn->prepare("DELETE FROM typen WHERE id = :id");

// Bind the ID value to the placeholder
$stmt->bindParam(':id', $id, PDO::PARAM_INT);

// Execute the query
$stmt->execute();

if ($stmt->rowCount() > 0) {
    echo "Delete successful!";
    header("Location: bungalow_type.php");
} else {
    echo "Delete failed!";
}
?>