<?php
header('Content-Type: application/json');

use Predis\Client;

header('Content-Type: application/json');

$redis = new Client();

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