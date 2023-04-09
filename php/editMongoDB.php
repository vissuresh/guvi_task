
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

$editData = array(
    '$set' => array(
        "first_name" => $data['first_name'],
        "last_name" => $data['last_name'],
        "dob" => $data['dob'],
        "phone" => $data['phone'],
        "address" => $data['address'],
    )
);

$condition = array("email" => $data['email']);

if ($mongo_collection->updateOne($condition, $editData)) {
    echo "UPDATE_SUCCESS";
} else {
    echo "ERROR";
}
