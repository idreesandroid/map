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