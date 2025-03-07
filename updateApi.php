    <?php

    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include('config.php');

    
    if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
        echo json_encode(array('message' => 'Only PUT requests are allowed', 'status' => false));
        exit();
    }

    
    $data = json_decode(file_get_contents("php://input"), true);

    
    if (!isset($data['id'], $data['firstName'], $data['lastName'], $data['phone'], $data['email'])) {
        echo json_encode(array('message' => 'Missing required fields', 'status' => false));
        exit();
    }

    
    $id = mysqli_real_escape_string($conn, $data['id']);
    $firstName = mysqli_real_escape_string($conn, $data['firstName']);
    $lastName = mysqli_real_escape_string($conn, $data['lastName']);
    $phone = mysqli_real_escape_string($conn, $data['phone']);
    $email = mysqli_real_escape_string($conn, $data['email']);

    
    $sql = "UPDATE students SET firstName = '$firstName', lastName = '$lastName', phone = '$phone', email = '$email' WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(array('message' => 'Student Record Updated.', 'status' => true));
    } else {
        echo json_encode(array('message' => 'Student Record Not Updated.', 'status' => false, 'error' => mysqli_error($conn)));
    }

    
    mysqli_close($conn);

    ?>
