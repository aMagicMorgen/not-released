<?php
declare(strict_types=1);

interface Cache {
    public function set(string $key, $value, int $ttl = 3600): void;
    public function get(string $key);
    public function delete(string $key): void;
}

abstract class DatabaseDriver {
    abstract public function getQuoteChar(): string;
    abstract public function autoIncrement(): string;
    abstract public function initCommands(): array;
    abstract public function formatDate(string $func): string;
    abstract public function handleLimit(int $limit, int $offset): string;
    
    public function isTransactional(): bool { return true; }
    public function supportsForeignKeys(): bool { return true; }
    
    public function convertBoolean(bool $value): int {
        return $value ? 1 : 0;
    }
}

class MySQLDriver extends DatabaseDriver {
    public function getQuoteChar(): string { return '`'; }
    public function autoIncrement(): string { return 'AUTO_INCREMENT'; }
    public function initCommands(): array { return ['SET sql_mode = "STRICT_ALL_TABLES"']; }
    
    public function formatDate(string $func): string {
        $func = strtoupper($func);
        if ($func === 'NOW') return 'NOW()';
        if ($func === 'DATE') return 'CURDATE()';
        if ($func === 'TIME') return 'CURTIME()';
        return "DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i:%s')";
    }
    
    public function handleLimit(int $limit, int $offset): string {
        return "LIMIT $limit OFFSET $offset";
    }
}

class SQLiteDriver extends DatabaseDriver {
    public function getQuoteChar(): string { return '"'; }
    public function autoIncrement(): string { return 'AUTOINCREMENT'; }
    public function initCommands(): array { return ['PRAGMA journal_mode = WAL']; }
    
    public function formatDate(string $func): string {
        $func = strtoupper($func);
        if ($func === 'NOW') return "strftime('%Y-%m-%d %H:%M:%S', 'now')";
        if ($func === 'DATE') return "strftime('%Y-%m-%d', 'now')";
        if ($func === 'TIME') return "strftime('%H:%M:%S', 'now')";
        return "strftime('%Y-%m-%d %H:%M:%S', 'now')";
    }
    
    public function handleLimit(int $limit, int $offset): string {
        return "LIMIT $limit OFFSET $offset";
    }
/*    
    public function isTransactional(): bool { return false; }
*/
	public function isTransactional(): bool {
        return true; // SQLite поддерживает транзакции
    }
    public function supportsForeignKeys(): bool { return false; }
}

class aMagicDBH {
    /** @var PDO|null */
    private static $instance = null;
    
    /** @var DatabaseDriver|null */
    private static $driver = null;
    
    /** @var Cache|null */
    private static $cache = null;
    
    /** @var array */
    private static $config = [
        'timeout' => 5,
        'debug' => false,
        'enable_cache' => true
    ];
	
/////////// ДОБАВЛЕНЫ новые методы для исправления ошибки при отправке формы

public static function beginTransaction(): void {
    if (!self::$instance) {
        throw new RuntimeException("Database connection not established");
    }
    
    if (!self::$driver->isTransactional()) {
        throw new RuntimeException("Transactions not supported for current driver");
    }
    
    try {
        self::$instance->beginTransaction();
    } catch (PDOException $e) {
        throw new RuntimeException("Failed to start transaction: " . $e->getMessage());
    }
}

public static function commit(): void {
    if (!self::$instance) {
        throw new RuntimeException("Database connection not established");
    }
    
    try {
        self::$instance->commit();
    } catch (PDOException $e) {
        throw new RuntimeException("Failed to commit transaction: " . $e->getMessage());
    }
}

public static function rollBack(): void {
    if (!self::$instance) {
        return; // Нет соединения - нечего откатывать
    }
    
    try {
        if (self::$instance->inTransaction()) {
            self::$instance->rollBack();
        }
    } catch (PDOException $e) {
        throw new RuntimeException("Failed to rollback transaction: " . $e->getMessage());
    }
}

public static function inTransaction(): bool {
    return self::$instance && self::$instance->inTransaction();
}
///////////////////////

    public static function validateConfig(array $config): void {
        $driver = $config['driver'] ?? 'sqlite';
        
        if ($driver === 'sqlite') {
            if (!isset($config['path'])) {
                throw new InvalidArgumentException("SQLite requires 'path' parameter in config");
            }
        } else {
            if (!isset($config['dbname'])) {
                throw new InvalidArgumentException("Database name (dbname) is required in config");
            }
            if (!isset($config['host'])) {
                throw new InvalidArgumentException("Host is required for non-SQLite databases");
            }
        }
    }

