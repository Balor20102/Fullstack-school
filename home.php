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
                    echo "<li><a href='login/logout.php'>Logout</a></li>";
                }else {
                    echo "<li><a href='login/login.php'>Login</a></li>";
                }
            ?>
        </ul>

    </aside>
    <main>
        <h2>bungalow</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>bungalow type</th>
                <th>Price</th>
                <th>Photo</th>
                <th>Services</th>
                <th>to book</th>
            </tr>
            <?php
            $sql = "SELECT * FROM bungalows ORDER BY name ASC ";
            $result = $conn->query($sql);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $row["name"] . "</td>";

                $type = $row["type"];
                $sql2 = "SELECT * FROM bungalow_type WHERE id = ?";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->execute([$type]);
                $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                // Use the correct column name from bungalow_type to fetch data

                echo "<td>" . $row2["name"] . "</td>";
                echo "<td>" . $row["price"] . "</td>";
                echo "<td><img src='uploads/" . $row['photo'] . "' width= 100px height= 100px></td>";
                echo "<td> <ul>";
                $id = $row["id"];
                $sql3 = "SELECT * FROM services_has_bungalows WHERE bungalows_idbungalows = ?";
                $stmt3 = $conn->prepare($sql3);
                $stmt3->execute([$id]);
                while ($row3 = $stmt3->fetch(PDO::FETCH_ASSOC)) {
                    $service_id = $row3["Services_id"];
                    $sql4 = "SELECT * FROM services WHERE id = ?";
                    $stmt4 = $conn->prepare($sql4);
                    $stmt4->execute([$service_id]);
                    $row4 = $stmt4->fetch(PDO::FETCH_ASSOC);
                    echo "<li>". $row4["name"] . "</li>";
                }

                echo "</ul></td>";
                echo "<td><a href='#'>to book</a></td>";
                echo "</tr>";
            }
            ?>
        </table>
</body>