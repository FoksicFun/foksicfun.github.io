<?php
session_start();
include 'includes/db.php';

if (!isset($_GET['confirm']) || !isset($_SESSION['user_id'])) {
    header("Location: favorites.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$confirm = intval($_GET['confirm']);

if ($confirm !== 1) {
    header("Location: favorites.php");
    exit();
}

// Удаляем все данные пользователя
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    session_unset();
    session_destroy();
    header("Location: index.php?message=account_deleted");
    exit();
} else {
    echo "<p>Ошибка при удалении аккаунта</p>";
}
?>