    public static function connect(array $config): void {
        self::validateConfig($config);
        
        $dsn = self::buildDsn($config);
        $options = self::buildOptions($config);
        
        self::$instance = new PDO(
            $dsn,
            $config['user'] ?? null,
            $config['pass'] ?? null,
            $options
        );

        self::detectDriver();
        self::runInitCommands();
        self::$config = array_merge(self::$config, $config);
    }

    public static function setCache(Cache $cache): void {
        self::$cache = $cache;
    }

    public static function insert(string $table, array $data): string {
        self::validateTableName($table);
        $columns = array_keys($data);
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = sprintf("INSERT INTO %s (%s) VALUES (%s)",
            self::quoteIdentifier($table),
            implode(', ', array_map([self::class, 'quoteIdentifier'], $columns)),
            $placeholders
        );

        self::execute($sql, array_values($data));
        return self::$instance->lastInsertId();
    }

    public static function select(
        string $table,
        array $conditions = [],
        string $columns = '*',
        string $order = '',
        int $limit = 0,
        int $offset = 0
    ): array {
        self::validateTableName($table);
        [$where, $params] = self::buildWhere($conditions);
        $sql = sprintf("SELECT %s FROM %s", $columns, self::quoteIdentifier($table));
        
        if (!empty($where)) $sql .= " WHERE $where";
        if (!empty($order)) $sql .= " ORDER BY $order";
        if ($limit > 0) $sql .= " " . self::$driver->handleLimit($limit, $offset);

        return self::cachedQuery($sql, $params);
    }

    public static function update(string $table, array $data, array $conditions): int {
        self::validateTableName($table);
        [$set, $setParams] = self::buildSet($data);
        [$where, $whereParams] = self::buildWhere($conditions);
        
        $sql = sprintf("UPDATE %s SET %s WHERE %s",
            self::quoteIdentifier($table),
            $set,
            $where
        );

        $stmt = self::execute($sql, array_merge($setParams, $whereParams));
        return $stmt->rowCount();
    }

    public static function delete(string $table, array $conditions): int {
        self::validateTableName($table);
        [$where, $params] = self::buildWhere($conditions);
        
        $sql = sprintf("DELETE FROM %s WHERE %s",
            self::quoteIdentifier($table),
            $where
        );

        $stmt = self::execute($sql, $params);
        return $stmt->rowCount();
    }

    public static function transaction(callable $callback) {
        if (!self::$driver->isTransactional()) {
            throw new RuntimeException("Transactions not supported for current driver");
        }

        try {
            self::$instance->beginTransaction();
            $result = $callback();
            self::$instance->commit();
            return $result;
        } catch (Throwable $e) {
            self::$instance->rollBack();
            throw new RuntimeException("Transaction failed: " . $e->getMessage(), 0, $e);
        }
    }

    public static function first(string $table, array $conditions = [], string $columns = '*') {
        $result = self::select($table, $conditions, $columns, '', 1);
        return $result[0] ?? null;
    }

    public static function count(string $table, array $conditions = []): int {
        $result = self::first($table, $conditions, 'COUNT(*) as count');
        return (int)($result['count'] ?? 0);
    }

    public static function sum(string $table, string $column, array $conditions = []): float {
        $result = self::first($table, $conditions, "SUM($column) as sum");
        return (float)($result['sum'] ?? 0.0);
    }

    public static function optimize(): void {
        $driverClass = get_class(self::$driver);
        if ($driverClass === SQLiteDriver::class) {
            self::execute('VACUUM');
        } elseif ($driverClass === MySQLDriver::class) {
            self::execute('OPTIMIZE TABLE');
        }
    }

    private static function buildDsn(array $config): string {
        if (($config['driver'] ?? null) === 'sqlite') {
            return 'sqlite:' . $config['path'];
        }
        
        $charset = $config['charset'] ?? 'utf8mb4';
        return sprintf("mysql:host=%s;dbname=%s;charset=%s",
            $config['host'],
            $config['dbname'],
            $charset
        );
    }

