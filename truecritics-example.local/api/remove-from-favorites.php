<?php
session_start();
include '../../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Неавторизован']);
    exit();
}

$game_id = intval($_POST['game_id'] ?? 0);
$user_id = intval($_SESSION['user_id']);

if (!$game_id || !$user_id) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Неверные параметры']);
    exit();
}

$stmt = $conn->prepare("DELETE FROM favorites WHERE game_id = ? AND user_id = ?");
$stmt->bind_param("ii", $game_id, $user_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Ошибка базы данных']);
}
?>