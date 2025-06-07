<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Получаем список избранных игр
$sql = "
    SELECT g.* 
    FROM games g
    JOIN favorites f ON g.id = f.game_id
    WHERE f.user_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$favorites = [];
while ($row = $result->fetch_assoc()) {
    $favorites[] = $row;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Избранное - True CRitics</title>
	<link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/favorites.css">
</head>
<body class="page-favorites">
    <header>
        <div class="container header-content">
			<a href="index.php" class="logo">
				<img src="images/logo.jpg" alt="True CRitics Logo">
			</a>

            <!-- Пользовательские кнопки -->
            <div class="account-actions">
                <button class="btn btn-logout">Выйти</button>
                <button class="btn btn-delete-account">Удалить аккаунт</button>
				<a href="feedback.php" class="btn btn-secondary">Обратная связь</a>

                <!-- Аватар и счётчик избранного -->
                <div class="user-avatar" id="userAvatar">U</div>
                <div id="favoritesInfo">
                    В избранном игр: <span class="favorites-count"><?= count($favorites) ?></span>
                </div>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="favorites-intro">
            <h1>Избранное</h1>
            <p>Здесь собраны все игры, которые вы добавили в избранное</p>
        </div>

        <div class="game-list" id="favoritesList">
            <?php if (!empty($favorites)): ?>
                <?php foreach ($favorites as $game): ?>
                    <div class="game-card" data-game-id="<?= $game['id'] ?>">
                        <a href="game-detail.php?id=<?= $game['id'] ?>" class="game-link">
                            <img src="<?= htmlspecialchars($game['image']) ?>" alt="<?= htmlspecialchars($game['title']) ?>" class="game-image">
                            <div class="game-info">
                                <h3><?= htmlspecialchars($game['title']) ?></h3>
                                <p><?= htmlspecialchars(substr($game['description'], 0, 100)) ?>...</p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="grid-column: 1/-1; text-align: center;">Ваше избранное пусто</p>
            <?php endif; ?>
        </div>

        <!-- Кнопка назад в каталог -->
        <div style="text-align: center; margin-top: 2rem;">
            <a href="catalog.php" class="btn btn-back">Назад в каталог</a>
        </div>
    </main>

    <!-- Подключаем JS для кликов по аватару и кнопкам -->
    <script>
        // Обработчики событий
        const userAvatar = document.getElementById('userAvatar');
        const logoutBtn = document.querySelector('.btn-logout');
        const deleteAccountBtn = document.querySelector('.btn-delete-account');

        // Переход по аватару → в избранное (сама страница)
        if (userAvatar) {
            userAvatar.addEventListener('click', function () {
                window.location.href = 'favorites.php';
            });
        }

        // Выход через logout.php
        if (logoutBtn) {
            logoutBtn.addEventListener('click', function () {
                window.location.href = 'logout.php';
            });
        }

        // Удаление аккаунта через delete-account.php
        if (deleteAccountBtn) {
            deleteAccountBtn.addEventListener('click', function () {
                if (confirm("Вы уверены, что хотите удалить свой аккаунт? Это действие нельзя отменить.")) {
                    window.location.href = 'delete-account.php?confirm=1';
                }
            });
        }
    </script>
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