    private static function buildOptions(array $config): array {
        return [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => $config['timeout'] ?? 5,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
    }

    private static function detectDriver(): void {
        $driverName = self::$instance->getAttribute(PDO::ATTR_DRIVER_NAME);
        if ($driverName === 'mysql') {
            self::$driver = new MySQLDriver();
        } elseif ($driverName === 'sqlite') {
            self::$driver = new SQLiteDriver();
        } else {
            throw new RuntimeException("Unsupported driver: $driverName");
        }
    }

    private static function runInitCommands(): void {
        foreach (self::$driver->initCommands() as $command) {
            self::$instance->exec($command);
        }
    }

    private static function buildWhere(array $conditions): array {
        $clauses = [];
        $params = [];
        
        foreach ($conditions as $key => $value) {
            $operator = '=';
            $field = $key;
            
            if (strpos($key, ' ') !== false) {
                list($field, $operator) = explode(' ', $key, 2);
            }
            
            $clauses[] = sprintf("%s %s ?", 
                self::quoteIdentifier($field), 
                self::validateOperator($operator)
            );
            $params[] = $value;
        }
        
        return [
            $clauses ? implode(' AND ', $clauses) : '1=1',
            $params
        ];
    }

    private static function buildSet(array $data): array {
        $set = [];
        $params = [];
        
        foreach ($data as $key => $value) {
            $set[] = sprintf("%s = ?", self::quoteIdentifier($key));
            $params[] = $value;
        }
        
        return [implode(', ', $set), $params];
    }
	
	
// ЗАМЕНА private на protected
    protected static function cachedQuery(string $sql, array $params): array {
        $cacheKey = self::generateCacheKey($sql, $params);
        
        if (self::$config['enable_cache'] && self::$cache) {
            $cached = self::$cache->get($cacheKey);
            if ($cached !== null) {
                return $cached;
            }
        }

        $stmt = self::execute($sql, $params);
        $result = $stmt->fetchAll();

        if (self::$config['enable_cache'] && self::$cache) {
            self::$cache->set($cacheKey, $result);
        }

        return $result;
    }

// ЗАМЕНА private на protected
    protected static function execute(string $sql, array $params = []): PDOStatement {
        try {
            $stmt = self::$instance->prepare($sql);
            $stmt->execute(self::normalizeParams($params));
            return $stmt;
        } catch (PDOException $e) {
            self::handleError($e, $sql, $params);
            throw $e;
        }
    }

protected static function validateTableName(string $table): void {
    // Разрешаем специальные запросы PRAGMA
    if (strpos($table, 'PRAGMA') === 0) {
        return;
    }
    
    if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $table)) {
        throw new InvalidArgumentException("Invalid table name: $table");
    }
}

    private static function validateOperator(string $operator): string {
        $allowed = ['=', '<>', '!=', '<', '>', '<=', '>=', 'LIKE', 'IN'];
        if (!in_array(strtoupper($operator), $allowed)) {
            throw new InvalidArgumentException("Invalid operator: $operator");
        }
        return $operator;
    }
// ЗАМЕНА private на protected
    protected static function quoteIdentifier(string $identifier): string {
        return self::$driver->getQuoteChar() . $identifier . self::$driver->getQuoteChar();
    }
// ЗАМЕНА private на protected
    protected static function normalizeParams(array $params): array {
        return array_map(function($value) {
            if ($value instanceof DateTimeInterface) {
                return $value->format('Y-m-d H:i:s');
            }
            if (is_bool($value)) {
                return self::$driver->convertBoolean($value);
            }
            return $value;
        }, $params);
    }
// ЗАМЕНА private на protected
    protected static function handleError(PDOException $e, string $sql, array $params): void {
        $message = sprintf("[DB Error] %s\nSQL: %s\nParams: %s",
            $e->getMessage(),
            $sql,
            json_encode($params)
        );

        error_log($message);

        if (self::$config['debug']) {
            throw new RuntimeException($message);
        }
    }

    private static function generateCacheKey(string $sql, array $params): string {
        return md5($sql . json_encode($params));
    }
}

class DatabaseManager extends aMagicDBH {
    /** @var DatabaseManager|null */
    private static $instance = null;
    
    /** @var string */
    private static $mainTable;
    
    /** @var string */
    private static $storageTable;
    
    /** @var string */
    private static $dbFilePath;
    
    /** @var array */
    private static $config = [
        'storage_suffix' => '_db',
        'auto_sync' => true,
        'enable_backup' => false,
        'debug' => false
    ];

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

    public static function getInstance(): self {
        if (self::$instance === null) {
            throw new RuntimeException("DatabaseManager not initialized");
        }
        return self::$instance;
    }

    private static function deriveTableNames(): void {
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

    private static function connectDatabase(): void {
        parent::connect([
            'driver' => 'sqlite',
            'path' => self::$dbFilePath
        ]);
        
        if (substr(self::$dbFilePath, -8) === '.sqlite') {
            self::execute("PRAGMA journal_mode = WAL");
            self::execute("PRAGMA synchronous = NORMAL");
        }
    }

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

private static function createMainTable(string $userFieldsString): void {
    if (self::tableExists(self::$mainTable)) {
        return;
    }

    $fields = self::parseUserFields($userFieldsString);
    
    // Убедимся, что id не дублируется в пользовательских полях
    if (array_key_exists('id', $fields)) {
        unset($fields['id']);
    }

    $columns = ['id INTEGER PRIMARY KEY AUTOINCREMENT'];

    foreach ($fields as $field => $label) {
        // Проверка валидности имени поля
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $field)) {
            throw new InvalidArgumentException("Invalid field name: $field");
        }
        $columns[] = self::quoteIdentifier($field) . " TEXT";
    }

