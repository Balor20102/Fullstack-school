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
        <title>bungalow</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <h2>Welcome, <?php echo $_SESSION["username"]; ?>!</h2>
        <?php require '../components/sidebar_admin_in folder.php'; ?>
        <div class="content">
            <h2>Bungalow</h2>
            <a href="add_bungalow.php">Add new bungalow</a>
            <table>
                <tr>
                    <th>Naam</th>
                </tr>
                <?php
                $sql = "SELECT * FROM bungalows";
                $result = $conn->query($sql);

                if ($result) {
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) { // Use PDO's FETCH_ASSOC
                        echo "<tr>";
                        echo "<td>" . $row["name"] . "</td>";
                        echo "<td><a href='edit_bungalow.php?id=" . $row["idbungalows"] . "'>Edit</a> <a href='delete_bungalow.php?id=" . $row["idbungalows"] . "'>Delete</a></td>";
                        echo "</tr>";
                    }
                }
                ?>

            </table>
        </div>
    </body>
</html>
