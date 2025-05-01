# класс `ammdrSite`, где настройки заданы **по умолчанию**, если не переданы в `configure()`:
Ваш код в целом записан правильно, но есть несколько мелких недочётов. Вот исправленная и оптимизированная версия:

```php
<?php
/**
 * aMagic Markdown Reader - Directory Scanner
 * Статический класс для сканирования директорий с .md файлами и создания JSON-индекса
 */
class ammdrSite
{
    // Настройки по умолчанию
    private static $contentDir = null;
    private static $outputFile = null;
    private static $excludedDirs = ['.', '..', '.git', 'node_modules'];
    private static $previewLines = 5;

    // Инициализация настроек по умолчанию
    private static function initDefaults()
    {
        if (self::$contentDir === null) {
            self::$contentDir = __DIR__;
        }
        if (self::$outputFile === null) {
            self::$outputFile = __DIR__ . '/ammdrSite.json';
        }
    }

    /**
     * Установка конфигурации
     * @param array $options Массив с настройками
     */
    public static function configure(array $options = [])
    {
        self::initDefaults();

        if (isset($options['contentDir'])) {
            self::$contentDir = rtrim($options['contentDir'], '/');
        }
        if (isset($options['outputFile'])) {
            self::$outputFile = $options['outputFile'];
        }
        if (isset($options['excludedDirs'])) {
            self::$excludedDirs = array_merge(['.', '..'], $options['excludedDirs']);
        }
        if (isset($options['previewLines'])) {
            self::$previewLines = max(1, (int)$options['previewLines']);
        }
    }

    /**
     * Генерация JSON-индекса
     * @throws Exception Если директория не найдена
     */
    public static function generate()
    {
        self::initDefaults();

        if (!is_dir(self::$contentDir)) {
            throw new Exception("Content directory not found: " . self::$contentDir);
        }

        $outputDir = dirname(self::$outputFile);
        if (!is_dir($outputDir) && !mkdir($outputDir, 0755, true)) {
            throw new Exception("Failed to create output directory: " . $outputDir);
        }

        $structure = [
            'generated' => date('c'),
            'content' => self::scanDirectory(self::$contentDir, self::$contentDir)
        ];

        if (file_put_contents(
            self::$outputFile,
            json_encode($structure, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        ) === false) {
            throw new Exception("Failed to write output file: " . self::$outputFile);
        }
    }

    /**
     * Рекурсивное сканирование директории
     */
    private static function scanDirectory($dir, $baseDir)
    {
        $structure = [];
        $items = scandir($dir);
        
        foreach ($items as $item) {
            if (in_array($item, self::$excludedDirs)) {
                continue;
            }
            
            $path = $dir . '/' . $item;
            $relativePath = ltrim(str_replace($baseDir, '', $path), '/');
            
            if (is_dir($path)) {
                $node = [
                    'type' => 'directory',
                    'path' => $relativePath,
                    'children' => self::scanDirectory($path, $baseDir)
                ];
                
                if (file_exists($path . '/README.md')) {
                    $node['readme'] = $relativePath . '/README.md';
                    $node['preview'] = self::getMarkdownPreview($path . '/README.md');
                }
                
                $structure[$item] = $node;
            } elseif (pathinfo($path, PATHINFO_EXTENSION) === 'md') {
                $structure[$item] = [
                    'type' => 'file',
                    'path' => $relativePath,
                    'preview' => self::getMarkdownPreview($path)
                ];
            }
        }
        
        return $structure;
    }

    /**
     * Получение превью из Markdown-файла
     */
    private static function getMarkdownPreview($filePath)
    {
        if (!file_exists($filePath)) {
            return '';
        }
        
        $content = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $preview = array_slice($content, 0, self::$previewLines);
        return implode("\n", $preview);
    }
}


// Example usage:
// ammdrSite::configure([
//     'contentDir' => '/path/to/content',
//     'outputFile' => '/path/to/output.json',
//     'excludedDirs' => ['.', '..', '.git'],
//     'previewLines' => 5
// ]);
// ammdrSite::generate();
// header("Location: index-ammdrSite.php");
```

Основные улучшения:

1. Более безопасная обработка путей:
   - Добавлен `rtrim()` для `contentDir`
   - Улучшено формирование `relativePath`

2. Лучшая обработка ошибок:
   - Проверка успешности `mkdir()`
   - Проверка успешности `file_put_contents()`
   - Более информативные исключения

3. Улучшения в коде:
   - Использование `date('c')` для ISO-формата даты
   - Добавлен `FILE_SKIP_EMPTY_LINES` при чтении файлов
   - Гарантировано, что `previewLines` будет не менее 1
   - Обязательные точки и точки-точки в `excludedDirs`

4. Оптимизации:
   - Более чистая структура кода в `scanDirectory`
   - Улучшенное формирование относительных путей

Пример использования остаётся прежним:
```php
ammdrSite::generate(); // С настройками по умолчанию

// Или с кастомными настройками:
ammdrSite::configure([
    'contentDir' => '/path/to/content',
    'outputFile' => '/path/to/output.json'
]);
ammdrSite::generate();
```
