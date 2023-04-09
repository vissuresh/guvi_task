<?php

// Composer autoloader
require '../vendor/autoload.php'; 


//// MYSQL SETUP ////////
$conn = new mysqli('localhost', 'root', '');

// Testing the connection
if ($conn->connect_error) {
    echo "Database connection error: ".$conn->connect_error;
    die("Database connection error: ".$conn->connect_error);
}

// Creating database using prepared statement
$db_name = "guvi";
$query = "CREATE DATABASE IF NOT EXISTS `$db_name`";
$stmt = $conn->prepare($query);
$stmt->execute();

// Closing connecction
$conn->close();
//////// END MYSQL SETUP ///////


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



// Connection to the database 'guvi'
$conn = new mysqli('localhost', 'root', '', 'guvi');


// Checking if user table exists
$table_name = "users";
$query = "SHOW TABLES LIKE '$table_name'";

$stmt = $conn->prepare($query);
$stmt->execute();
$stmt->store_result();

if($stmt->num_rows == 0 ){
    $query = "CREATE TABLE users (
        id INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(50) NOT NULL,
        password VARCHAR(300) NOT NULL
    )";
    $stmt = $conn->prepare($query);
    $stmt->execute();
}

// Getting the input json username and password
$json = file_get_contents('php://input');
$data = json_decode($json, true);
$email = $data['email'];
$password = $data['password'];



// Check for same username
$query = "SELECT email FROM users WHERE email=?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $email);
$stmt->execute();
$exists = $stmt->get_result()->num_rows;

if($exists > 0){
    echo "email already exists";
}
else{

    // Create Hash and insert in MySQL
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $query = "INSERT INTO users(email,password) values(?,?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $hash);
    $stmt->execute();


    // Insert data in MongoDB
    $mongo_newprofileData = array(
        "email" => $email,
        "first_name" => $data['first_name'],
        "last_name" => $data['last_name'],
        "dob" => $data['dob'],
    );
    
    if ($mongo_collection->insertOne($mongo_newprofileData)) {
        echo "REGISTRATION_SUCCESS";
    } 
    else {
    echo "Could not create Profile in MongoDB";
    }
}

$conn->close();

?>