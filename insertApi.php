<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['firstName']) || empty(trim($data['firstName'])) ||
    !isset($data['lastName']) || empty(trim($data['lastName'])) ||
    !isset($data['phone']) || !preg_match("/^[0-9]{10}$/", $data['phone']) ||
    !isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    
    echo json_encode(array('message' => 'Invalid input data', 'status' => false));
    exit;
}

$firstName = trim($data['firstName']);
$lastName = trim($data['lastName']);
$phone = trim($data['phone']);
$email = trim($data['email']);

include('config.php');

$sql = "INSERT INTO students (firstName, lastName, phone, email) VALUES ('$firstName', '$lastName', '$phone', '$email')";

if (mysqli_query($conn, $sql)) {
    echo json_encode(array('message' => 'Student Record Inserted.', 'status' => true));
} else {
    echo json_encode(array('message' => 'Student Record Not Inserted', 'status' => false));
}

?>
