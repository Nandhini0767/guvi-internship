<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../vendor/autoload.php';

use MongoDB\Client;
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


    // Check token
    if (!isset($_POST["token"])) {

        echo json_encode([
            "status" => "error",
            "message" => "Token missing"
        ]);

        exit;
    }


    $token = $_POST["token"];


    // Get user id from Redis session
    $user_id = $redis->get($token);


    if (!$user_id) {

        echo json_encode([
            "status" => "error",
            "message" => "Invalid session"
        ]);

        exit;
    }


    // MongoDB connection
    $client = new Client(
        getenv("MONGODB_URI")
    );


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