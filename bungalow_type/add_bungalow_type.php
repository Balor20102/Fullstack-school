<?php
    session_start();
    require '../dbconection.php';

if (!isset($_SESSION["username"]) || $_SESSION["admin"] != 1) {
    header("Location: ../home.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Add bungalow type</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <h2>Welcome, <?php echo $_SESSION["username"]; ?>!</h2>
        <?php require '../components/sidebar_admin_in folder.php'; ?>
        <div class="content">
            <h2>Add bungalow type</h2>
            <form action="add_bungalow_type.php" method="post">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required>
                <input type="submit" name="submit" value="Add"> <!-- Added name attribute to the submit button -->
            </form>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $name = $_POST["name"];

                $sql = "INSERT INTO bungalow_type (name) VALUES (?)";
                $stmt = $conn->prepare($sql);

                if ($stmt->execute([$name])) {
                    header("Location: bungalow_type.php");
                } else {
                    echo "Error: " . $sql . "<br>" . $stmt->error; // Corrected to $stmt->error
                }
            }
            ?>
        </div>
    </body>
</html>