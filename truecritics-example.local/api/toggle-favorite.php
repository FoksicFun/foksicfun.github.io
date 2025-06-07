<?php
session_start();
include '../includes/db.php'; // Исправленный путь

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

// Проверяем, есть ли уже в избранном
$stmt = $conn->prepare("SELECT * FROM favorites WHERE user_id = ? AND game_id = ?");
$stmt->bind_param("ii", $user_id, $game_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Удаляем из избранного
    $stmt = $conn->prepare("DELETE FROM favorites WHERE user_id = ? AND game_id = ?");
    $stmt->bind_param("ii", $user_id, $game_id);
    $stmt->execute();
    echo json_encode([
        'status' => 'removed',
        'is_favorite' => false
    ]);
} else {
    // Добавляем в избранное
    $stmt = $conn->prepare("INSERT INTO favorites (user_id, game_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $game_id);
    $stmt->execute();
    echo json_encode([
        'status' => 'added',
        'is_favorite' => true
    ]);
}
?>