<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../vendor/autoload.php';

use Predis\Client as RedisClient;

header('Content-Type: application/json');

try {

    // Redis connection
    $redis = new RedisClient([
        'scheme' => 'tcp',
        'host' => getenv("REDISHOST"),
        'port' => getenv("REDISPORT"),
        'password' => getenv("REDISPASSWORD")
    ]);

    // MySQL connection
    $conn = new mysqli(
        getenv("MYSQLHOST"),
        getenv("MYSQLUSER"),
        getenv("MYSQLPASSWORD"),
        getenv("MYSQLDATABASE"),
        getenv("MYSQLPORT")
    );

    if ($conn->connect_error) {

        echo json_encode([
            "status" => "error",
            "message" => "MySQL connection failed: " . $conn->connect_error
        ]);
        exit;
    }

    $email = $_POST["email"];
    $password = $_POST["password"];

    // Find user
    $stmt = $conn->prepare(
        "SELECT id, name, email, password FROM users WHERE email = ?"
    );

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user["password"])) {

        // Create token
        $token = bin2hex(random_bytes(16));

        // Store session in Redis
        $redis->set($token, $user["id"]);
        $redis->expire($token, 3600);

        echo json_encode([
            "status" => "success",
            "token" => $token,
           
        ]);

    } else {

        echo json_encode([
            "status" => "error",
            "message" => "Invalid email or password"
        ]);

    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);

}

?>