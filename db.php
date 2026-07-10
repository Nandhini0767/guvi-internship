<?php

require 'vendor/autoload.php';

use MongoDB\Client;

$client = new Client(getenv("MONGODB_URI"));

$db = $client->guvi_internship;

?>