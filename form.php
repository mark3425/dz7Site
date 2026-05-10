<html>
<head>
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <?php 
    // Получаем ошибки из cookies
    $errors = [];
    if (isset($_COOKIE['errors_array'])) {
        $errors = json_decode($_COOKIE['errors_array'], true);
    }
    ?>
    
    <?php if (!empty($errors)): ?>
        <div class="error-messages">
            <?php foreach ($errors as $field => $message): ?>
                <p style="color: red; margin: 5px 0;">❌ <?php echo $message; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
<h1>Создание учётной записи</h1>
    <form method="POST">

     <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

        <label>ФИО: 
            <input name="fio" value="<?php echo isset($_COOKIE['saved_fio']) ? htmlspecialchars($_COOKIE['saved_fio']) : ''; ?>"
                   style="<?php echo isset($errors['fio']) ? 'border:2px solid red;' : ''; ?>">
        </label>
        
        <label>Телефон: 
            <input name="phone" value="<?php echo isset($_COOKIE['saved_phone']) ? htmlspecialchars($_COOKIE['saved_phone']) : ''; ?>"
                   style="<?php echo isset($errors['phone']) ? 'border:2px solid red;' : ''; ?>">
        </label>
        
        <label>Почта: 
            <input name="email" value="<?php echo isset($_COOKIE['saved_email']) ? htmlspecialchars($_COOKIE['saved_email']) : ''; ?>"
                   style="<?php echo isset($errors['email']) ? 'border:2px solid red;' : ''; ?>">
        </label>
        
        <label>Дата рождения: 
            <input name="brithDate" type="date" value="<?php echo isset($_COOKIE['saved_brithDate']) ? htmlspecialchars($_COOKIE['saved_brithDate']) : ''; ?>"
                   style="<?php echo isset($errors['brithDate']) ? 'border:2px solid red;' : ''; ?>">
        </label>
        
        <label>Пол:
            <input type="radio" name="gender" value="male" <?php echo (isset($_COOKIE['saved_gender']) && $_COOKIE['saved_gender'] == 'male') ? 'checked' : ''; ?>>Мужской
            <input type="radio" name="gender" value="female" <?php echo (isset($_COOKIE['saved_gender']) && $_COOKIE['saved_gender'] == 'female') ? 'checked' : ''; ?>>Женский
        </label>
        
        <label>Любимый язык программирования: 
            <select name="lang_id[]" multiple size="5" style="<?php echo isset($errors['lang_id']) ? 'border:2px solid red;' : ''; ?>">
                <?php
                $saved_lang = [];
                if (isset($_COOKIE['saved_lang_id'])) {
                    $saved_lang = json_decode($_COOKIE['saved_lang_id'], true);
                }
                ?>
                <option value="1" <?php echo in_array('1', $saved_lang) ? 'selected' : ''; ?>>Pascal</option>
                <option value="2" <?php echo in_array('2', $saved_lang) ? 'selected' : ''; ?>>C</option>
                <option value="3" <?php echo in_array('3', $saved_lang) ? 'selected' : ''; ?>>C++</option>
                <option value="4" <?php echo in_array('4', $saved_lang) ? 'selected' : ''; ?>>JavaScript</option>
                <option value="5" <?php echo in_array('5', $saved_lang) ? 'selected' : ''; ?>>PHP</option>
                <option value="6" <?php echo in_array('6', $saved_lang) ? 'selected' : ''; ?>>Python</option>
                <option value="7" <?php echo in_array('7', $saved_lang) ? 'selected' : ''; ?>>Java</option>
                <option value="8" <?php echo in_array('8', $saved_lang) ? 'selected' : ''; ?>>Haskel</option>
                <option value="9" <?php echo in_array('9', $saved_lang) ? 'selected' : ''; ?>>Clojure</option>
                <option value="10" <?php echo in_array('10', $saved_lang) ? 'selected' : ''; ?>>Prolog</option>
                <option value="11" <?php echo in_array('11', $saved_lang) ? 'selected' : ''; ?>>Scala</option>
                <option value="12" <?php echo in_array('12', $saved_lang) ? 'selected' : ''; ?>>Go</option>    
            </select>
        </label>
        
        <label>Биография:</label> 
        <textarea name="bio"><?php echo isset($_COOKIE['saved_bio']) ? htmlspecialchars($_COOKIE['saved_bio']) : ''; ?></textarea>
        
        <div>
            <input type="checkbox" name="contract" value="1">
            С контрактом ознакомлен
        </div>
        
        <button type="submit">Сохранить</button>
    </form>
    <a href=".\autorization.php">Уже есть аккаунт?</a>
</body>
</html> 