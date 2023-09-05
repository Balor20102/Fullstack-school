<?php
    session_start();
    require 'dbconection.php';

if (!isset($_SESSION["username"]) || $_SESSION["admin"] != 1) {
    header("Location: home.php");
    exit();
}

$username = $_SESSION["username"];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
</head>
<body>
    <h2>Welcome, <?php echo $username; ?>!</h2>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="logout.php">Logout</a></li>
        <li><a href= "bungalow_type.php"> Bungalows Type</a></li>
        <li><a href= "bungalow.php">Bungalows</a></li>
        <li><a href= 'service.php'>Voorzieningen</a></li>
</body>
</html>