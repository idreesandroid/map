<?php

require_once('Medoo.php');

use Medoo\Medoo;

$database = new Medoo([
    'database_type' => 'mysql',
    'database_name' => 'google_map_db',
    'server' => 'localhost',
    'username' => 'root',
    'password' => ''
]);

if(isset($_REQUEST['CompleteMapData'])){

    $desc = $_REQUEST['CompleteMapData'];       
    $account_id = $database->id();
    
    $sql = 'INSERT INTO google_map(description) VALUES ('.$desc.') ';

    if ($database->insert("google_map",['description' => stripslashes($desc)])) {
      echo "New record created successfully";
    } else {
      echo "Error: " . $sql . "<br>" ;
    }
}


if(isset($_REQUEST['id']) && $_REQUEST['type'] == 'getMap'){

    $sql = 'SELECT description FROM google_map WHERE id = '. $_REQUEST['id'];  

    $result = $database->select("google_map",'description',['id[=]' => $_REQUEST['id']]);
    
    echo $result[0]; 
}
