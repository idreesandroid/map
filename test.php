<?php
$servername = "localhost";
$username = "root";
$password = "";
$conn = new PDO("mysql:host=$servername;dbname=milkman", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


?>
<!DOCTYPE html>
<html>
  <head>
    <title>Info Window With maxWidth</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA4d_ChkEg7E_k9rU7zPt09FVPGKpL1aAE&callback=initializeMap&libraries=&v=weekly"
      defer
    ></script>
    <style type="text/css">
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #mapIn {
        height: 100%;
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
      // This example displays a marker at the center of Australia.
      // When the user clicks the marker, an info window opens.
      // The maximum width of the info window is set to 200 pixels.
      function initializeMap() {
        
        const mapIn = new google.maps.Map(document.getElementById("mapIn"), {
          zoom: 4,
          center: new google.maps.LatLng(30.3753, 69.3451),
        });
        const contentString =
          '<div id="content">' +
          '<div id="siteNotice">' +
          "</div>" +
          '<h1 id="firstHeading" class="firstHeading">Uluru</h1>' +
          '<div id="bodyContent">' +
          "<p><b>Uluru</b>, also referred to as <b>Ayers Rock</b>, is a large " +
          "sandstone rock formation in the southern part of the " +
          "Northern Territory, central Australia. It lies 335&#160;km (208&#160;mi) " +
          "south west of the nearest large town, Alice Springs; 450&#160;km " +
          "(280&#160;mi) by road. Kata Tjuta and Uluru are the two major " +
          "features of the Uluru - Kata Tjuta National Park. Uluru is " +
          "sacred to the Pitjantjatjara and Yankunytjatjara, the " +
          "Aboriginal people of the area. It has many springs, waterholes, " +
          "rock caves and ancient paintings. Uluru is listed as a World " +
          "Heritage Site.</p>" +
          '<p>Attribution: Uluru, <a href="https://en.wikipedia.org/w/index.php?title=Uluru&oldid=297882194">' +
          "https://en.wikipedia.org/w/index.php?title=Uluru</a> " +
          "(last visited June 22, 2009).</p>" +
          "</div>" +
          "</div>";
        const infowindow = new google.maps.InfoWindow({
          content: contentString,
          maxWidth: 400,
        });
        const marker = new google.maps.Marker({
          position: new google.maps.LatLng(30.3753, 69.3451),
          mapIn,
          title: "Uluru (Ayers Rock)",
        });
        marker.addListener("click", () => {
          infowindow.open(mapIn, marker);
        });
      }

      function getMap(){
  var id = jQuery("#getMap").val();
  jQuery.ajax({
      url: 'process.php',  
      data: {id : id, type : 'getMap' },
      type: 'post',
      async: false,
      success: function (msg) {
        $('#data').val('');
          $('#data').val(msg);
      }           
  });
}
    </script>
  </head>
  <body>
    <div class="map" id="mapIn"></div>
<div style="text-align:center">
  <!-- <a href="http://jsfiddle.net/doktormolle/EdZk4/">[source]</a> -->
  <input id="clear_shapes" value="clear shapes" type="button" />
  <input id="myTestButton" value="My Test Button" type="button" />
  <!-- <input id="save_encoded" value="save encoded(IO.IN(shapes,true))" type="button" /> -->
  <!-- <input id="save_raw_map" value="save raw(IO.IN(shapes,false))" type="button" /> -->
  <input type="button" id="save_raw_map" value="save">
  <input id="data" value="" style="width:100%" readonly />
  <!-- <input id="restore" value="restore(IO.OUT(array,map))" type="button" /> -->
  <input id="restore" value="restore" type="button" />
<?php

$sql = "SELECT id FROM google_map";  

    $stmt = $conn->prepare($sql);
    $stmt->execute();  
    $stmt->setFetchMode(PDO::FETCH_ASSOC); 

    $result = $stmt->fetchAll();
    $count = 0;

?>
  <select id="getMap" name="mapId" onchange="getMap()">
    <?php foreach($result as $item) { $count++;?>     
    
    <option value="<?= $item['id'];?>"><?= $count;?></option>
    <?php } ?>
  </select>
</div>
  </body>
</html>