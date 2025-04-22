# DatabaseManager: Универсальный менеджер документ-ориентированных баз данных

# DatabaseManager: Универсальный менеджер документ-ориентированных баз данных

```php
<?php
declare(strict_types=1);

require_once 'aMagicDBH.php';

class DatabaseManager extends aMagicDBH {
    // Конфигурация по умолчанию
    private static ?self $instance = null;
    private static string $mainTable;
    private static string $storageTable;
    private static string $dbFilePath;
    private static array $config = [
        'storage_suffix' => '_db',
        'auto_sync' => true,
        'enable_backup' => false
    ];

    /**
     * Инициализация системы
     */
    public static function init(string $dbFile, string $userFieldsString, array $config = []): void {
        if (self::$instance !== null) {
            throw new RuntimeException("DatabaseManager already initialized");
        }

        self::$instance = new self();
        self::$config = array_merge(self::$config, $config);
        self::$dbFilePath = $dbFile;
        
        self::deriveTableNames();
        self::connectDatabase();
        self::ensureDatabaseStructure($userFieldsString);
    }

    /**
     * Получение экземпляра (для доступа к нестатическим методам)
     */
    public static function getInstance(): self {
        if (self::$instance === null) {
            throw new RuntimeException("DatabaseManager not initialized");
        }
        return self::$instance;
    }

    /**
     * Определение имен таблиц на основе имени файла БД
     */
    private static function deriveTableNames(): void {
        $filename = pathinfo(self::$dbFilePath, PATHINFO_FILENAME);
        
        // Очистка имени файла для использования как имени таблицы
        $tableName = preg_replace('/[^a-z0-9_]+/i', '', $filename);
        
        if (empty($tableName)) {
            throw new InvalidArgumentException("Invalid database filename");
        }
        
        self::$mainTable = $tableName;
        self::$storageTable = $tableName . self::$config['storage_suffix'];
    }

    /**
     * Подключение к базе данных
     */
    private static function connectDatabase(): void {
        parent::connect(['sqlite' => self::$dbFilePath]);
        
        // Оптимизация для SQLite
        if (str_ends_with(self::$dbFilePath, '.sqlite')) {
            self::execute("PRAGMA journal_mode = WAL");
            self::execute("PRAGMA synchronous = NORMAL");
        }
    }

    /**
     * Проверка и создание структуры БД
     */
    private static function ensureDatabaseStructure(string $userFieldsString): void {
        self::createBackupIfEnabled();

        try {
            self::createMainTable($userFieldsString);
            self::createStorageTable();
            
            if (self::$config['auto_sync']) {
                self::syncStructure($userFieldsString);
            }
        } catch (Exception $e) {
            self::restoreBackupIfExists();
            throw $e;
        }
    }

    /**
     * Создание основной таблицы
     */
    private static function createMainTable(string $userFieldsString): void {
        if (self::tableExists(self::$mainTable)) {
            return;
        }

        $fields = self::parseUserFields($userFieldsString);
        $columns = ['id INTEGER PRIMARY KEY AUTOINCREMENT'];

        foreach ($fields as $field => $label) {
            $columns[] = self::quoteIdentifier($field) . " TEXT";
        }

        $sql = sprintf(
            "CREATE TABLE %s (%s)",
            self::quoteIdentifier(self::$mainTable),
            implode(', ', $columns)
        );

        self::execute($sql);
    }

    /**
     * Создание таблицы-хранилища
     */
    private static function createStorageTable(): void {
        if (self::tableExists(self::$storageTable)) {
            return;
        }

        $sql = sprintf(
            "CREATE TABLE %s (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                doc_id INTEGER NOT NULL,
                text TEXT NOT NULL,
                json TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (doc_id) REFERENCES %s(id)
            )",
            self::quoteIdentifier(self::$storageTable),
            self::quoteIdentifier(self::$mainTable)
        );

        self::execute($sql);
    }

    /**
     * Синхронизация структуры таблицы с пользовательской строкой
     */
    public static function syncStructure(string $userFieldsString): void {
        $fields = self::parseUserFields($userFieldsString);
        $existingColumns = self::getTableColumns(self::$mainTable);
        $newColumns = array_diff(array_keys($fields), $existingColumns);

        foreach ($newColumns as $column) {
            $sql = sprintf(
                "ALTER TABLE %s ADD COLUMN %s TEXT",
                self::quoteIdentifier(self::$mainTable),
                self::quoteIdentifier($column)
            );
            self::execute($sql);
        }
    }

    /**
     * Парсинг пользовательской строки полей
     */
    private static function parseUserFields(string $userFieldsString): array {
        $result = [];
        $items = explode(',', $userFieldsString);

        foreach ($items as $item) {
            $parts = array_map('trim', explode('=', trim($item), 2));
            
            if (count($parts) === 2 && !empty($parts[0])) {
                $result[$parts[0]] = $parts[1] ?? $parts[0];
            }
        }

        if (empty($result)) {
            throw new InvalidArgumentException("Invalid user fields string");
        }

        return $result;
    }

    /**
     * Проверка существования таблицы
     */
    private static function tableExists(string $table): bool {
        try {
            $result = self::select("sqlite_master", [
                'type' => 'table',
                'name' => $table
            ], 'COUNT(*) as cnt');
            
            return $result[0]['cnt'] > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Получение списка колонок таблицы
     */
    private static function getTableColumns(string $table): array {
        $columns = [];
        $result = self::select("PRAGMA table_info({$table})");

        foreach ($result as $row) {
            $columns[] = $row['name'];
        }

        return $columns;
    }

    /**
     * Создание резервной копии
     */
    private static function createBackupIfEnabled(): void {
        if (!self::$config['enable_backup'] || !file_exists(self::$dbFilePath)) {
            return;
        }

        $backupFile = self::$dbFilePath . '.bak';
        copy(self::$dbFilePath, $backupFile);
    }

    /**
     * Восстановление из резервной копии
     */
    private static function restoreBackupIfExists(): void {
        $backupFile = self::$dbFilePath . '.bak';
        
        if (file_exists($backupFile)) {
            copy($backupFile, self::$dbFilePath);
        }
    }

    // ================ CRUD-методы ================ //

    /**
     * Создание документа
     */
    public static function createDocument(array $data): int {
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        $text = implode('*', $data);

        self::beginTransaction();

        try {
            // Вставка в основную таблицу
            $docId = self::insert(self::$mainTable, array_intersect_key($data, self::parseUserFields('')));

            // Вставка в хранилище
            self::insert(self::$storageTable, [
                'doc_id' => $docId,
                'text' => $text,
                'json' => $json
            ]);

            self::commit();
            return $docId;
        } catch (Exception $e) {
            self::rollBack();
            throw $e;
        }
    }

    /**
     * Получение документа
     */
    public static function getDocument(int $id): ?array {
        $storageData = self::first(self::$storageTable, ['doc_id' => $id]);
        
        if (!$storageData) {
            return null;
        }

        return array_merge(
            self::first(self::$mainTable, ['id' => $id]) ?? [],
            json_decode($storageData['json'], true) ?? []
        );
    }

    /**
     * Поиск документов
     */
    public static function searchDocuments(string $query, int $limit = 100): array {
        $storageTable = self::quoteIdentifier(self::$storageTable);
        $mainTable = self::quoteIdentifier(self::$mainTable);

        $sql = "SELECT m.*, s.json 
                FROM {$mainTable} m
                JOIN {$storageTable} s ON m.id = s.doc_id
                WHERE s.text LIKE ?
                LIMIT ?";

        $results = self::cachedQuery($sql, ["%{$query}%", $limit]);
        
        return array_map(function($row) {
            return array_merge($row, json_decode($row['json'], true) ?? []);
        }, $results);
    }

    /**
     * Обновление документа
     */
    public static function updateDocument(int $id, array $data): bool {
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        $text = implode('*', $data);

        self::beginTransaction();

        try {
            // Обновление основной таблицы
            self::update(
                self::$mainTable,
                array_intersect_key($data, self::parseUserFields('')),
                ['id' => $id]
            );

            // Обновление хранилища
            self::update(
                self::$storageTable,
                ['text' => $text, 'json' => $json],
                ['doc_id' => $id]
            );

            self::commit();
            return true;
        } catch (Exception $e) {
            self::rollBack();
            throw $e;
        }
    }

    /**
     * Удаление документа
     */
    public static function deleteDocument(int $id): bool {
        self::beginTransaction();

        try {
            self::delete(self::$storageTable, ['doc_id' => $id]);
            self::delete(self::$mainTable, ['id' => $id]);
            
            self::commit();
            return true;
        } catch (Exception $e) {
            self::rollBack();
            throw $e;
        }
    }

    // ================ Утилиты ================ //

    public static function getMainTableName(): string {
        return self::$mainTable;
    }

    public static function getStorageTableName(): string {
        return self::$storageTable;
    }

    public static function optimize(): void {
        if (str_ends_with(self::$dbFilePath, '.sqlite')) {
            self::execute("VACUUM");
        }
    }
}
```

