<?php
require_once 'DatabaseManager.php';

// Инициализация базы данных
DatabaseManager::init('documents.sqlite', 
    'title=Заголовок,author=Автор,status=Статус,content=Содержание');

$document = null;
$error = '';

// Получение документа для редактирования
if (isset($_GET['id'])) {
    $document = DatabaseManager::getDocument((int)$_GET['id']);
    if (!$document) {
        $error = "Документ не найден";
    }
}

// Обработка сохранения изменений
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    try {
        $data = [
            'id' => $_POST['id'],
            'title' => $_POST['title'] ?? '',
            'author' => $_POST['author'] ?? '',
            'status' => $_POST['status'] ?? 'draft',
            'content' => $_POST['content'] ?? '',
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        DatabaseManager::updateDocument((int)$_POST['id'], $data);
        header("Location: table.php?updated=".$_POST['id']);
        exit();
    } catch (Exception $e) {
        $error = "Ошибка при обновлении: " . $e->getMessage();
        $document = $data; // Сохраняем введенные данные для повторного отображения
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование документа</title>
    <style>
        .form-group { margin-bottom: 15px; }
        label { display: inline-block; width: 150px; }
        input[type="text"], textarea { width: 300px; }
        textarea { height: 150px; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Редактирование документа</h1>
    
    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if ($document): ?>
        <form method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($document['id']) ?>">
            
            <div class="form-group">
                <label for="title">Заголовок:</label>
                <input type="text" id="title" name="title" 
                       value="<?= htmlspecialchars($document['title'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="author">Автор:</label>
                <input type="text" id="author" name="author" 
                       value="<?= htmlspecialchars($document['author'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="status">Статус:</label>
                <select id="status" name="status">
                    <option value="draft" <?= ($document['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Черновик</option>
                    <option value="published" <?= ($document['status'] ?? '') === 'published' ? 'selected' : '' ?>>Опубликован</option>
                    <option value="archived" <?= ($document['status'] ?? '') === 'archived' ? 'selected' : '' ?>>В архиве</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="content">Содержание:</label>
                <textarea id="content" name="content"><?= htmlspecialchars($document['content'] ?? '') ?></textarea>
            </div>
            
            <div class="form-group">
                <input type="submit" value="Сохранить">
                <a href="table.php">Отмена</a>
            </div>
        </form>
    <?php else: ?>
        <p>Документ не найден</p>
        <p><a href="table.php">Вернуться к списку</a></p>
    <?php endif; ?>
</body>
</html>
