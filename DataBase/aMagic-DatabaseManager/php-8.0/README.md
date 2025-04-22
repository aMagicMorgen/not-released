# Полная документация DatabaseManager PHP 8.0+

## Оглавление
1. [Подключение и инициализация](#подключение-и-инициализация)
2. [Структура базы данных](#структура-базы-данных)
3. [Основные CRUD-методы](#основные-crud-методы)
4. [Дополнительные методы](#дополнительные-методы)
5. [Наследуемые методы от aMagicDBH](#наследуемые-методы-от-amagicdbh)
6. [Примеры использования](#примеры-использования)

## Подключение и инициализация

### Требования
- PHP 8.0+
- Класс aMagicDBH должен быть доступен

### Установка
```php
require_once 'aMagicDBH.php';
require_once 'DatabaseManager.php';
```

### Инициализация
```php
DatabaseManager::init(
    string $dbFile,          // Путь к файлу БД (например: 'documents.sqlite')
    string $userFieldsString,// Строка полей (например: 'title=Название,status=Статус')
    array $config = [        // Дополнительные настройки (опционально)
        'storage_suffix' => '_db', // Суффикс для таблицы-хранилища
        'auto_sync' => true,       // Автоматическая синхронизация структуры
        'enable_backup' => false   // Создание резервных копий
    ]
);
```

**Пример:**
```php
DatabaseManager::init(
    'projects.sqlite',
    'title=Название,status=Статус,owner=Владелец',
    ['enable_backup' => true]
);
```

## Структура базы данных

Для каждого файла БД создается две таблицы:

1. **Основная таблица** (название = имя файла без расширения)
   - Содержит только поля, указанные в `$userFieldsString`
   - Пример для `projects.sqlite`:
     ```sql
     CREATE TABLE projects (
         id INTEGER PRIMARY KEY AUTOINCREMENT,
         title TEXT,
         status TEXT,
         owner TEXT
     )
     ```

2. **Таблица-хранилище** (основное название + суффикс)
   - Содержит полные данные документов
   - Пример:
     ```sql
     CREATE TABLE projects_db (
         id INTEGER PRIMARY KEY AUTOINCREMENT,
         doc_id INTEGER NOT NULL,
         text TEXT NOT NULL,  // Все данные через *
         json TEXT NOT NULL,  // Полные данные в JSON
         created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
         FOREIGN KEY (doc_id) REFERENCES projects(id)
     )
     ```

## Основные CRUD-методы

### 1. Создание документа
```php
DatabaseManager::createDocument(array $data): int
```
- `$data` - ассоциативный массив данных документа
- Возвращает ID созданного документа

**Пример:**
```php
$docId = DatabaseManager::createDocument([
    'title' => 'Новый проект',
    'status' => 'active',
    'owner' => 'Иванов',
    'description' => 'Тестовый проект' // Поле не из userFieldsString
]);
```

### 2. Получение документа
```php
DatabaseManager::getDocument(int $id): ?array
```
- Возвращает полные данные документа или null, если не найден

**Пример:**
```php
$document = DatabaseManager::getDocument(1);
/*
[
    'id' => 1,
    'title' => 'Новый проект',
    'status' => 'active',
    'owner' => 'Иванов',
    'description' => 'Тестовый проект'
]
*/
```

### 3. Поиск документов
```php
DatabaseManager::searchDocuments(string $query, int $limit = 100): array
```
- Ищет по полю `text` в хранилище (все данные через *)
- Возвращает массив найденных документов

**Пример:**
```php
$results = DatabaseManager::searchDocuments('Иванов');
```

### 4. Обновление документа
```php
DatabaseManager::updateDocument(int $id, array $data): bool
```
- Обновляет документ с указанным ID
- Возвращает true при успехе

**Пример:**
```php
DatabaseManager::updateDocument(1, [
    'status' => 'completed',
    'progress' => 100
]);
```

### 5. Удаление документа
```php
DatabaseManager::deleteDocument(int $id): bool
```
- Удаляет документ и связанные данные из хранилища
- Возвращает true при успехе

**Пример:**
```php
DatabaseManager::deleteDocument(1);
```

## Дополнительные методы

### Синхронизация структуры
```php
DatabaseManager::syncStructure(string $userFieldsString): void
```
- Обновляет структуру таблицы согласно новой строке полей

**Пример:**
```php
// Добавляем новое поле 'priority'
DatabaseManager::syncStructure('title=Название,status=Статус,priority=Приоритет');
```

### Оптимизация базы данных
```php
DatabaseManager::optimize(): void
```
- Выполняет оптимизацию (VACUUM для SQLite)

### Получение имен таблиц
```php
DatabaseManager::getMainTableName(): string
DatabaseManager::getStorageTableName(): string
```

### Создание резервной копии
```php
// Включается через конфиг при инициализации
$config = ['enable_backup' => true];
```

## Наследуемые методы от aMagicDBH

DatabaseManager наследует все методы родительского класса aMagicDBH:

### CRUD-операции
```php
::insert(string $table, array $data): int
::select(string $table, array $conditions = [], string $columns = '*', ...): array
::update(string $table, array $data, array $conditions): int
::delete(string $table, array $conditions): int
::first(string $table, array $conditions = [], string $columns = '*'): ?array
```

### Работа с транзакциями
```php
::beginTransaction(): void
::commit(): void
::rollBack(): void
::transaction(callable $callback): mixed
```

### Агрегатные функции
```php
::count(string $table, array $conditions = []): int
::sum(string $table, string $column, array $conditions = []): float
```

### Утилиты
```php
::execute(string $sql, array $params = []): PDOStatement
::quoteIdentifier(string $identifier): string
```

## Примеры использования

### 1. Полный цикл работы с документами
```php
// Инициализация
DatabaseManager::init('tasks.sqlite', 'title=Задача,status=Статус');

// Создание
$taskId = DatabaseManager::createDocument([
    'title' => 'Написать документацию',
    'status' => 'В работе',
    'priority' => 'high'
]);

// Получение
$task = DatabaseManager::getDocument($taskId);

// Поиск
$urgentTasks = DatabaseManager::searchDocuments('high');

// Обновление
DatabaseManager::updateDocument($taskId, [
    'status' => 'Завершено',
    'completed_at' => date('Y-m-d')
]);

// Удаление
DatabaseManager::deleteDocument($taskId);
```

### 2. Работа с несколькими базами
```php
// База проектов
DatabaseManager::init('projects.sqlite', 'name=Название');
$projectId = DatabaseManager::createDocument(['name' => 'Проект X']);

// База задач (отдельный файл)
DatabaseManager::init('tasks.sqlite', 'title=Задача');
$taskId = DatabaseManager::createDocument(['title' => 'Задача для проекта X']);
```

### 3. Использование транзакций
```php
DatabaseManager::transaction(function() {
    $docId = DatabaseManager::createDocument([
        'title' => 'Важный документ',
        'content' => 'Конфиденциально'
    ]);
    
    // Другие операции...
    
    if ($someCondition) {
        throw new Exception("Отмена транзакции");
    }
});
```

## Заключение

DatabaseManager предоставляет:
- Гибкую систему хранения документов
- Автоматическое управление структурой
- Полноценный CRUD-функционал
- Наследование всех возможностей aMagicDBH
- Надежные механизмы транзакций и восстановления

Для работы требуется только указать файл БД и строку с полями - все остальные операции выполняются автоматически.
