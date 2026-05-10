<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();

// CSRF токен
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Подключение к БД
$user = 'u82468';
$pass = '3747530';
$db = new PDO('mysql:host=localhost;dbname=u82468', $user, $pass,
    [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

// Функция проверки админа
function checkAdmin($db) {
    if (empty($_SESSION['uLogin'])) {
        header('Location: ./autorization.php');
        exit();
    }
    
    $stmt = $db->prepare("SELECT role FROM users WHERE userLogin = :login");
    $stmt->execute([':login' => $_SESSION['uLogin']]);
    $role = $stmt->fetchColumn();
    
    if ($role !== 'admin') {
        header('Location: redakt.php');
        exit();
    }
}

// Проверка для GET запроса
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    checkAdmin($db);
    
    if (!empty($_GET['save'])) {
        echo '<div style="background: #d4edda; padding: 15px; margin: 10px 0;">';
        echo '✅ Спасибо, результаты сохранены.';
        echo '</div>';
    }
    include('AdminForm.php');
    exit();
}

// Обработка POST запроса
$errors_array = [];

// CSRF проверка
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('Ошибка безопасности. Запрос отклонён.');
}

// Проверяем, что UID указан
if (empty($_POST['UID'])) {
    $errors_array['UID'] = "Укажите ID пользователя.";
}

if (!empty($_POST['fio']) && strlen($_POST['fio']) > 150) {
    $errors_array['fio'] = "Слишком много символов в поле ФИО.";
}
if (!empty($_POST['fio']) && !preg_match('/^[a-zA-Zа-яА-ЯёЁ\s-]+$/u', $_POST['fio'])) {
    $errors_array['fio'] = "ФИО должно содержать только буквы, пробелы и дефисы";
}
if (!empty($_POST['phone']) && !preg_match('/^[\+\(\)\d\s-]+$/', $_POST['phone'])) {
    $errors_array['phone'] = "Номер телефона может содержать цифры, +, пробелы, скобки и дефисы.";
}
if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $errors_array['email'] = "Введите корректный email адрес.";
}

if (!empty($errors_array)) {
    foreach ($errors_array as $value) {
        print(htmlspecialchars($value) . "<br>");
    }
} else {
    try {
        // Проверяем админа
        checkAdmin($db);
        
        $db->beginTransaction();
        
        // ID пользователя из формы
        $targetUserId = $_POST['UID'];
        
        // Проверка существования пользователя
        $stmt = $db->prepare("SELECT id FROM users WHERE id = :id");
        $stmt->execute([':id' => $targetUserId]);
        $userExists = $stmt->fetchColumn();
        
        if (!$userExists) {
            $db->rollBack();
            print(htmlspecialchars("Ошибка: Пользователь с ID = {$targetUserId} не существует."));
            exit();
        }
        
        // Обновляем только те поля, которые пришли
        if (!empty($_POST['fio'])) {
            $stmt = $db->prepare("UPDATE users SET fio = :fio WHERE id = :id");
            $stmt->execute([
                ':fio' => $_POST['fio'],
                ':id' => $targetUserId
            ]);
        }
        
        if (!empty($_POST['phone'])) {
            $stmt = $db->prepare("UPDATE users SET phone = :phone WHERE id = :id");
            $stmt->execute([
                ':phone' => $_POST['phone'],
                ':id' => $targetUserId
            ]);
        }
        
        if (!empty($_POST['email'])) {
            $stmt = $db->prepare("UPDATE users SET email = :email WHERE id = :id");
            $stmt->execute([
                ':email' => $_POST['email'],
                ':id' => $targetUserId
            ]);
        }
        
        if (!empty($_POST['brithDate'])) {
            $stmt = $db->prepare("UPDATE users SET brithDate = :brithDate WHERE id = :id");
            $stmt->execute([
                ':brithDate' => $_POST['brithDate'],
                ':id' => $targetUserId
            ]);
        }
        
        if (!empty($_POST['gender'])) {
            $stmt = $db->prepare("UPDATE users SET gender = :gender WHERE id = :id");
            $stmt->execute([
                ':gender' => $_POST['gender'],
                ':id' => $targetUserId
            ]);
        }
        
        // Обновление языков
        if (!empty($_POST['lang_id'])) {
            // Удаляем старые языки
            $stmt = $db->prepare("DELETE FROM user_languages WHERE user_id = :user_id");
            $stmt->execute([':user_id' => $targetUserId]);
            
            // Добавляем новые
            $stmt = $db->prepare("INSERT INTO user_languages (user_id, lang_id) VALUES (:user_id, :lang_id)");
            foreach ($_POST['lang_id'] as $lang_id) {
                $stmt->execute([
                    ':user_id' => $targetUserId,
                    ':lang_id' => $lang_id
                ]);
            }
        }
        
        if (!empty($_POST['bio'])) {
            $stmt = $db->prepare("UPDATE users SET bio = :bio WHERE id = :id");
            $stmt->execute([
                ':bio' => $_POST['bio'],
                ':id' => $targetUserId
            ]);
        }
        
        // Обновление роли (только для админа)
        if (!empty($_POST['role'])) {
            $stmt = $db->prepare("UPDATE users SET role = :role WHERE id = :id");
            $stmt->execute([
                ':role' => $_POST['role'],
                ':id' => $targetUserId
            ]);
        }
        
        $db->commit();
        
    } catch (PDOException $e) {
        $db->rollBack();
        error_log($e->getMessage());
        print('Произошла ошибка. Попробуйте позже.');
        exit();
    }
    
    header('Location: ?save=1');
    exit();
}
?>