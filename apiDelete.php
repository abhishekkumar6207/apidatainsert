<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['sid']) || !is_numeric($data['sid'])) {
    echo json_encode(array('message' => 'Invalid Student ID', 'status' => false));
    exit;
}

$student_id = intval($data['sid']);

include("config.php");

$sql = "DELETE FROM students WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $student_id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(array('message' => 'Student Record Deleted.', 'status' => true));
} else {
    echo json_encode(array('message' => 'Student Record Not Deleted', 'status' => false));
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

