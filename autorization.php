<?php 
header('Content-Type: text/html; charset=UTF-8');
session_start();

// CSRF токен
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!empty($_SESSION['uLogin'])) {
        header('Location: ./redakt.php');
        exit();
    } 
    include('autorizationForm.php');
    exit();
}

// Обработка POST запроса
$login = $_POST['login'] ?? '';
$password = $_POST['password'] ?? '';

// CSRF проверка
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('Ошибка безопасности. Запрос отклонён.');
}

$user = 'u82468';
$pass = '3747530';
$db = new PDO('mysql:host=localhost;dbname=u82468', $user, $pass,
    [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

try {
    $stmt = $db->prepare("SELECT id, userPass, role FROM users WHERE userLogin = :login");
    $stmt->execute([':login' => $login]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($userData && password_verify($password, $userData['userPass'])) {
        $_SESSION['uLogin'] = $login;
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['role'] = $userData['role'];
        
        header('Location: ./redakt.php');
        exit();
    }
    
    // Неверный логин или пароль
    header('Location: ./autorizationForm.php?error=1');
    exit();
    
} catch(PDOException $e) {
    error_log($e->getMessage());
    print('Произошла ошибка при входе. Попробуйте позже.');
    exit();
}
?>