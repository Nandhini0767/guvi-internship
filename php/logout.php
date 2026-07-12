<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../vendor/autoload.php';

use Predis\Client as RedisClient;

header('Content-Type: application/json');

try {

    $redis = new RedisClient([
        'scheme' => 'tcp',
        'host' => getenv("REDISHOST"),
        'port' => getenv("REDISPORT"),
        'password' => getenv("REDISPASSWORD")
    ]);

    if (!isset($_POST["token"])) {

        echo json_encode([
            "status" => "error",
            "message" => "Token missing"
        ]);

        exit;
    }

    $token = $_POST["token"];

    // Delete session from Redis
    $redis->del([$token]);

    echo json_encode([
        "status" => "success",
        "message" => "Logged out successfully"
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);

}

?>