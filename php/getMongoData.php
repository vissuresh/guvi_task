<?php

session_start(); 
require '../vendor/autoload.php'; 


////////MONGODB SETUP /////////////

// MongoDB Headers
$mongo_db_url = "mongodb://localhost:27017";
$mongo_db_name = "guvi";
$mongo_collection_name = "userProfile";

$mongo_client = new MongoDB\Client($mongo_db_url);

// Checking the status of connection request
if (!$mongo_client) {
echo "Connection to MongoDB Failed!";
}

// Selecting Guvi Database in MongoDB
$mongo_current_db = $mongo_client->selectDatabase($mongo_db_name);
if (!$mongo_current_db) {
echo "Unable to select database ".$mongo_db_name."\n";
}

// Selection userProfile collection in MongoDB
$mongo_collection = $mongo_current_db->selectCollection($mongo_collection_name);

/////// END MONGODB SETUP /////////

// Getting the input json username and password
$json = file_get_contents('php://input');
$data = json_decode($json, true);


$document = $mongo_collection->findOne(['email' => $data['email']]);

if ($document) {
    $result = json_encode($document);
    echo $result;
}
else{
    echo "ERROR";
}

?>