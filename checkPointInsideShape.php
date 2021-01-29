<?php

//==========================for rectange====================
$vertices_x = array(30.437614451066665, 30.44294242158493,30.404159986680934, 30.362696075435398, 30.339883427298073,30.376025679741023,30.402975547683376, 30.418076068592473); // x-coordinates of the vertices of the polygon
$vertices_y = array(69.26064260253908,69.35093648681642, 69.44191701660158, 69.43058736572267, 69.38767202148439,69.2565227294922,69.24690969238283, 69.25034291992189); // y-coordinates of the vertices of the polygon
$points_polygon = count($vertices_x); // number vertices
//$longitude_x = $_GET["longitude"]; // x-coordinate of the point to test
//$latitude_y = $_GET["latitude"]; // y-coordinate of the point to test
//// For testing.  This point lies inside the test polygon.
$longitude_x = 30.400310507409205;
$latitude_y = 69.37393911132814;



function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)
{
  $i = $j = $c = 0;
  for ($i = 0, $j = $points_polygon-1 ; $i < $points_polygon; $j = $i++) {
    if ( (($vertices_y[$i] > $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
    ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i]) ) ) 
        $c = !$c;
  }
  return $c;
}


echo (is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)) ? "Is in polygon!" : "Is not in polygon";




//==========================for circle====================


function getDistanceBetweenPoints($lat1, $lon1, $lat2, $lon2) {
    $theta = $lon1 - $lon2;
    $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
    $miles = acos($miles);
    $miles = rad2deg($miles);
    $miles = $miles * 60 * 1.1515;
    $kilometers = $miles * 1.609344;
    return $kilometers;
}


function isInsideCircle($center_x,$center_y,$radius, $x, $y){ 

  $addDiffSqr = getDistanceBetweenPoints($center_x, $center_y, $x, $y);

  $sqrt = sqrt($addDiffSqr);

  if($sqrt < ($radius / 1000)){
      return 'In Side of Cirlce'; // Inside
  } else {
      return 'Out Side of Circle'; // Outside
  }
}


echo isInsideCircle(33.77227715004051,73.89549136117091,1885.244442099549,33.77827009375648,73.904331922084);


//=========================for rectangle=====================



$vertices_x = array(30.356771223617116,30.423997203802255,30.356771223617116,30.423997203802255); // x-coordinates of the vertices of the polygon
$vertices_y = array(69.30218465576174,69.43470723876955,69.43470723876955,69.30218465576174); // y-coordinates of the vertices of the polygon
$points_polygon = count($vertices_x); // number vertices
//$longitude_x = $_GET["longitude"]; // x-coordinate of the point to test
//$latitude_y = $_GET["latitude"]; // y-coordinate of the point to test
//// For testing.  This point lies inside the test polygon.
 $longitude_x = 30.350549742989077;
 $latitude_y = 69.32278402099611;

if (is_in_rectange($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)){
  echo "Is in rectange!";
}
else echo "Is not in rectange";


function is_in_rectange($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)
{
  $i = $j = $c = 0;
  for ($i = 0, $j = $points_polygon-1 ; $i < $points_polygon; $j = $i++) {
    if ( (($vertices_y[$i] > $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
    ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i]) ) ) 
        $c = !$c;
  }
  return $c;
}