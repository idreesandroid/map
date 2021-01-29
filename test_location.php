<?php
/**
  From: http://www.daniweb.com/web-development/php/threads/366489
  Also see http://en.wikipedia.org/wiki/Point_in_polygon


[{"type":"RECTANGLE","id":null,"geometry":[[30.356771223617116,69.30218465576174],[30.423997203802255,69.43470723876955]]},

{"type":"MARKER","id":null,"geometry":[30.424293251130738,69.30218465576174]}]

[{"type":"RECTANGLE","id":null,"geometry":[[30.356771223617116,69.30218465576174],[30.423997203802255,69.43470723876955]]},{"type":"MARKER","id":null,"geometry":[30.424293251130738,69.30218465576174]},{"type":"MARKER","id":null,"geometry":[30.424293251130738,69.36878927001955]}]

[{"type":"RECTANGLE","id":null,"geometry":[[30.356771223617116,69.30218465576174],[30.423997203802255,69.43470723876955]]},{"type":"MARKER","id":null,"geometry":[30.424293251130738,69.30218465576174]},{"type":"MARKER","id":null,"geometry":[30.424293251130738,69.36878927001955]},{"type":"MARKER","id":null,"geometry":[30.424293251130738,69.43470723876955]}]


[{"type":"RECTANGLE","id":null,"geometry":[[30.356771223617116,69.30218465576174],[30.423997203802255,69.43470723876955]]},{"type":"MARKER","id":null,"geometry":[30.424293251130738,69.30218465576174]},{"type":"MARKER","id":null,"geometry":[30.424293251130738,69.36878927001955]},{"type":"MARKER","id":null,"geometry":[30.424293251130738,69.43470723876955]},{"type":"MARKER","id":null,"geometry":[30.39438793530168,69.26029927978517]}]



Left Top 30.424293251130738,69.30218465576174
Rigth Top 30.41615162228836,69.41307790527345

Left Botoom 30.353660532755036,69.28845174560549
Right Botoom 30.353956793288877,69.41307790527345
*/

/*


[{"type":"POLYGON","id":null,"geometry":[[[30.39053807085073,69.30802114257814],[30.39231495021965,69.36501271972658],[30.35706747473122,69.36363942871095],[30.35143855015696,69.32312734375002]]]},{"type":"MARKER","id":null,"geometry":[30.410970232086026,69.24690969238283]},{"type":"MARKER","id":null,"geometry":[30.368620568355308,69.34647329101564]}]


[{"type":"RECTANGLE","id":null,"geometry":[[30.356771223617116,69.30218465576174],[30.423997203802255,69.43470723876955]]},{"type":"MARKER","id":null,"geometry":[30.424293251130738,69.30218465576174]},{"type":"MARKER","id":null,"geometry":[30.424293251130738,69.36878927001955]},{"type":"MARKER","id":null,"geometry":[30.424293251130738,69.43470723876955]},{"type":"MARKER","id":null,"geometry":[30.39438793530168,69.26029927978517]},{"type":"MARKER","id":null,"geometry":[30.39616474464349,69.35368306884767]},{"type":"MARKER","id":null,"geometry":[30.388465004073456,69.39488179931642]},{"type":"MARKER","id":null,"geometry":[30.350549742989077,69.32278402099611]}]

*/

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
?>

