<?php

header('Content-type: text/plain; charset=utf-8');

require __DIR__ . '/vendor/autoload.php';

$server = new \vakata\websocket\Server('wss://philcooper.org:8080', 'rumorsmatrix.pem');
$server->tick_timestamp = time();

function broadcast($server, $message) {

	foreach($server->getClients() as $client) {
		$server->send($client['socket'], $message);
	}

};


// this handler will forward each message to all clients (except the sender)
$server->onMessage(function ($sender, $message, $server) {

	echo "Recieved: [" . (int)$sender['socket'] .  "]: " . $message . "\n";

    foreach ($server->getClients() as $client) {
        if ((int)$sender['socket'] !== (int)$client['socket']) {
            $server->send($client['socket'], $message);
        }
    }

});



$server->validateClient(function($client, $server) {

	print_r($client);

	echo "Validating client: " . (int)$client['socket'] . ": ";

	if (
		($client['headers']['origin'] !== 'https://rumorsmatrix.com') ||
		($client['cookies']['ws_session'] !== 'vz3YZdhNAiqP')
	) {


		echo "declined.\n";
		return false;
	}

	echo "accepted.\n";
	return true;
});



$server->onConnect(function($client, $server) {

	echo "Connected: " . (int)$client['socket'] . "\n";

	$message = ['message' => 'hello'];
	$message = json_encode($message);

	$result = $server->send($client['socket'], $message);
});


$server->onTick(function($server) {

	if (time() > $server->tick_timestamp) {

		broadcast($server, $server->tick_timestamp);
		$server->tick_timestamp = time();
	}


});




echo "Listening...\n";
$server->run();
