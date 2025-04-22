# Полный комплект файлов для работы с DatabaseManager (PHP 7.4)

## 1. table.php - Вывод данных в HTML-таблицу

```php
<?php
require_once 'DatabaseManager.php';

// Инициализация базы данных
DatabaseManager::init('documents.sqlite', 
    'id=ID,title=Заголовок,author=Автор,created_at=Дата создания,status=Статус');

// Пользовательская строка для управления отображением (порядок и видимость столбцов)
$displaySettings = 'title,author,status'; // Только эти столбцы в указанном порядке

// Получаем данные
$documents = DatabaseManager::searchDocuments('');

// Парсим настройки отображения
$columnsToShow = explode(',', $displaySettings);
$allColumns = array_keys(DatabaseManager::parseUserFields('id=ID,title=Заголовок,author=Автор,created_at=Дата создания,status=Статус'));

// Функция для получения "красивого" названия поля
function getFieldLabel($field) {
    $labels = [
        'id' => 'ID',
        'title' => 'Заголовок',
        'author' => 'Автор',
        'created_at' => 'Дата создания',
        'status' => 'Статус'
    ];
    return $labels[$field] ?? $field;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Просмотр документов</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .actions { white-space: nowrap; }
    </style>
</head>
<body>
    <h1>Список документов</h1>
    <table>
        <thead>
            <tr>
                <?php foreach ($columnsToShow as $column): ?>
                    <th><?= htmlspecialchars(getFieldLabel($column)) ?></th>
                <?php endforeach; ?>
                <th class="actions">Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($documents as $doc): ?>
                <tr>
                    <?php foreach ($columnsToShow as $column): ?>
                        <td><?= htmlspecialchars($doc[$column] ?? '') ?></td>
                    <?php endforeach; ?>
                    <td class="actions">
                        <a href="redaktor.php?id=<?= $doc['id'] ?>">Редактировать</a>
                        <a href="delete.php?id=<?= $doc['id'] ?>" onclick="return confirm('Удалить документ?')">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p><a href="forms.php">Добавить новый документ</a></p>
</body>
</html>
```

## 2. forms.php - Форма добавления документа

```php
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
```

## 3. redaktor.php - Редактирование документа

```php
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
```

## 4. delete.php - Удаление документа

```php
<?php
require_once 'DatabaseManager.php';

DatabaseManager::init('documents.sqlite', 'title=Заголовок,author=Автор');

if (isset($_GET['id'])) {
    try {
        DatabaseManager::deleteDocument((int)$_GET['id']);
        header("Location: table.php?deleted=".$_GET['id']);
    } catch (Exception $e) {
        die("Ошибка при удалении: " . $e->getMessage());
    }
} else {
    header("Location: table.php");
}
exit();
```

## Особенности реализации:

1. **Гибкий вывод таблицы**:
   - Используется `$displaySettings` для управления отображаемыми столбцами
   - Поддержка пользовательских заголовков через функцию `getFieldLabel()`

2. **Безопасность**:
   - Все выводимые данные экранируются через `htmlspecialchars()`
   - Проверка типов при работе с ID документов

3. **Полный CRUD-функционал**:
   - Создание (forms.php)
   - Чтение (table.php)
   - Обновление (redaktor.php)
   - Удаление (delete.php)

4. **Пользовательский интерфейс**:
   - Простые и понятные формы
   - Подтверждение при удалении
   - Сообщения об ошибках

5. **Интеграция с DatabaseManager**:
   - Единая точка инициализации БД
   - Использование всех возможностей класса
   - Обработка транзакций и ошибок

Для работы системы достаточно разместить все файлы в одной директории с файлом DatabaseManager.php и aMagicDBH.php. База данных будет автоматически создана при первом обращении.
