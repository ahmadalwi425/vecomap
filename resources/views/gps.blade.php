<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Map Device</title>
	<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
	<link href="https://api.mapbox.com/mapbox-gl-js/v2.10.0/mapbox-gl.css" rel="stylesheet">
	<script src="https://api.mapbox.com/mapbox-gl-js/v2.10.0/mapbox-gl.js"></script>
	<style>
	body {
		overflow: hidden;
		margin: 0;
		padding: 0;
	}
	
	#map {
		position: absolute;
		top: 0;
		bottom: 0;
		width: 100%;
	}
	
	.loader,
	.loader:after {
		border-radius: 50%;
		width: 10em;
		height: 10em;
		overflow: hidden;
	}
	
	.loader {
		overflow: hidden;
		margin: 60px auto;
		font-size: 10px;
		position: relative;
		text-indent: -9999em;
		border-top: 1.1em solid rgba(255, 255, 255, 0.2);
		border-right: 1.1em solid rgba(255, 255, 255, 0.2);
		border-bottom: 1.1em solid rgba(255, 255, 255, 0.2);
		border-left: 1.1em solid #ffffff;
		-webkit-transform: translateZ(0);
		-ms-transform: translateZ(0);
		transform: translateZ(0);
		-webkit-animation: load8 1.1s infinite linear;
		animation: load8 1.1s infinite linear;
	}
	
	@-webkit-keyframes load8 {
		0% {
			-webkit-transform: rotate(0deg);
			transform: rotate(0deg);
		}
		100% {
			-webkit-transform: rotate(360deg);
			transform: rotate(360deg);
		}
	}
	
	@keyframes load8 {
		0% {
			-webkit-transform: rotate(0deg);
			transform: rotate(0deg);
		}
		100% {
			-webkit-transform: rotate(360deg);
			transform: rotate(360deg);
		}
	}
	
	.btn {
		background-color: #1d9bdf;
		color: white;
		border: 0px;
		border-radius: 10px;
		/* Font-size: 10px;
		font-style: italic; */
	}
	
	#overlay1 {
		height: 40px;
		width: 120px;
		position: absolute;
		bottom: 10pt;
		right: 10pt;
		/* z-index: 10; */
		transition: all 0.6s;
		/* cursor: url(cursors/select.PNG), pointer; */
	}
	
	#overlay2 {
		height: 40px;
		width: 120px;
		position: absolute;
		bottom: 50pt;
		right: 10pt;
		/* z-index: 10; */
		transition: all 0.6s;
		/* cursor: url(cursors/select.PNG), pointer; */
	}
	
	#overlay3 {
		height: 40px;
		width: 120px;
		position: absolute;
		bottom: 90pt;
		right: 10pt;
		/* z-index: 10; */
		background-color: rgb(203, 18, 18) !important;
		transition: all 0.6s;
		/* cursor: url(cursors/select.PNG), pointer; */
	}
	
	.loading-box-container {
		--size: 150px;
		--radius: 2000px;
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		width: var(--size);
		height: var(--size);
		padding: var(--radius);
		border-radius: var(--radius);
		overflow: hidden;
	}
	
	.loading-box {
		position: relative;
		width: 100%;
		height: 100%;
		display: flex;
		align-items: center;
		justify-content: center;
		color: #fff;
		background-image: linear-gradient(to right, #00AEED, #37B54B);
		border-radius: var(--radius);
	}
	
	.loading-box-container::before {
		content: '';
		width: 150%;
		/* The upscaling allows the box to fill its container even when rotated */
		height: 150%;
		position: absolute;
		top: -25%;
		left: -25%;
		background: conic-gradient(#0000ff00, rgb(255, 255, 255));
		animation: rotate-border 5s linear infinite;
	}
	
	@keyframes rotate-border {
		to {
			transform: rotate(360deg);
		}
	}
	
	#loadingDiv {
		position: absolute;
		;
		top: 0;
		padding-top: 200px;
		left: 0;
		width: 100%;
		height: 100%;
		background-color: rgb(18, 153, 203);
		overflow: hidden;
	}
	
	.marker {
		background-image: url('https://www.freeiconspng.com/uploads/blue-location-icon-png-19.png');
		background-size: cover;
		width: 50px;
		height: 50px;
		border-radius: 50%;
		cursor: pointer;
	}
	
	.mapboxgl-popup {
		max-width: 200px;
	}
	
	.mapboxgl-popup-content {
		text-align: center;
		font-family: 'Open Sans', sans-serif;
	}
	</style>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.js" type="text/javascript"></script>
</head>

<body>
	<script>
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
			var arraymsg = msg.payloadString.split(',');
			latitude = arraymsg[0];
			longitude = arraymsg[1];
			updated = true;
		}
	}

	function onConnect() {
		// Once a connection has been made, make a subscription and send a message.
		// console.log("Connected ");
		mqtt.subscribe("veco/vecov1/gps");
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
	</script>
	<script>
	MQTTconnect();
	</script>
	<div id="map"></div>
	<button id="overlay1" class="btn">
		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-unlock-fill" viewBox="0 0 16 16">
			<path d="M11 1a2 2 0 0 0-2 2v4a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h5V3a3 3 0 0 1 6 0v4a.5.5 0 0 1-1 0V3a2 2 0 0 0-2-2z" /> </svg> Buka Device</button>
	<button id="overlay2" class="btn">
		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
			<path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z" /> </svg> Kunci Device</button>
	<div id="waiting" class="loading-box-container">
		<div class="loading-box">
			<p>Waiting for data</p>
		</div>
	</div>
	<button id="overlay3" onclick="location.href='{{ url('logout') }}';" class="btn"> Logout</button>
	<script>
	function removeWait() {
		$("#waiting").fadeOut(500, function() {
			// fadeOut complete. Remove the loading div
			$("#waiting").remove(); //makes page more lightweight 
		});
	}
	mapboxgl.accessToken = 'pk.eyJ1IjoiYWhtYWRhbHdpNDI1IiwiYSI6ImNsODQzYXdsNDBhM2ozcHBjaWNteHM4M2wifQ.4jjOLm9wBB1cZBQRV-P0DQ';
	const map = new mapboxgl.Map({
		container: 'map',
		// Choose from Mapbox's core styles, or make your own style with Mapbox Studio
		style: 'mapbox://styles/mapbox/streets-v11',
		zoom: 16,
		projection: 'globe'
	});
	// const marker = new mapboxgl.Marker().setLngLat([latitude, longitude]).addTo(map);
	map.on('style.load', () => {
		map.setFog({});
	});
	map.on('load', async() => {
		const geojson = await getLocation();
		map.addSource('points', {
			type: 'geojson',
			data: geojson
		});
		map.loadImage('https://docs.mapbox.com/mapbox-gl-js/assets/custom_marker.png', (error, image) => {
			if(error) throw error;
			map.addImage('custom-marker', image);
			// Get the initial location of the International Space Station (ISS).
			// Add the ISS location as a source.
			// Add the rocket symbol layer to the map.
			map.addLayer({
				'id': 'points',
				'type': 'symbol',
				'source': 'points',
				'layout': {
					'icon-image': 'custom-marker',
					// get the title name from the source's "title" property
					'text-field': ['get', 'title'],
					'text-font': ['Open Sans Semibold', 'Arial Unicode MS Bold'],
					'text-offset': [0, -3],
					'text-anchor': 'top'
				}
			});
			// Update the source from the API every 2 seconds.
		});
		const updateSource = setInterval(async() => {
			const geojson = await getLocation(updateSource);
			map.getSource('points').setData(geojson);
			if(updated) {
				removeWait();
			}
		}, 2000);
		async function getLocation(updateSource) {
			// Make a GET request to the API and return the location of the ISS.
			try {
				const response = await fetch('https://api.wheretheiss.at/v1/satellites/25544', {
					method: 'GET'
				});
				// Fly the map to the location.
				map.flyTo({
					center: [longitude, latitude],
					speed: 20
				});
				// Return the location of the ISS as GeoJSON.
				return {
					'type': 'FeatureCollection',
					'features': [{
						'type': 'Feature',
						'properties': {
							'title': 'Your Vehicle'
						},
						'geometry': {
							'type': 'Point',
							'coordinates': [longitude, latitude]
						}
					}]
				};
			} catch(err) {
				// If the updateSource interval is defined, clear the interval to stop updating the source.
				if(updateSource) clearInterval(updateSource);
				throw new Error(err);
			}
		}
	});
	console.log(map.style.sourceCaches);
	</script>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
$('body').append('<div style="" id="loadingDiv"><div class="loader">Loading...</div></div>');
$(window).on('load', function() {
	setTimeout(removeLoader, 2000); //wait for page load PLUS two seconds.
});
// while(!status){
// }
// removeLoader();
function removeLoader() {
	$("#loadingDiv").fadeOut(500, function() {
		// fadeOut complete. Remove the loading div
		$("#loadingDiv").remove(); //makes page more lightweight 
	});
}
</script>

</html>