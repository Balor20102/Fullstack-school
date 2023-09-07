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
        <title>Add bungalow</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <h2>Welcome, <?php echo $_SESSION["username"]; ?>!</h2>
        <?php require '../components/sidebar_admin_in folder.php'; ?>
        <div class="content">
            <h2>Add bungalow </h2>
            <form method="post" enctype="multipart/form-data"><br>
                <label for="name">Name:</label><br>
                <input type="text" name="name" id="name" required><br>
                <label for="type">Type:</label><br>
                <select name="type" id="type">
                    <?php
                    $sql = "SELECT * FROM bungalow_type";
                    $result = $conn->query($sql);

                    if ($result) {
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) { // Use PDO's FETCH_ASSOC
                            echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
                        }
                    }
                    ?>
                </select><br>
                <label for="price">Price:</label><br>
                <input type="number" step='any' name="price" id="price" required><br>
                <label for="services">Services:</label><br> 
                <?php
                    $sql = "SELECT * FROM services";
                    $result = $conn->query($sql);

                    if($result){
                        while($row = $result->fetch(PDO::FETCH_ASSOC)){
                            echo "<input type='checkbox' name='services[]' value='" . $row["id"] . "'>" . $row["name"] . "<br>";
                        }
                    }
                ?>
                <label for="photo">Photo:</label><br>
                <input type="file" name="photo" id="photo" required><br>
                <input type="submit" name="submit" value="Add"> <!-- Added name attribute to the submit button -->
            </form>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $name = $_POST["name"];
                $price = $_POST["price"];
                $type = $_POST["type"];
                $services = $_POST["services"];


                $target_dir = "../uploads/";
                
                $currentDateTime = date('Y-m-d-H-i-s');
                $originalFileName = basename($_FILES["photo"]["name"]);
                $imageFileType = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
                $uniqueFileName = $currentDateTime . "." . mt_rand(100,999). "." . $imageFileType;

                $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
                if (!in_array($imageFileType, $allowedTypes)) {
                    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";

                }else{
                    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_dir . $uniqueFileName)){
                        echo "The file " . $uniqueFileName . " has been uploaded.";
                        $photo = $uniqueFileName;
                    }else{
                        echo "Sorry, there was an error uploading your file.";
                    }
                }

                $sql = "INSERT INTO bungalows (name, price, type, photo) VALUES (:name, :price, :type, :photo)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':type', $type);
                $stmt->bindParam(':photo', $photo);
                $stmt->execute();

                $ID_bungalow = $conn->lastInsertId();

                foreach ($services as $service) {
                    $sql = "INSERT INTO services_has_bungalows (bungalows_idbungalows , Services_id) VALUES (:bungalow, :service)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':bungalow', $ID_bungalow);
                    $stmt->bindParam(':service', $service);
                    $stmt->execute();
                }

                header("Location: bungalow.php");
            }
            ?>
        </div>
    </body>
</html>