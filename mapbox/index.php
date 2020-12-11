<!DOCTYPE html>
<html>
<head>
	<title>map box</title>
	<script src='https://api.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.js'></script>
	<link href='https://api.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.css' rel='stylesheet' />
</head>
<body>
	<div id='map' style='width: 100%; height: 750px;'></div>
	<script>
	// TO MAKE THE MAP APPEAR YOU MUST
	// ADD YOUR ACCESS TOKEN FROM
	// https://account.mapbox.com
	mapboxgl.accessToken = 'pk.eyJ1IjoiaWRyZWVzNzg2IiwiYSI6ImNraWE5cmplYzBnaWIycW12emh0bmZyYmcifQ.HY5UVg06kQiQaWBeLWwVkA';
	var map = new mapboxgl.Map({
	container: 'map',
	style: 'mapbox://styles/mapbox/streets-v11', // stylesheet location
	center: [69.3451, 30.3753], // starting position [lng, lat]
	zoom: 5 // starting zoom
	});
	</script>

</body>
</html>