
<?php
$host = 'dpg-d1ijbtbipnbc73bsrb0g-a.oregon-postgres.render.com';
$port = '5432';
$dbname = 'slot_machine';
$user = 'slot_machine_user';
$password = '5xN95tqKpY4kwVCDNG4U5k9cqGE2yFoI';
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";

try {
    // Connect to PostgreSQL using PDO
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // Create SlotMachine_Accounts table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS SlotMachine_Accounts (
        id SERIAL PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
    )";
    $pdo->exec($sql);

    // Get POST data
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Basic validation
    if (empty($username) || empty($password)) {
        echo "Username and password are required.";
        exit;
    }


    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if username exists
    $stmt = $pdo->prepare("SELECT id FROM SlotMachine_Accounts WHERE username = :username");
    $stmt->execute(['username' => $username]);

    if ($stmt->rowCount() > 0) {
        echo "Username already exists.";
    } else {
        // Insert new user
        $stmt = $pdo->prepare("INSERT INTO SlotMachine_Accounts (username, password) VALUES (:username, :password)");
        if ($stmt->execute(['username' => $username, 'password' => $hashedPassword])) {
            echo "Sign up successful!";
        } else {
            echo "Error during sign up.";
        }
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit;
}
?>
