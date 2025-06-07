<?php
session_start();
include 'includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Проверка email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Введите корректный email";
    } else {
        // Ищем пользователя в базе
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Проверяем пароль
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_email'] = $email;
                $_SESSION['user_id'] = $row['id'];
                header("Location: catalog.php");
                exit();
            } else {
                $error = "Неверный пароль";
            }
        } else {
            $error = "Пользователь не найден";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>True CRitics - Вход</title>
	<link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/login.css">
</head>
<body class="page-login">
    <div class="login-box">
        <h1>Вход</h1>

        <?php if (!empty($error)): ?>
            <p style="color: red;"><?= $error ?></p>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required value="<?= htmlspecialchars($email ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Пароль:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn">Войти</button>
        </form>

        <p class="login-link">
            Нет аккаунта? <a href="registration.php">Зарегистрируйтесь</a>
        </p>
    </div>
</body>
</html>