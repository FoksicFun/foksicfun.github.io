<?php
session_start();
include 'includes/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm-password']);
    $agree = isset($_POST['agree']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Введите корректный email";
    } elseif ($password !== $confirmPassword) {
        $error = "Пароли не совпадают";
    } elseif (!$agree) {
        $error = "Вы должны согласиться на обработку данных";
    } else {
        // Проверяем, существует ли пользователь
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $error = "Этот email уже зарегистрирован";
        } else {
            // Хэшируем пароль
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Вставляем нового пользователя
            $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $hashed_password);

            if ($stmt->execute()) {
                // Сохраняем ID пользователя в сессии
                $_SESSION['user_email'] = $email;
                $_SESSION['user_id'] = $stmt->insert_id;

                // Перенаправление на catalog.php
                header("Location: catalog.php");
                exit();
            } else {
                $error = "Ошибка регистрации";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>True CRitics - Регистрация</title>
	<link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/registration.css">
</head>
<body class="registration-page">
    <div class="registration-container">
        <h1>Регистрация</h1>

        <?php if (!empty($error)): ?>
            <p style="color: red;"><?= $error ?></p>
        <?php endif; ?>

        <form id="registrationForm" method="post">
            <div class="form-group">
                <label for="email">Электронная почта</label>
                <input type="email" id="email" name="email" required value="<?= htmlspecialchars($email ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="confirm-password">Подтвердите пароль</label>
                <input type="password" id="confirm-password" name="confirm-password" required>
            </div>

            <div class="checkbox-group">
                <div class="checkbox-label">
                    <input type="checkbox" id="agree" name="agree" required>
                    <label for="agree">Я согласен на обработку персональных данных</label>
                </div>
            </div>

            <button type="submit" class="submit-btn" id="submitBtn" disabled>Зарегистрироваться</button>

            <div class="login-link">
                Уже есть аккаунт? <a href="login.php">Войти</a>
            </div>
        </form>
    </div>

    <script src="js/registration.js" defer></script>
</body>
</html>