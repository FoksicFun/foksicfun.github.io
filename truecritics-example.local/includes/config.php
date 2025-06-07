<?php
session_start();
include 'includes/db.php';

// Проверка авторизации
$isLoggedIn = isset($_SESSION['user_id']);
?>