<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../vendor/autoload.php';

use Predis\Client;

header('Content-Type: application/json');

$redis = new Client();

$conn = new mysqli(
    "sql12.freesqldatabase.com",
    "sql12832550",
    "evFyLB8CHp",
    "sql12832550"
);

if ($conn->connect_error) {
    echo json_encode([
        "status" => "error",
        "message" => "DB connection failed"
    ]);
    exit;
}

$email = $_POST["email"];
$password = $_POST["password"];

$stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user["password"])) {

    $token = bin2hex(random_bytes(16));

    $redis->set($token, $user["id"]);
    $redis->expire($token, 3600);

    echo json_encode([
        "status" => "success",
        "token" => $token,
        "name" => $user["name"]
    ]);

} else {

    echo json_encode([
        "status" => "error",
        "message" => "Invalid email or password"
    ]);
}

$conn->close();

?>