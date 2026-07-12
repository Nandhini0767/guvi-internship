<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../vendor/autoload.php';

use MongoDB\Client;
use Predis\Client as RedisClient;

header('Content-Type: application/json');

try {

    // Redis Connection
    $redis = new RedisClient([
        'scheme' => 'tcp',
        'host' => getenv("REDISHOST"),
        'port' => getenv("REDISPORT"),
        'password' => getenv("REDISPASSWORD")
    ]);

    // Check Token
    if (!isset($_POST["token"])) {

        echo json_encode([
            "status" => "error",
            "message" => "Token missing"
        ]);
        exit;
    }

    $token = $_POST["token"];

    // Get User ID from Redis
    $user_id = $redis->get($token);

    if (!$user_id) {

        echo json_encode([
            "status" => "error",
            "message" => "Invalid session"
        ]);
        exit;
    }

    // MySQL Connection (Registered User Data)
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
            "message" => "MySQL Connection Failed"
        ]);
        exit;
    }

    // Get Name & Email from MySQL
    $stmt = $conn->prepare(
        "SELECT name, email FROM users WHERE id = ?"
    );

    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // MongoDB Connection (Profile Data)
    $client = new Client(getenv("MONGODB_URI"));

    $database = $client->guvi;
    $collection = $database->profile;

    // SAVE PROFILE
    if (isset($_POST["name"])) {

        $profile = [

            "user_id" => $user_id,

            "name" => $_POST["name"],

            "age" => $_POST["age"],

            "dob" => $_POST["dob"],

            "contact" => $_POST["contact"]

        ];

        $collection->updateOne(

            ["user_id" => $user_id],

            ['$set' => $profile],

            ["upsert" => true]

        );

        echo json_encode([
            "status" => "success",
            "message" => "Profile saved successfully!"
        ]);

        exit;
    }

    // LOAD PROFILE
    $profile = $collection->findOne([
        "user_id" => $user_id
    ]);

    echo json_encode([

        "status" => "success",

        "email" => $user["email"] ?? "",

        "name" => $user["name"] ?? "",

        "age" => $profile["age"] ?? "",

        "dob" => $profile["dob"] ?? "",

        "contact" => $profile["contact"] ?? ""

    ]);

    $stmt->close();
    $conn->close();

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);

}

?>