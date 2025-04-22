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
