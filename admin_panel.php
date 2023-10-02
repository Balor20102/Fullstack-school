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
    <?php require 'components/sidebar_admin.php'; ?>
</body>
</html>