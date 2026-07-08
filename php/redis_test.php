<?php

require '../vendor/autoload.php';

use Predis\Client;

try {

     $redis = new Client([
    'scheme' => 'tcp',
    'host' => 'autumnal-betterment-persistent-56481.db.redis.io',
    'port' => 19373,
    'username' => 'GUVI-Internship-Redis',
    'password' => 'wWH1TmY826jHIcBaD1Ndd0Fnp8kfunLe',
]);

    // set test value
    $redis->set("test_key", "Redis is working!");

    // get test value
    echo $redis->get("test_key");

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

?>