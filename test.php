<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "vakantiepark";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "SELECT * FROM `users`";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo "<table><tr><th>ID</th><th>Username</th><th>Password</th><th>Role</th></tr>";
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>".$row["ID"]."</td><td>".$row["username"]."</td><td>".$row["password"]."</td><td>".$row["admin"]."</td></tr>";
        }}

?>
<body>
    <a href="home.php">Home</a>
</body>