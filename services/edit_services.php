<?php
    session_start();
    require '../dbconection.php';

if (!isset($_SESSION["username"]) || $_SESSION["admin"] != 1) {
    header("Location: ../home.php");
    exit();
}

$id = $_GET["id"];

$stmt = $conn->prepare("SELECT * FROM services WHERE id = :id");

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
        <title>Add services type</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <h2>Welcome, <?php echo $_SESSION["username"]; ?>!</h2>
        <?php require '../components/sidebar_admin_in folder.php'; ?>
        <div class="content">
            <h2>Add services type</h2>
            <form method="post" >
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required value="<?php echo htmlspecialchars($result['name']); ?>" >
                <label for="description">Description:</label>
                <input type= "text" name="description" id="description" value="<?php echo htmlspecialchars($result['description']); ?>"><br>
                <input type="submit" name="submit" value="update"> <!-- Added name attribute to the submit button -->
            </form>

        </div>
    </body>
</html>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST["name"];
        $description = $_POST["description"];
        try {
            $sql = "UPDATE services SET name = :newData, description = :newDescription WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':newData', $name, PDO::PARAM_STR);
            $stmt->bindParam(':newDescription', $description, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
            if ($stmt->execute()) {
                echo "Update successful!";
                header("Location: services.php");
            } else {
                echo "Update failed!";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
?>