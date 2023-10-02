<?php
    session_start();
    require '../dbconection.php';

if (!isset($_SESSION["username"]) || $_SESSION["admin"] != 1) {
    header("Location: ../home.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>services_type</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <h2>Welcome, <?php echo $_SESSION["username"]; ?>!</h2>
        <?php require '../components/sidebar_admin_in folder.php'; ?>
        <div class="content">
            <h2> Services</h2>
            <a href="add_services_type.php">Add new services type</a>
            <table>
                <tr>
                    <th>Naam</th>
                </tr>
                <?php
                $sql = "SELECT * FROM services";
                $result = $conn->query($sql);

                if ($result) {
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) { // Use PDO's FETCH_ASSOC
                        echo "<tr>";
                        echo "<td>" . $row["name"] . "</td>";
                        echo "<td>" . $row["description"] . "</td>";
                        echo "<td><a href='edit_services.php?id=" . $row["id"] . "'>Edit</a> <a href='delete_services.php?id=" . $row["id"] . "'>Delete</a></td>";
                        echo "</tr>";
                    }
                }
                ?>

            </table>
        </div>
    </body>
</html>
