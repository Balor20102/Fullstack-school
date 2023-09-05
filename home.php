<?php
    session_start();
    require 'dbconection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
</head>

<body>
    <h1>Home</h1>
    <aside
        <ul>
            <li>
                <a href="home.php">Home</a>
            </li>

            <?php
                if (isset($_SESSION["admin"]) && $_SESSION["admin"] == 1) {
                    echo "<li><a href='admin_panel.php'>Admin Panel</a></li>";
                }
                if (isset($_SESSION['username'])) {
                    echo "<li><a href='logout.php'>Logout</a></li>";
                }else {
                    echo "<li><a href='login.php'>Login</a></li>";
                }
            ?>
        </ul>
    </aside>
</body>