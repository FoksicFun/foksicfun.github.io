<?php
include 'includes/db.php';

// Определяем метод запроса
$method = $_SERVER['REQUEST_METHOD'];

// GET: получаем список всех отзывов
if ($method === 'GET') {
    $result = $conn->query("SELECT * FROM feedback ORDER BY created_at DESC");
    $feedbackList = [];

    while ($row = $result->fetch_assoc()) {
        $feedbackList[] = $row;
    }

    echo json_encode(['status' => 'success', 'data' => $feedbackList]);
    exit();
}

// POST: сохраняем новый отзыв
if ($method === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode(['status' => 'error', 'message' => 'Все поля обязательны']);
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Неверный формат email']);
        exit();
    }

    // Сохраняем в базу данных
    $stmt = $conn->prepare("INSERT INTO feedback (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        // Возвращаем обновлённый список после добавления
        $result = $conn->query("SELECT * FROM feedback ORDER BY created_at DESC");
        $feedbackList = [];
        while ($row = $result->fetch_assoc()) {
            $feedbackList[] = $row;
        }

        echo json_encode(['status' => 'success', 'data' => $feedbackList]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Ошибка при сохранении отзыва']);
    }

    exit();
}
?>