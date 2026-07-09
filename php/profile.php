<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../vendor/autoload.php';

use MongoDB\Client;
use Predis\Client as RedisClient;

header('Content-Type: application/json');

try {

    // Redis connection
    $redis = new RedisClient();

    if (!isset($_POST["token"])) {
        echo json_encode([
            "status" => "error",
            "message" => "Token missing"
        ]);
        exit;
    }

    $token = $_POST["token"];

    // Validate session
    $user_id = $redis->get($token);

    if (!$user_id) {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid session"
        ]);
        exit;
    }

    // MongoDB connection
    $client = new Client("mongodb+srv://nandhini0767_db_user:Nandhini0767@cluster0.sg6lsth.mongodb.net/?appName=Cluster0");
    $db = $client->guvi;
    $collection = $db->profile;

    // ---------- SAVE PROFILE ----------
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

    // ---------- LOAD PROFILE ----------
    $profile = $collection->findOne(["user_id" => $user_id]);

    echo json_encode([
        "status" => "success",
        "name" => $profile["name"] ?? "",
        "age" => $profile["age"] ?? "",
        "dob" => $profile["dob"] ?? "",
        "contact" => $profile["contact"] ?? ""
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
?>