<?php

require 'vendor/autoload.php';

use MongoDB\Client;

$client = new Client("mongodb://localhost:27017");

$db = $client->guvi_internship;

echo "MongoDB Connected Successfully";

?>