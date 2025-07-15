<?php
require_once '../dbconnect.php';
header('Content-Type: application/json');

$response = array('success' => false);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    
    // Get current photo
    $result = $conn->query("SELECT photo FROM contacts WHERE id = " . $conn->real_escape_string($id));
    $current = $result->fetch_assoc();
    $photo_path = $current['photo'];
    
    // Handle new photo upload
    if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        // Delete old photo if it exists and is not default
        if ($photo_path !== "uploads/default.png" && file_exists("../" . $photo_path)) {
            unlink("../" . $photo_path);
        }
        
        $photo_name = basename($_FILES["photo"]["name"]);
        $photo_path = "uploads/" . uniqid() . "_" . $photo_name;
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], "../" . $photo_path)) {
            // Successfully uploaded
        } else {
            $photo_path = $current['photo']; // Keep old photo if upload fails
        }
    }

    $sql = "UPDATE contacts SET first_name = ?, last_name = ?, email = ?, phone = ?, photo = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $first_name, $last_name, $email, $phone, $photo_path, $id);
    
    if ($stmt->execute()) {
        $response['success'] = true;
    }
    
    $stmt->close();
}

echo json_encode($response);
$conn->close();
?> 