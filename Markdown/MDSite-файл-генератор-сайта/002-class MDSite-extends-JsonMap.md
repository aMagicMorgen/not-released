# class MDSite extends JsonMap НАЧАЛО

# Документация MDSite (Markdown Site Generator)

```PHP
/*
#print_r(function_exists('mb_internal_encoding')); //проверка 1-подключено, 0 - не подключено
error_reporting(E_ALL);
ini_set('display_errors','on');
mb_internal_encoding('UTF-8');
*/
/**
 * MDSite - генератор структуры сайта на основе Markdown-файлов
 * 
 
 

#JsonMap::setExcludedDirs(['temp', 'cache']);



// Получить текущие настройки
#$config = MDSite::getConfig();


* Настройки по умолчанию:
 * - previewLines: количество строк для превью (по умолчанию 10)
 * - excludeDirs: исключаемые директории
 * - filePattern: паттерн для поиска файлов (по умолчанию /\d{2}-/)
// Базовая инициализация
// Изменить паттерн для файлов
#JsonMap::setFilePattern('/\d{2}-/');
 // Добавить исключаемые директории
MDSite::setExcludedDirs(['tests', 'backup']);
//СОЗДАТЬ ВСЕ ПУТИ К ФАЙЛАМ в массив json в файл index.json
#MDSite::indexJson();
 // извлекать Количество строк для превью
MDSite::contentJson(15); // С превью по 15 строк


// Получение данных
$indexJson = MDSite::getIndex()
$contentJson = MDSite::getContent();

// Принудительное обновление
#MDSite::indexJson();
#MDSite::contentJson();
 */

```


## Обзор

Класс `MDSite` (наследник `JsonMap`) предоставляет инструменты для генерации структуры сайта на основе Markdown-файлов. Основные функции:

1. Сканирование директорий и создание индекса файлов (`index.json`)
2. Генерация древовидной структуры с превью контента (`content.json`)
3. Гибкая настройка параметров сканирования

## Основные методы

### `MDSite::indexJson()`
Создает/обновляет файл `index.json` с плоским списком всех найденных файлов.

```php
$files = MDSite::indexJson();
```

### `MDSite::contentJson(int $previewLines = 10)`
Создает файл `content.json` с древовидной структурой и превью контента.

```php
$content = MDSite::contentJson(15); // С превью по 15 строк
```

### `MDSite::getIndex()`
Возвращает содержимое `index.json` (без повторного сканирования).

```php
$index = MDSite::getIndex();
```

### `MDSite::getContent()`
Возвращает содержимое `content.json`.

```php
$content = MDSite::getContent();
```

## Настройки

### Исключаемые директории
```php
MDSite::setExcludedDirs(['temp', 'cache']);
```

### Паттерн для файлов
```php
MDSite::setFilePattern('/\d{2}-/'); // По умолчанию
```

### Получение текущих настроек
```php
$config = MDSite::getConfig();
```

## Формат файлов

### index.json
```json
[
  "content/01-article.md",
  "content/images/02-pic.jpg"
]
```

### content.json
```json
{
  "generated": "2023-05-02T12:00:00+00:00",
  "content": {
    "content": {
      "type": "directory",
      "path": "content",
      "name": "content",
      "children": {
        "01-article.md": {
          "type": "file",
          "path": "content/01-article.md",
          "name": "ARTICLE",
          "preview": "Первые строки содержимого..."
        }
      }
    }
  }
}
```

## Особенности работы

1. **Автоматическая инициализация**:
   - При вызове `contentJson()` автоматически создается `index.json`, если его нет
   - Для не-Markdown файлов в `preview` сохраняется относительный путь

2. **Обработка имен**:
   - Удаляются числовые префиксы (01-, 02-)
   - Заменяются разделители (-, _) на пробелы
   - Для файлов - преобразование в верхний регистр
   - Для директорий - сохранение оригинального регистра

3. **Поиск корневой директории**:
   - Автоматически ищет `index.json` в текущей и родительских директориях
   - Если не найден - использует текущую директорию

## Пример использования

```php
// Настройка
MDSite::setExcludedDirs(['tests', 'backup']);
MDSite::setFilePattern('/\d{2}-/');

// Генерация файлов
MDSite::contentJson(10);

// Получение данных
$content = MDSite::getContent();
$index = MDSite::getIndex();

// Принудительное обновление
MDSite::indexJson();
MDSite::contentJson(15);
```

## Требования

- PHP 7.4+
- Модуль mbstring для корректной работы с UTF-8

## Примечания

1. Для Markdown-файлов в `preview` попадают первые N строк
2. Для других файлов в `preview` сохраняется относительный путь
3. Все пути в JSON-файлах относительные от корневой директории

   ```PHP
class MDSite extends JsonMap {
    /**
     * Получить текущую конфигурацию
     */
    public static function getConfig(): array {
        return [
            'excludedDirs' => self::$excludeDirs,
            'filePattern' => self::$filePattern
        ];
    }
    
    /**
     * Получить содержимое content.json
     * @return array|null Древовидная структура контента или null
     */
    public static function getContent(): ?array {
        $file = self::findRootDir() . '/content.json';
        return self::loadJsonFile($file);
    }
    
    /**
     * Получить содержимое index.json 
     * @return array|null Плоский список файлов или null
     */
    public static function getIndex(): ?array {
        $file = self::findRootDir() . '/index.json';
        return self::loadJsonFile($file);
    }
    
    /**
     * Общий метод для загрузки JSON файлов
     */
    private static function loadJsonFile(string $file): ?array {
        if (!file_exists($file)) {
            return null;
        }
        
        $content = file_get_contents($file);
        if ($content === false) {
            return null;
        }
        
        $data = json_decode($content, true);
        return is_array($data) ? $data : null;
    }
	
	
}



```
