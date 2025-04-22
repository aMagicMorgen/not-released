### Адаптация DatabaseManager для PHP 7.4

Для обратной совместимости с PHP 7.4 потребуется внести следующие изменения:

---

#### 1. **Типизация свойств класса**
В PHP 7.4 нельзя использовать типизацию для статических свойств.

**Было (PHP 8.0+):**
```php
private static ?self $instance = null;
private static string $mainTable;
```

**Стало (PHP 7.4):**
```php
private static $instance = null;
private static $mainTable;
private static $storageTable;
private static $dbFilePath;
private static $config = [];
```

---

#### 2. **Объявление типов возвращаемых значений**
Убрать `: void` и другие сложные типы.

**Было:**
```php
public static function init(string $dbFile, string $userFieldsString, array $config = []): void
```

**Стало:**
```php
public static function init(string $dbFile, string $userFieldsString, array $config = [])
```

---

#### 3. **Замена str_contains() (PHP 8.0+)**
**Было:**
```php
if (str_contains($filename, '_v2')) {...}
```

**Стало:**
```php
if (strpos($filename, '_v2') !== false) {...}
```

---

#### 4. **Замена str_ends_with() (PHP 8.0+)**
**Было:**
```php
if (str_ends_with($path, '.sqlite')) {...}
```

**Стало:**
```php
if (substr($path, -8) === '.sqlite') {...}
```

---

#### 5. **Обработка nullable-типов**
**Было:**
```php
public static function getDocument(int $id): ?array
```

**Стало:**
```php
/**
 * @return array|null
 */
public static function getDocument(int $id)
```

---

#### 6. **Замена array_map с стрелочными функциями**
**Было (PHP 7.4+):**
```php
array_map(fn($row) => array_merge(...), $results);
```

**Стало:**
```php
array_map(function($row) {
    return array_merge($row, json_decode($row['json'], true) ?? []);
}, $results);
```

---

#### 7. **Изменение метода getTableNameFromFile()**
Упрощаем регулярное выражение:

**Было:**
```php
$tableName = preg_replace('/[^a-z0-9_]+/i', '', $filename);
```

**Стало:**
```php
$tableName = preg_replace('/[^\w]+/', '', $filename); // \w = [a-zA-Z0-9_]
```

---

### Полный список изменений для PHP 7.4

1. **Убрать**:
   - Типизацию свойств (`private static string $var`)
   - Возвращаемые типы `void`
   - Nullable-типы (`?type`)
   - Функции `str_contains()`, `str_ends_with()`
   - Стрелочные функции в array_map()

2. **Заменить**:
   - `fn() =>` на `function() {}`
   - Современные строковые функции на аналоги для PHP 7.4
   - `?:` на более явные проверки isset()

3. **Добавить**:
   - PHPDoc-аннотации для типов
   - Явные проверки на null

---

### Пример адаптированного метода (PHP 7.4)

**Было (PHP 8.0+):**
```php
private static function getTableNameFromFile(string $dbFile): string {
    $filename = pathinfo($dbFile, PATHINFO_FILENAME);
    $tableName = preg_replace('/[^a-z0-9_]+/i', '', $filename);
    return $tableName ?? 'documents';
}
```

**Стало (PHP 7.4):**
```php
/**
 * @param string $dbFile
 * @return string
 */
private static function getTableNameFromFile($dbFile) {
    $filename = pathinfo($dbFile, PATHINFO_FILENAME);
    $tableName = preg_replace('/[^\w]+/', '', $filename);
    return !empty($tableName) ? $tableName : 'documents';
}
```

---

### Критические места для проверки

1. **Объявления типов**:
   ```php
   // Было
   public static function createDocument(array $data): int
   
   // Стало
   /** @return int */
   public static function createDocument(array $data)
   ```

2. **Обработка JSON**:
   ```php
   // Добавить проверку на JSON_ERROR_NONE
   $data = json_decode($json, true);
   if (json_last_error() !== JSON_ERROR_NONE) {
       throw new RuntimeException("Invalid JSON");
   }
   ```

3. **Инициализация свойств**:
   ```php
   // В конструкторе или init()
   self::$mainTable = '';
   self::$storageTable = '';
   ```

---

### Итоговые рекомендации

1. Для полной совместимости:
   - Используйте PHP 7.4 для разработки
   - Установите `error_reporting(E_ALL)`
   - Проверьте логи на предупреждения

2. Альтернатива:
   ```php
   if (PHP_VERSION_ID < 80000) {
       // Код для PHP 7.4
   } else {
       // Современный код
   }
   ```

3. Тестирование:
   - Протестируйте все CRUD-операции
   - Проверьте обработку ошибок
   - Убедитесь, что транзакции работают корректно

Эти изменения сохранят 100% функциональности класса при работе на PHP 7.4.