    $sql = sprintf(
        "CREATE TABLE %s (%s)",
        self::quoteIdentifier(self::$mainTable),
        implode(', ', $columns)
    );

    try {
        self::execute($sql);
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'duplicate column name') !== false) {
            // Если таблица уже существует с такими столбцами
            return;
        }
        throw $e;
    }
}


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
// ЗАМЕНА	private на public
public static function parseUserFields(string $userFieldsString): array {
    $result = [];
    
    // Если строка пустая - возвращаем пустой массив
    if (trim($userFieldsString) === '') {
        return $result;
    }

    $items = explode(',', $userFieldsString);

    foreach ($items as $item) {
        $parts = array_map('trim', explode('=', trim($item), 2));
        
        if (!empty($parts[0])) {
            $fieldName = $parts[0];
            $label = $parts[1] ?? $parts[0];
            $result[$fieldName] = $label;
        }
    }

    return $result;
}

    private static function tableExists(string $table): bool {
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

    private static function createBackupIfEnabled(): void {
        if (!self::$config['enable_backup'] || !file_exists(self::$dbFilePath)) {
            return;
        }

        $backupFile = self::$dbFilePath . '.bak';
        if (!copy(self::$dbFilePath, $backupFile)) {
            error_log("Failed to create backup");
        }
    }

    private static function restoreBackupIfExists(): void {
        $backupFile = self::$dbFilePath . '.bak';
        
        if (file_exists($backupFile)) {
            copy($backupFile, self::$dbFilePath);
        }
    }

public static function createDocument(array $data): int {
    $json = json_encode($data, JSON_UNESCAPED_UNICODE);
    if ($json === false) {
        throw new RuntimeException("Failed to encode document data");
    }
    
    $text = implode('*', $data);

    $transactionStarted = false;
    try {
        if (!parent::inTransaction()) {
            parent::beginTransaction();
            $transactionStarted = true;
        }

        // Получаем список полей из структуры таблицы
        $tableFields = self::getTableColumns(self::$mainTable);
        $fieldsToInsert = array_intersect_key($data, array_flip($tableFields));

        $docId = self::insert(self::$mainTable, $fieldsToInsert);

        self::insert(self::$storageTable, [
            'doc_id' => $docId,
            'text' => $text,
            'json' => $json
        ]);

        if ($transactionStarted) {
            parent::commit();
        }
        
        return $docId;
    } catch (Exception $e) {
        if ($transactionStarted && parent::inTransaction()) {
            parent::rollBack();
        }
        throw new RuntimeException("Error creating document: " . $e->getMessage());
    }
}

    public static function getDocument(int $id) {
        $storageData = self::first(self::$storageTable, ['doc_id' => $id]);
        
        if (!$storageData) {
            return null;
        }

        $mainData = self::first(self::$mainTable, ['id' => $id]) ?: [];
        $jsonData = json_decode($storageData['json'], true) ?: [];

        return array_merge($mainData, $jsonData);
    }

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
            $jsonData = json_decode($row['json'], true) ?: [];
            return array_merge($row, $jsonData);
        }, $results);
    }
// Обновленный
public static function syncStructure(string $userFieldsString): void {
    $fields = self::parseUserFields($userFieldsString);
    $existingColumns = self::getTableColumns(self::$mainTable);
    $newColumns = array_diff(array_keys($fields), $existingColumns);

    foreach ($newColumns as $column) {
        try {
            $sql = sprintf(
                "ALTER TABLE %s ADD COLUMN %s TEXT",
                self::quoteIdentifier(self::$mainTable),
                self::quoteIdentifier($column)
            );
            self::execute($sql);
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'duplicate column name') === false) {
                throw $e;
            }
            // Игнорируем ошибку дублирования столбцов
        }
    }
}


private static function getTableColumns(string $table): array {
    // Пропускаем валидацию для специальных запросов PRAGMA
    if (strpos($table, 'PRAGMA') === 0) {
        $tableName = trim(substr($table, strlen('PRAGMA table_info(')), ')');
        self::validateTableName($tableName);
    } else {
        self::validateTableName($table);
    }

    $columns = [];
    $result = self::select($table, [], '*');

    foreach ($result as $row) {
        if (isset($row['name'])) {
            $columns[] = $row['name'];
        }
    }

    return $columns;
}


    public static function optimize(): void {
        if (substr(self::$dbFilePath, -8) === '.sqlite') {
            self::execute("VACUUM");
        }
    }

    public static function getMainTableName(): string {
        return self::$mainTable;
    }

    public static function getStorageTableName(): string {
        return self::$storageTable;
    }
}