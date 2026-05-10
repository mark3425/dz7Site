<?php
session_start();

// CSRF токен
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
?>
<html>
    <head>
    <link href="style.css" rel="stylesheet">
    </head>
    <body>

    <?php if (isset($_GET['error'])): ?>
        <p style="color: red;">Неверный логин или пароль</p>
    <?php endif; ?>

    <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
            <label>Логин:</label>
            <input type="text" name="login">
            <label>Пароль:</label>
            <input type="password" name="password">
            <button type="submit">Войти</button>
    </form>
    <a href="index.php">Нет аккаунта?</a>
    </body>
</html>