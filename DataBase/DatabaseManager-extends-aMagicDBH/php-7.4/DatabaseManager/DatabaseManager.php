<?php
/**
 * DatabaseManager - класс для управления документ-ориентированными базами данных
 * Версия для PHP 7.4
 */
class DatabaseManager extends aMagicDBH {
    // Конфигурация по умолчанию
    private static $instance = null;
    private static $mainTable;
    private static $storageTable;
    private static $dbFilePath;
    private static $config = [
        'storage_suffix' => '_db',
        'auto_sync' => true,
        'enable_backup' => false,
        'debug' => false
    ];

    /**
     * Инициализация системы
     * @param string $dbFile Путь к файлу БД
     * @param string $userFieldsString Строка с полями
     * @param array $config Дополнительные настройки
     * @throws RuntimeException
     */
    public static function init($dbFile, $userFieldsString, $config = []) {
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
     * Получение экземпляра
     * @return DatabaseManager
     * @throws RuntimeException
     */
    public static function getInstance() {
        if (self::$instance === null) {
            throw new RuntimeException("DatabaseManager not initialized");
        }
        return self::$instance;
    }

    /**
     * Определение имен таблиц из имени файла
     */
    private static function deriveTableNames() {
        $filename = pathinfo(self::$dbFilePath, PATHINFO_FILENAME);
        $tableName = preg_replace('/[^\w]+/', '', $filename);
        
        if (empty($tableName)) {
            if (self::$config['debug']) {
                error_log("Invalid database filename, using default");
            }
            $tableName = 'documents';
        }
        
        self::$mainTable = $tableName;
        self::$storageTable = $tableName . self::$config['storage_suffix'];
    }

    /**
     * Подключение к базе данных
     */
    private static function connectDatabase() {
        parent::connect(['sqlite' => self::$dbFilePath]);
        
        // Оптимизация для SQLite
        if (substr(self::$dbFilePath, -8) === '.sqlite') {
            self::execute("PRAGMA journal_mode = WAL");
            self::execute("PRAGMA synchronous = NORMAL");
        }
    }

    /**
     * Проверка и создание структуры БД
     * @param string $userFieldsString
     */
    private static function ensureDatabaseStructure($userFieldsString) {
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
     * @param string $userFieldsString
     */
    private static function createMainTable($userFieldsString) {
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
    private static function createStorageTable() {
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
     * Парсинг строки полей
     * @param string $userFieldsString
     * @return array
     * @throws InvalidArgumentException
     */
    private static function parseUserFields($userFieldsString) {
        $result = [];
        $items = explode(',', $userFieldsString);

        foreach ($items as $item) {
            $parts = array_map('trim', explode('=', trim($item), 2);
            
            if (count($parts) === 2 && !empty($parts[0])) {
                $result[$parts[0]] = isset($parts[1]) ? $parts[1] : $parts[0];
            }
        }

        if (empty($result)) {
            throw new InvalidArgumentException("Invalid user fields string");
        }

        return $result;
    }

    /**
     * Проверка существования таблицы
     * @param string $table
     * @return bool
     */
    private static function tableExists($table) {
        try {
            $result = self::select("sqlite_master", [
                'type' => 'table',
                'name' => $table
            ], 'COUNT(*) as cnt');
            
            return isset($result[0]['cnt']) && $result[0]['cnt'] > 0;
        } catch (PDOException $e) {
            if (self::$config['debug']) {
                error_log("Table check error: " . $e->getMessage());
            }
            return false;
        }
    }

    /**
     * Создание резервной копии
     */
    private static function createBackupIfEnabled() {
        if (!self::$config['enable_backup'] || !file_exists(self::$dbFilePath)) {
            return;
        }

        $backupFile = self::$dbFilePath . '.bak';
        if (!copy(self::$dbFilePath, $backupFile)) {
            error_log("Failed to create backup");
        }
    }

    /**
     * Восстановление из резервной копии
     */
    private static function restoreBackupIfExists() {
        $backupFile = self::$dbFilePath . '.bak';
        
        if (file_exists($backupFile)) {
            copy($backupFile, self::$dbFilePath);
        }
    }

    // ================ CRUD-методы ================ //

    /**
     * Создание документа
     * @param array $data
     * @return int
     * @throws Exception
     */
    public static function createDocument($data) {
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        $text = implode('*', $data);

        self::beginTransaction();

        try {
            // Вставка в основную таблицу
            $docId = self::insert(
                self::$mainTable,
                array_intersect_key($data, self::parseUserFields(''))
            );

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
     * @param int $id
     * @return array|null
     */
    public static function getDocument($id) {
        $storageData = self::first(self::$storageTable, ['doc_id' => $id]);
        
        if (!$storageData) {
            return null;
        }

        $mainData = self::first(self::$mainTable, ['id' => $id]) ?: [];
        $jsonData = json_decode($storageData['json'], true) ?: [];

        return array_merge($mainData, $jsonData);
    }

    /**
     * Поиск документов
     * @param string $query
     * @param int $limit
     * @return array
     */
    public static function searchDocuments($query, $limit = 100) {
        $storageTable = self::quoteIdentifier(self::$storageTable);
        $mainTable = self::quoteIdentifier(self::$mainTable);

        $sql = "SELECT m.*, s.json 
                FROM {$mainTable} m
                JOIN {$storageTable} s ON m.id = s.doc_id
                WHERE s.text LIKE ?
                LIMIT ?";

        $results = self::cachedQuery($sql, ["%{$query}%", $limit]);
        
        return array_map(function($row) {
            $jsonData = json_decode($row['json'], true) ?: [];
            return array_merge($row, $jsonData);
        }, $results);
    }

    /**
     * Синхронизация структуры
     * @param string $userFieldsString
     */
    public static function syncStructure($userFieldsString) {
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
     * Получение колонок таблицы
     * @param string $table
     * @return array
     */
    private static function getTableColumns($table) {
        $columns = [];
        $result = self::select("PRAGMA table_info({$table})");

        foreach ($result as $row) {
            if (isset($row['name'])) {
                $columns[] = $row['name'];
            }
        }

        return $columns;
    }

    /**
     * Оптимизация БД
     */
    public static function optimize() {
        if (substr(self::$dbFilePath, -8) === '.sqlite') {
            self::execute("VACUUM");
        }
    }

    /**
     * Получение имени основной таблицы
     * @return string
     */
    public static function getMainTableName() {
        return self::$mainTable;
    }

    /**
     * Получение имени таблицы-хранилища
     * @return string
     */
    public static function getStorageTableName() {
        return self::$storageTable;
    }
}
