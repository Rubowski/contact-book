<?php
require_once '../dbconnect.php';
header('Content-Type: application/json');

$sql = "SELECT * FROM contacts ORDER BY created_at DESC";
$result = $conn->query($sql);

$contacts = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $contacts[] = $row;
    }
}

echo json_encode($contacts);
$conn->close();
?> 