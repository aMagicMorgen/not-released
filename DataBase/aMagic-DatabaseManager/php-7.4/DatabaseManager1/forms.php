<?php
require_once 'DatabaseManager.php';

// Инициализация базы данных
DatabaseManager::init('documents.sqlite', 
    'title=Заголовок,author=Автор,status=Статус,content=Содержание');

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = [
            'title' => $_POST['title'] ?? '',
            'author' => $_POST['author'] ?? '',
            'status' => $_POST['status'] ?? 'draft',
            'content' => $_POST['content'] ?? '',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $id = DatabaseManager::createDocument($data);
        header("Location: table.php?created=$id");
        exit();
    } catch (Exception $e) {
        $error = "Ошибка при сохранении: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавление документа</title>
    <style>
        .form-group { margin-bottom: 15px; }
        label { display: inline-block; width: 150px; }
        input[type="text"], textarea { width: 300px; }
        textarea { height: 150px; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Добавить новый документ</h1>
    
    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form method="post">
        <div class="form-group">
            <label for="title">Заголовок:</label>
            <input type="text" id="title" name="title" required>
        </div>
        
        <div class="form-group">
            <label for="author">Автор:</label>
            <input type="text" id="author" name="author">
        </div>
        
        <div class="form-group">
            <label for="status">Статус:</label>
            <select id="status" name="status">
                <option value="draft">Черновик</option>
                <option value="published">Опубликован</option>
                <option value="archived">В архиве</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="content">Содержание:</label>
            <textarea id="content" name="content"></textarea>
        </div>
        
        <div class="form-group">
            <input type="submit" value="Сохранить">
            <a href="table.php">Отмена</a>
        </div>
    </form>
</body>
</html>
