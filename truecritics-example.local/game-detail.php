<?php
session_start();
include 'includes/db.php';

// Получаем ID игры из URL
$game_id = intval($_GET['id'] ?? 0);

if ($game_id === 0) {
    die("Игра не найдена");
}

// Всегда получаем данные игры
$stmt = $conn->prepare("SELECT * FROM games WHERE id = ?");
$stmt->bind_param("i", $game_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Игра не найдена в базе данных");
}

$game = $result->fetch_assoc();

// Проверяем, добавлена ли игра в избранное
$isFavorite = false;

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $fav_check = $conn->prepare("SELECT * FROM favorites WHERE user_id = ? AND game_id = ?");
    $fav_check->bind_param("ii", $user_id, $game_id);
    $fav_check->execute();
    $isFavorite = $fav_check->get_result()->num_rows > 0;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($game['title']) ?></title>
	<link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/game-detail.css">
</head>
<body class="page-game-detail">
    <header>
        <div class="container header-content">
            <a href="index.php" class="logo">
				<img src="images/logo.jpg" alt="True CRitics Logo">
			</a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Для авторизованных -->
                <div class="user-controls">
                    <div class="user-avatar" id="userAvatar">U</div>
                </div>
            <?php else: ?>
                <!-- Для гостей -->
                <div class="user-controls guest-controls">
                    <a href="login.php" class="btn btn-outline">Войти</a>
                    <a href="registration.php" class="btn btn-primary">Зарегистрироваться</a>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <main class="container">
        <div class="game-detail">
            <div class="game-header">
                <img src="<?= htmlspecialchars($game['image']) ?>" alt="<?= htmlspecialchars($game['title']) ?>" class="game-cover">
                <h2 class="game-title"><?= htmlspecialchars($game['title']) ?></h2>
            </div>

            <div class="game-meta">
                <div class="game-genres">
                    <?php foreach (explode(',', $game['genres']) as $genre): ?>
                        <span class="genre-tag"><?= trim(htmlspecialchars($genre)) ?></span>
                    <?php endforeach; ?>
                </div>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <button class="favorite-btn" id="favoriteBtn">
                        <span id="favoriteIcon"><?= $isFavorite ? '✓' : '❤' ?></span>
                        <span id="favoriteText"><?= $isFavorite ? 'В избранном' : 'Добавить в избранное' ?></span>
                    </button>
                <?php endif; ?>
            </div>

            <div class="game-description">
                <?= nl2br(htmlspecialchars($game['full_description'])) ?>
            </div>

            <!-- Кнопка назад -->
            <div style="text-align: center; margin-top: 2rem;">
                <a href="catalog.php" class="btn btn-back">Назад в каталог</a>
            </div>
        </div>
    </main>

    <!-- Подключаем JS только для залогиненных -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <script>
            window.gameData = {
                isFavorite: <?= json_encode($isFavorite) ?>,
                gameId: <?= json_encode($game['id']) ?>,
                userId: <?= json_encode($_SESSION['user_id']) ?>
            };
        </script>
        <script src="js/game-detail.js" defer></script>
    <?php endif; ?>
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