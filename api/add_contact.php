<?php
require_once '../dbconnect.php';
header('Content-Type: application/json');

$response = array('success' => false);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    
    $photo_path = 'uploads/default.png';
    if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo_name = basename($_FILES["photo"]["name"]);
        $photo_path = "uploads/" . uniqid() . "_" . $photo_name;
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], "../" . $photo_path)) {
            // Successfully uploaded
        } else {
            $photo_path = 'uploads/default.png';
        }
    }

    $sql = "INSERT INTO contacts (first_name, last_name, email, phone, photo) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $first_name, $last_name, $email, $phone, $photo_path);
    
    if ($stmt->execute()) {
        $response['success'] = true;
    }
    
    $stmt->close();
}

echo json_encode($response);
$conn->close();
?> 