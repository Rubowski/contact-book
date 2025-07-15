<?php
include '../dbconnect.php';
header('Content-Type: application/json');

$search_query = "SELECT * FROM contacts";
$conditions = [];

if (!empty($_GET['last_name'])) {
    $conditions[] = "last_name LIKE '%" . $conn->real_escape_string($_GET['last_name']) . "%'";
}

if (!empty($_GET['phone'])) {
    $conditions[] = "phone LIKE '%" . $conn->real_escape_string($_GET['phone']) . "%'";
}

if (count($conditions) > 0) {
    $search_query .= " WHERE " . implode(" AND ", $conditions);
}

$search_query .= " ORDER BY created_at DESC";
$result = $conn->query($search_query);

$contacts = [];
while ($row = $result->fetch_assoc()) {
    $contacts[] = $row;
}

echo json_encode($contacts);
$conn->close();
?> 