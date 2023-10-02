<?php
    session_start();
    require '../dbconection.php';

if (!isset($_SESSION["username"]) || $_SESSION["admin"] != 1) {
    header("Location: ../home.php");
    exit();
}

$id = $_GET["id"];

$stmt = $conn->prepare("SELECT * FROM bungalows WHERE id = :id");

// Bind the ID value to the placeholder
$stmt->bindParam(':id', $id, PDO::PARAM_INT);

// Execute the query
$stmt->execute();

// Fetch the result
$result_edit = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>bungalow</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <h2>Welcome, <?php echo $_SESSION["username"]; ?>!</h2>
        <?php require '../components/sidebar_admin_in folder.php'; ?>
        <div class="content">
            <h2>bungalow </h2>
            <form method="post" enctype="multipart/form-data"><br>
                <label for="name">Name:</label><br>
                <input type="text" name="name" id="name" required value="<?php echo htmlspecialchars($result_edit['name']); ?>"><br>
                <label for="type">Type:</label><br>
                <select name="type" id="type">
                    <?php
                    $sql = "SELECT * FROM bungalow_type";
                    $result = $conn->query($sql);

                    if ($result) {
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) { // Use PDO's FETCH_ASSOC
                            if($row["id"] == $result_edit["type"]){
                                echo "<option value='" . $row["id"] . "' selected>" . $row["name"] . "</option>";
                            }else{
                                echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
                            }
                        }
                    }
                    ?>
                </select><br>
                <label for="price">Price:</label><br>
                <input type="number" step='any' name="price" id="price" required value="<?php echo htmlspecialchars($result_edit['price']); ?>"><br>
                <label for="services">Services:</label><br> 
                <?php
                    $sql_M2M = "SELECT * FROM services_has_bungalows WHERE bungalows_idbungalows = :id";
                    $stmt_M2M = $conn->prepare($sql_M2M);
                    $stmt_M2M->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt_M2M->execute();
                    $result_M2M = $stmt_M2M->fetchAll(PDO::FETCH_ASSOC);

                    $sql = "SELECT * FROM services";
                    $result = $conn->query($sql);

                    $services_associated = array();

                    foreach ($result_M2M as $row_M2M) {
                        $services_associated[] = $row_M2M["Services_id"];
                    }

                    if ($result) {
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            $isChecked = in_array($row["id"], $services_associated);
                            echo "<input type='checkbox' name='services[]' value='" . $row["id"] . "' " . ($isChecked ? "checked" : "") . ">" . $row["name"] . "<br>";
                        }
                    }
                ?>
                <label for="photo">Photo:</label><br>
                <img src="../uploads/<?php echo $result_edit['photo']; ?>" alt="photo" width="200" height="200"><br>
                <input type ="hidden" name="old_photo" value="<?php echo $result_edit['photo']; ?>">
                <input type="file" name="photo" id="photo" ><br>
                <input type="submit" name="submit" value="Add"> <!-- Added name attribute to the submit button -->
            </form>

            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $name = $_POST["name"];
                $price = $_POST["price"];
                $type = $_POST["type"];
                $old_photo = $_POST["old_photo"];
                
                if ($_FILES["photo"]["name"] != "") {
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

                            if (!empty($old_photo) && file_exists($target_dir . $old_photo)) {
                                unlink($target_dir . $old_photo);
                            }
                        }else{
                            echo "Sorry, there was an error uploading your file.";
                        }
                    }
                }else{
                    $photo = $old_photo;
                }

                $sql = "UPDATE bungalows SET name = :name, price = :price, type = :type, photo = :photo WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':type', $type);
                $stmt->bindParam(':photo', $photo);
                $stmt->bindParam(':id', $id); // Assuming you have the ID of the bungalow you want to update
                $stmt->execute();

                

                // Step 1: Retrieve current relationships
                $newServices = $_POST["services"];
                $currentServices = [];

                $sql_current = "SELECT Services_id FROM services_has_bungalows WHERE bungalows_idbungalows = :bungalow";
                $stmt_current = $conn->prepare($sql_current);
                $stmt_current->bindParam(':bungalow', $id, PDO::PARAM_INT);
                $stmt_current->execute();

                while ($row = $stmt_current->fetch(PDO::FETCH_ASSOC)) {
                    $currentServices[] = $row['Services_id'];
                // header("Location: bungalow.php");
                }
                
                // Step 2: Compare current relationships with new relationships
                $servicesToAdd = array_diff($newServices, $currentServices);
                $servicesToRemove = array_diff($currentServices, $newServices);


                // Step 3: Add new relationships
                foreach ($servicesToAdd as $service) {
                    $sql = "INSERT INTO services_has_bungalows (bungalows_idbungalows , Services_id) VALUES (:bungalow, :service)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':bungalow', $id);
                    $stmt->bindParam(':service', $service);
                    $stmt->execute();
                }

                // Step 4: Remove old relationships
                foreach ($servicesToRemove as $service) {
                    $sql = "DELETE FROM services_has_bungalows WHERE bungalows_idbungalows = :bungalow AND Services_id = :service";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':bungalow', $id);
                    $stmt->bindParam(':service', $service);
                    $stmt->execute();
                }

                header("Location: bungalow.php");
            }
            ?>