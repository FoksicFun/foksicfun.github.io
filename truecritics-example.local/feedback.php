<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Форма обратной связи — True CRitics</title>
	<link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/feedback.css">
</head>
<body class="page-feedback">
    <header>
        <div class="container header-content">
			<a href="index.php" class="logo">
				<img src="images/logo.jpg" alt="True CRitics Logo">
			</a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="user-controls">
                    <div class="user-avatar" id="userAvatar">U</div>
                </div>
            <?php else: ?>
                <div class="user-controls guest-controls">
                    <a href="login.php" class="btn btn-outline">Войти</a>
                    <a href="registration.php" class="btn btn-primary">Зарегистрироваться</a>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <main class="container">
        <h1>Обратная связь</h1>
        <p>Если у вас есть предложения, пожелания или вы нашли ошибку — напишите нам!</p>

        <!-- Форма обратной связи -->
        <form id="feedbackForm">
            <div class="form-group">
                <label for="name">Имя:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="message">Сообщение:</label>
                <textarea id="message" name="message" rows="5" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Отправить</button>
        </form>

        <!-- Модальное окно -->
        <div id="successModal" class="modal-overlay" style="display: none;">
            <div class="modal">
                <span class="close-btn">&times;</span>
                <h3>Спасибо за ваше сообщение!</h3>
                <p>Мы обязательно ответим вам.</p>
                <button id="closeModalBtn" class="btn btn-secondary">Закрыть</button>
            </div>
        </div>

        <!-- Список отзывов -->
        <section id="feedbackList" class="feedback-list">
            <h2>Последние отзывы</h2>
            <p>Здесь будут отображаться все отзывы.</p>
        </section>
    </main>

    <!-- Подключаем jQuery и JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <script src="js/feedback.js" defer></script>
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