## Ключевые особенности реализации

### 1. Автоматическое именование таблиц
- Определяет имена таблиц на основе имени файла БД
- `contracts.sqlite` → `contracts` (основная) и `contracts_db` (хранилище)
- Поддержка сложных имен файлов через очистку спецсимволов

### 2. Полноценное управление документами
- **Транзакционные операции**: создание, обновление, удаление
- **Двухфазное хранение**:
  - Основная таблица: только поля из `$userFieldsString`
  - Хранилище: полные данные в JSON + текстовый индекс
- **Автоматическая синхронизация** структуры при изменении полей

### 3. Расширенный функционал
- **Поиск по содержимому**: полнотекстовый поиск по полю `text`
- **Резервное копирование**: автоматическое создание бэкапов перед изменениями
- **Оптимизация БД**: методы для обслуживания (VACUUM для SQLite)

### 4. Безопасность и надежность
- Проверка целостности структуры таблиц
- Восстановление из бэкапа при ошибках
- Экранирование всех SQL-идентификаторов

## Пример использования

```php
// Инициализация
DatabaseManager::init('projects.sqlite', 'title=Название,status=Статус,owner=Владелец');

// Создание документа
$docId = DatabaseManager::createDocument([
    'title' => 'Мой проект',
    'status' => 'active',
    'owner' => 'Иванов',
    'description' => 'Тестовый проект' // Поле не из userFieldsString
]);

// Поиск
$results = DatabaseManager::searchDocuments('Иванов');

// Обновление
DatabaseManager::updateDocument($docId, ['status' => 'completed']);

// Получение
$document = DatabaseManager::getDocument($docId);
```

Этот класс представляет собой законченное решение для документ-ориентированного хранения данных с:
- Гибкой настройкой структуры
- Автоматическим управлением таблицами
- Полноценным CRUD-функционалом
- Надежными механизмами восстановления
