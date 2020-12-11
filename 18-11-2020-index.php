<!DOCTYPE html>
<html>
  <head>
    <title>Simple Map</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA4d_ChkEg7E_k9rU7zPt09FVPGKpL1aAE&callback=initMap&libraries=&v=weekly"
      defer
    ></script>
    <style type="text/css">
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 600px;
        width: 600px;
      }

      /* Optional: Makes the sample page fill the window. */
      html,
      body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
    <script>
     let map;

    function initMap() {

      	var options = {
      		center: { lat: 42.3601, lng: -71.0589 },
          	zoom: 8,
      	}

        var map = new google.maps.Map(document.getElementById("map"), options);

        google.maps.event.addListener(map,'click', function(event){
        	addMarker({coords:event.latLng});
        });

        const triangleCoords = [
          { lat: 41.3601, lng: -70.0589 },
          { lat: 40.466, lng: -60.118 },
          { lat: 38.321, lng: -62.757 },
          { lat: 41.3601, lng: -70.0589 },
        ];
        // Construct the polygon.
        const bermudaTriangle = new google.maps.Polygon({
          paths: triangleCoords,
          strokeColor: "#FF0000",
          strokeOpacity: 0.8,
          strokeWeight: 2,
          fillColor: "#FF0000",
          fillOpacity: 0.35,
        });
        bermudaTriangle.setMap(map);
       
        addMarker(
        {
        	coords : {lat:42.968,lng:-70.9415 }, 
        	icon : 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png', 
        	content : '<h1>idrees</h1>'
        });
        addMarker({coords : {lat:42.918,lng:-70.9495 }});
        addMarker({coords : {lat:42.998,lng:-70.9455 }});

        

        function addMarker(props){
        	var marker = new google.maps.Marker({
        	map: map,
        	position : props.coords,
        	icon : props.icon,
        	content : props.content
        });
        }
        if(props.icon){
        	marker.setIcon(props.icon);
        }

        if(props.content){
        	  const infowindow = new google.maps.InfoWindow({
			    content: "<p>Marker Location:" + marker.getPosition() + "</p>",
		  });
		  google.maps.event.addListener(marker, "click", () => {
		    infowindow.open(map, marker);
		  });
        }
      }
    </script>
  </head>
  <body>
    <div id="map"></div>
  </body>
</html>
