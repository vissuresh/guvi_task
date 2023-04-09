<?php

require '../vendor/autoload.php';

$conn = new mysqli("localhost", "root", "", "guvi");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Getting the input json username and password
$json = file_get_contents('php://input');
$data = json_decode($json, true);
$email = $data['email'];
$password = $data['password'];

$query = "SELECT * FROM users where email = ? ";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    echo "USER_NOT_FOUND";
}

else{
    $row = $result->fetch_assoc();
    if(password_verify($password, $row['password']) == false){
        echo "WRONG_PASSWORD\n".$row['password'];
    }
    else{
        $userInfo = json_encode($row);
        echo $userInfo;
    }
}