<?php
/**
  From: http://www.daniweb.com/web-development/php/threads/366489
  Also see http://en.wikipedia.org/wiki/Point_in_polygon

Left Top 30.424293251130738,69.30218465576174
Rigth Top 30.41615162228836,69.41307790527345

Left Botoom 30.353660532755036,69.28845174560549
Right Botoom 30.353956793288877,69.41307790527345
*/

/*




[{"type":"CIRCLE","id":null,"radius":4528.602553681176,"geometry":[30.453035169238767,69.22150380859377]}]
*/

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

//{"type":"MARKER","id":null,"geometry":[30.394417550863064,69.24656636962892]}]
echo isInsideCircle(30.412427392217,69.2422748352051,2609.8046979660,30.400465696318,69.26026446244);
?>

