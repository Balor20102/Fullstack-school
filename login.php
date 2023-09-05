<?php
    session_start();
    require 'dbconection.php';
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Replace these with your actual credentials validation logic

    $username = $_POST["username"];
    $password = $_POST["password"];

        // Query the database for the user
        $sql = "SELECT id, username, password, admin FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch();

    // Check if the user exists and verify the password
    if ($user && password_verify($password, $user["password"]) && $user["admin"] == 1) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["admin"] = $user["admin"];
        header("Location: home.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php if (isset($error)) { ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php } ?>
    <form action="login.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Login">
    </form>
</body>
</html>
