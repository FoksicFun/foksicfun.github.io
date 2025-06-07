<?php
session_start();
include 'includes/db.php';

// Проверяем, залогинен ли пользователь
$isLoggedIn = isset($_SESSION['user_id']);

if ($isLoggedIn) {
    $userId = $_SESSION['user_id'];
    // Проверяем, есть ли такой пользователь в БД
    $stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        session_unset();
        session_destroy();
        $isLoggedIn = false;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>True CRitics - Главная</title>
	<link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/home.css">
</head>
<body class="page-home">
    <header>
        <div class="container header-content">
            <h1>True CRitics</h1>
        </div>
    </header>

    <main class="container">
        <img src="images/main.jpg" alt="True CRitics" class="game-image">
        <p class="welcome-text">
            <strong>Добро пожаловать на True CRitics.</strong><br>
            У нас на сайте вы можете узнать о популярных видеоиграх.
        </p>

        <!-- Только для авторизованных -->
        <?php if ($isLoggedIn): ?>
            <div class="user-controls user-controls--logged-in">
                <a href="catalog.php" class="btn btn-primary">Перейти в каталог игр</a>
            </div>
        <?php else: ?>
            <!-- Для гостей -->
            <div class="user-controls guest-controls">
                <a href="login.php" class="btn btn-outline">Войти</a>
                <a href="registration.php" class="btn btn-primary">Зарегистрироваться</a>
                <a href="catalog.php" class="btn btn-primary">Перейти в каталог</a>
            </div>
        <?php endif; ?>
    </main>

	<footer class="site-footer">
		<div class="container footer-content">
			<p class="footer-contact">📧 zakharusupov@gmail.com</p>
			<div class="footer-links">
				<a href="privacy-policy.html">Политика конфиденциальности</a>
				<a href="terms-of-use.html">Условия использования</a>
				<a href="feedback.php">Обратная связь</a>
			</div>
		</div>
	</footer>
</body>
</html>