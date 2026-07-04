<?php

require '../vendor/autoload.php';

use Predis\Client;

try {

    $redis = new Client();

    // set test value
    $redis->set("test_key", "Redis is working!");

    // get test value
    echo $redis->get("test_key");

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

?>