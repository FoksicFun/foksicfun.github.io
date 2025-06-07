<?php
include '../includes/db.php';

$search = $_GET['search'] ?? '';
$sql = "SELECT COUNT(*) AS total FROM games";

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " WHERE title LIKE '%$search%' OR description LIKE '%$search%'";
}

$result = $conn->query($sql);
$row = $result->fetch_assoc();

echo json_encode(['total' => $row['total']]);
?>