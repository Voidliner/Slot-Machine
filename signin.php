<?php
$host = 'dpg-d0ti432dbo4c739nguq0-a.oregon-postgres.render.com';
$port = '5432';
$dbname = 'api_database_n9x1';
$user = 'api_database_n9x1_user';
$password = 'ZY2yoYoXypv0HYqJ6zwPxUcQhEBtQYT8';
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";

try {
    // Connect to PostgreSQL using PDO
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // Get POST data
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        die("Username and password are required.");
    }

    // Prepare and execute query to get hashed password
    $stmt = $pdo->prepare("SELECT password FROM SlotMachine_Accounts WHERE username = :username");
    $stmt->execute(['username' => $username]);

    $hashedPassword = $stmt->fetchColumn();

    if (!$hashedPassword) {
        echo "Invalid username or password.";
    } else {
        if (password_verify($password, $hashedPassword)) {
            echo "SUCCESS";
        } else {
            echo "Invalid username or password.";
        }
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
