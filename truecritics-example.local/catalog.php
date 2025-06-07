<?php
session_start();
include 'includes/db.php';

// Проверяем, залогинен ли пользователь
$isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
if ($isLoggedIn) {
    $userId = $_SESSION['user_id'];
    // Проверяем, существует ли пользователь в БД
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

// Получаем текущую страницу из URL
$page = intval($_GET['page'] ?? 1);
$gamesPerPage = 6;
$start = ($page - 1) * $gamesPerPage;

// Поиск по играм
$searchQuery = '';
if (isset($_GET['search'])) {
    $searchQuery = trim($_GET['search']);
}

// SQL-запрос с фильтрацией по названию или описанию
$sql = "SELECT id, title, image, description FROM games";
if (!empty($searchQuery)) {
    $searchQueryForSQL = $conn->real_escape_string($searchQuery);
    $sql .= " WHERE title LIKE '%$searchQueryForSQL%' OR description LIKE '%$searchQueryForSQL%'";
}
$sql .= " ORDER BY id LIMIT $start, $gamesPerPage";

$result = $conn->query($sql);

// Получаем общее количество найденных игр
$totalSql = "SELECT COUNT(*) AS total FROM games";
if (!empty($searchQuery)) {
    $totalSql .= " WHERE title LIKE '%$searchQueryForSQL%' OR description LIKE '%$searchQueryForSQL%'";
}
$totalResult = $conn->query($totalSql);
$totalRow = $totalResult->fetch_assoc();
$totalGames = $totalRow['total'];
$totalPages = ceil($totalGames / $gamesPerPage);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Каталог — True CRitics</title>
	<link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/catalog.css">
</head>
<body class="page-catalog">
    <header>
        <div class="container header-content">
			<a href="index.php" class="logo">
				<img src="images/logo.jpg" alt="True CRitics Logo">
			</a>

            <div class="user-controls">
                <?php if ($isLoggedIn): ?>
                    <!-- Аватар пользователя -->
                    <div class="user-avatar" id="userAvatar">U</div>
                <?php else: ?>
                    <!-- Для гостей -->
                    <div class="auth-buttons" id="authButtons">
                        <a href="login.php" class="btn btn-outline">Войти</a>
                        <a href="registration.php" class="btn btn-primary">Зарегистрироваться</a>
                    </div>
                <?php endif; ?>

                <!-- Поиск по названию игры -->
                <div class="search-container">
                    <input type="text" class="search-input" id="searchInput" placeholder="Поиск по играм...">
                </div>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="catalog-intro">
            <h1>Каталог</h1>
            <p>Здесь Вы можете найти популярные видеоигры</p>
        </div>

        <div class="game-list" id="gameList">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="game-card" onclick="window.location.href='game-detail.php?id=<?= $row['id'] ?>'">
                    <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>" class="game-image">
                    <div class="game-info">
                        <h3><?= htmlspecialchars($row['title']) ?></h3>
                        <p><?= htmlspecialchars(substr($row['description'], 0, 100)) ?>...</p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Пагинация -->
        <div class="pagination" id="pagination">
            <?php if ($searchQuery): ?>
                <!-- Если был поиск — пагинация с учётом поиска -->
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <button class="page-btn<?= $i == $page ? ' active' : '' ?>" data-page="<?= $i ?>">
                        <?= $i ?>
                    </button>
                <?php endfor; ?>
            <?php else: ?>
                <!-- Если обычный каталог — обычная пагинация -->
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>" class="page-btn">Назад</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?>" class="page-btn<?= $i == $page ? ' active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?>" class="page-btn">Вперёд</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </main>

    <!-- Подключаем JS -->
    <script src="js/catalog.js" defer></script>
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