<?php
require("db.php");

// Gets data from URL parameters.
if(isset($_POST['add_location'])) {
    add_location();
}
if(isset($_POST['confirm_location'])) {
    confirm_location();
}



function add_location(){
    global $con;
    if (!$con) {
        die('Not connected : ' . mysqli_connect_error());
    }
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $description =$_POST['description'];
    // Inserts new row with place data.
    $query = sprintf("INSERT INTO locations " . " (id, lat, lng, description) " . " VALUES (NULL, '%s', '%s', '%s');",
        mysqli_real_escape_string($con,$lat),
        mysqli_real_escape_string($con,$lng),
        mysqli_real_escape_string($con,$description));

    $result = mysqli_query($con,$query);
    echo"Inserted Successfully";
    if (!$result) {
        die('Invalid query: ' . mysqli_error($con));
    }
}
function confirm_location(){
    global $con;
    if (!$con) {
        die('Not connected : ' . mysqli_connect_error());
    }
    $id = $_POST['id'];
    $confirmed =$_POST['confirmed'];
    // update location with confirm if admin confirm.
    $query = "UPDATE locations SET location_status = $confirmed WHERE id = $id ";
    $result = mysqli_query($con,$query);
    echo "Inserted Successfully";
    if (!$result) {
        die('Invalid query: ' . mysqli_error($con));
    }
}
function get_confirmed_locations(){
    global $con;
    if (!$con) {
        die('Not connected : ' . mysqli_connect_error());
    }
    // update location with location_status if admin location_status.
    $sqldata = mysqli_query($con,"SELECT id ,lat, lng, description, location_status AS isconfirmed FROM locations WHERE location_status = 1");

    $rows = array();

    while($r = mysqli_fetch_assoc($sqldata)) {
        $rows[] = $r;

    }

    $indexed = array_map('array_values', $rows);
    //  $array = array_filter($indexed);

    echo json_encode($indexed);
    if (!$rows) {
        return null;
    }
}
function get_all_locations(){
    global $con;
    if (!$con) {
        die('Not connected : ' . mysqli_connect_error());
    }
    // update location with location_status if admin location_status.
    $sqldata = mysqli_query($con,"SELECT id ,lat,lng,description,location_status AS isconfirmed FROM locations");
    $rows = array();
    while($r = mysqli_fetch_assoc($sqldata)) {
        $rows[] = $r;
    }
  $indexed = array_map('array_values', $rows);
  //  $array = array_filter($indexed);

    echo json_encode($indexed);
    if (!$rows) {
        return null;
    }
}
function array_flatten($array) {
    if (!is_array($array)) {
        return FALSE;
    }
    $result = array();
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $result = array_merge($result, array_flatten($value));
        }
        else {
            $result[$key] = $value;
        }
    }
    return $result;
}

?>