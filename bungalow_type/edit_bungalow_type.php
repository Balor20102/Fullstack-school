<?php
    session_start();
    require '../dbconection.php';

if (!isset($_SESSION["username"]) || $_SESSION["admin"] != 1) {
    header("Location: ../home.php");
    exit();
}

$id = $_GET["id"];

$stmt = $conn->prepare("SELECT * FROM typen WHERE id = :id");

// Bind the ID value to the placeholder
$stmt->bindParam(':id', $id, PDO::PARAM_INT);

// Execute the query
$stmt->execute();

// Fetch the result
$result = $stmt->fetch(PDO::FETCH_ASSOC);
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
        <?php require '../components/sidebar_admin.php'; ?>
        <div class="content">
            <h2>Add bungalow type</h2>
            <form method="post" >
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required value="<?php echo htmlspecialchars($result['name']); ?>" >
                <input type="submit" name="submit" value="update"> <!-- Added name attribute to the submit button -->
            </form>

        </div>
    </body>
</html>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST["name"];
        try {
            $sql = "UPDATE typen SET name = :newData WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':newData', $name, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
            if ($stmt->execute()) {
                echo "Update successful!";
                header("Location: bungalow_type.php");
            } else {
                echo "Update failed!";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
?>