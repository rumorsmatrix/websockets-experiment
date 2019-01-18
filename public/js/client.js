class Client {

	constructor(uuid) {

		this.uuid = uuid;

		this.socket = new SimpleWebsocket('wss://rumorsmatrix.com:8080');

		this.socket.on('connect', this.onConnect );
		this.socket.on('data', function(data) {

			client.onData(data.toString());

		} );


		this.socket.on('close', function() {
			clearInterval(this.ticker);
			console.log("Disconnected.");
		})


		this.socket.on('error', function(err) {
			console.log(err);
		});

	}



	onConnect() {
		console.log('Connected.');
		this.ticker = setInterval( function() { client.tick();  } , 5000);
	}


	onData(data) {
		terminal.write(data.toString());
	}


	send(message) {
		this.socket.send(message);
	}


	tick() {
		console.log("tick");
		this.send('PING');
	}

}