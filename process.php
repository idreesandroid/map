<?php

require_once('Medoo.php');

use Medoo\Medoo;

$database = new Medoo([
    'database_type' => 'mysql',
    'database_name' => 'milkman',
    'server' => 'localhost',
    'username' => 'root',
    'password' => ''
]);

if(isset($_REQUEST['CompleteMapData'])){

    $desc = $_REQUEST['CompleteMapData'];

        $database->insert("users", [
        "user_name" => "foo",
        "email" => "foo@bar.com",
        "age" => 25
    ]);
     
    $account_id = $database->id();
    
    $sql = 'INSERT INTO google_map(description) VALUES ('.$desc.') ';

    if ($conn->query($sql) === TRUE) {
      echo "New record created successfully";
    } else {
      echo "Error: " . $sql . "<br>" ;
    }
}


if(isset($_REQUEST['id']) && $_REQUEST['type'] == 'getMap'){

    $sql = 'SELECT description FROM google_map WHERE id = '. $_REQUEST['id'];  

    $stmt = $conn->prepare($sql);
    $stmt->execute();  
    $stmt->setFetchMode(PDO::FETCH_ASSOC); 

    $result = $stmt->fetchAll();
    echo $result[0]['description'];     

}
$conn = null;