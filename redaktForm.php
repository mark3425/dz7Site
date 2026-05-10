<?php
// CSRF токен из сессии
$csrf_token = $_SESSION['csrf_token'] ?? '';
?>
<html>
<head>
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <h1>Редактирование учётной записи</h1>
    <label>Поля, которые не надо изменять, оставить пустыми.</label>

    <form method="POST">
        <!-- CSRF токен -->
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
        
        <label>ФИО: 
            <input name="fio">
        </label>
        
        <label>Телефон: 
            <input name="phone">
        </label>
        
        <label>Почта: 
            <input name="email">
        </label>
        
        <label>Дата рождения: 
            <input name="brithDate" type="date">
        </label>
        
        <label>Пол:
            <input type="radio" name="gender" value="male">Мужской
            <input type="radio" name="gender" value="female">Женский
        </label>
        
        <label>Любимый язык программирования: 
            <select name="lang_id[]" multiple size="5">
                <option value="1">Pascal</option>
                <option value="2">C</option>
                <option value="3">C++</option>
                <option value="4">JavaScript</option>
                <option value="5">PHP</option>
                <option value="6">Python</option>
                <option value="7">Java</option>
                <option value="8">Haskel</option>
                <option value="9">Clojure</option>
                <option value="10">Prolog</option>
                <option value="11">Scala</option>
                <option value="12">Go</option>    
            </select>
        </label>
        
        <label>Биография:</label> 
        <textarea name="bio"></textarea>
        
        <button type="submit">Редактировать</button>
    </form>
    <a href="Admin.php">Страница администратора</a>
</body>
</html>