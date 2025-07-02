
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

    // Get POST data
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo "Username and password are required.";
        exit;
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
    echo "Database error: " . $e->getMessage();
    exit;   
}
?>
