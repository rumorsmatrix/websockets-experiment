<?php

require __DIR__ . '/vendor/autoload.php';

// this handler will echo each message to standard output
$client = new \vakata\websocket\Client('wss://philcooper.org:8080');
$client->onMessage(function ($message, $client) {

	echo $message . "\r\n";
});


$client->run();
