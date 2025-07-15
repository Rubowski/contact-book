<?php
include '../dbconnect.php';
header('Content-Type: application/json');

$response = ['success' => false];

if (isset($_POST['id'])) {
    $id = $conn->real_escape_string($_POST['id']);
    
    // First get the photo path
    $result = $conn->query("SELECT photo FROM contacts WHERE id = $id");
    $row = $result->fetch_assoc();
    
    // Delete photo if it exists and is not the default
    if ($row && $row['photo'] !== "uploads/default.png" && file_exists("../" . $row['photo'])) {
        unlink("../" . $row['photo']);
    }
    
    // Delete the contact
    $sql = "DELETE FROM contacts WHERE id = '$id'";
    if ($conn->query($sql)) {
        $response['success'] = true;
    }
}

echo json_encode($response);
$conn->close();
?> 