<?php
// Opens a connection to a MySQL server.
$con=mysqli_connect ("localhost", 'root', '','google_map_db');
if (!$con) {
    die('Not connected : ' . mysqli_connect_error());
}

$abc = 'abc';
// Sets the active MySQL database.
/*$db_selected = mysqli_select_db($connection,'accounts');
if (!$db_selected) {
    die ('Can\'t use db : ' . mysqli_error($connection));
}*/