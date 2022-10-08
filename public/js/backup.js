var updated = false;
	var latitude = 0;
	var longitude = 0;
	var status = false;
	var mqtt;
	var reconnectTimeout = 2000;
	var host = "140.238.201.233"; //change this
	// var host="test.mosquitto.org";
	// var host="localhost";//change this
	// var host="pi2.local"; //change this
	//var host="ws6.local";//change this
	//var host="iot.eclipse.org"
	//var port=80
	// var port=8083;
	var port = 8090;

	function onFailure(message) {
		console.log("Connection Attempt to Host " + host + "Failed");
		setTimeout(MQTTconnect, reconnectTimeout);
	}

	function onMessageArrived(msg) {
		out_msg = msg.payloadString;
		console.log(out_msg);
		if(msg.payloadString == "Hello World") {} else {
			var arraymsg = msg.payloadString.split('/');
			const myId = document.getElementById("iddev"); 
			let iddev = myId.getAttribute("value");
			if(iddev == arraymsg[0]){
				var latlon = arraymsg[1].split(',');
				latitude = latlon[0];
				longitude = latlon[1];
				updated = true;
			}
			
		}
	}

	function onConnect() {
		// Once a connection has been made, make a subscription and send a message.
		console.log("Connected ");
		mqtt.subscribe("veco/v1/gps");
		// message = new Paho.MQTT.Message("6.234234,0.12323");
		// message.destinationName = "sensor2";
		// message.retained=true;
		// mqtt.send(message);
	}

	function MQTTconnect() {
		// console.log("connecting to "+ host +" "+ port);
		var x = Math.floor(Math.random() * 10000);
		var cname = "orderform-" + x;
		mqtt = new Paho.MQTT.Client(host, port, cname);
		//document.write("connecting to "+ host);
		var options = {
			timeout: 3,
			onSuccess: onConnect,
			onFailure: onFailure,
			userName: "admin_veco",
			password: "@veco147"
		};
		mqtt.onMessageArrived = onMessageArrived
		mqtt.connect(options); //connect
	}
	function buka(){
		const myId = document.getElementById("iddev"); 
		let iddev = myId.getAttribute("value");
		// var topic = "veco/vecov1/command";
		// var sendmessage = iddev+"/"+"buka_device";
		message = new Paho.MQTT.Message("buka_device"+"/"+iddev);
		message.destinationName = "veco/vecov1/command";
		message.retained=true;
		mqtt.send(message);
	}

	function kunci(){
		const myId = document.getElementById("iddev"); 
		let iddev = myId.getAttribute("value");
		// var topic = "veco/vecov1/command";
		// var sendmessage = iddev+"/"+"buka_device";
		message = new Paho.MQTT.Message("kunci_device"+"/"+iddev);
		message.destinationName = "veco/vecov1/command";
		message.retained=true;
		mqtt.send(message);
	}