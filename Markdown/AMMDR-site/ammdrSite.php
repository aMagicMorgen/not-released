<?php
/**
 * aMagic Markdown Reader - Directory Scanner
 * Статический класс для сканирования директорий с .md файлами и создания JSON-индекса
 */
class ammdrSite
{
    // Настройки по умолчанию (как в вашем примере)
    private static $contentDir;
    private static $outputFile;
    private static $excludedDirs = ['.', '..', '.git', 'node_modules'];
    private static $previewLines = 5;

    // Инициализация настроек по умолчанию при первом вызове
    private static function initDefaults()
    {
        if (self::$contentDir === null) {
            self::$contentDir = __DIR__ . '/content';
        }
        if (self::$outputFile === null) {
            self::$outputFile = __DIR__ . '/data/site-index.json';
        }
    }

    /**
     * Установка конфигурации (необязательно, если устраивают defaults)
     * @param array $options Массив с настройками (contentDir, outputFile, excludedDirs, previewLines)
     */
    public static function configure(array $options = [])
    {
        self::initDefaults(); // Инициализируем defaults перед применением настроек

        if (isset($options['contentDir'])) {
            self::$contentDir = $options['contentDir'];
        }
        if (isset($options['outputFile'])) {
            self::$outputFile = $options['outputFile'];
        }
        if (isset($options['excludedDirs'])) {
            self::$excludedDirs = $options['excludedDirs'];
        }
        if (isset($options['previewLines'])) {
            self::$previewLines = (int)$options['previewLines'];
        }
    }

    /**
     * Генерация JSON-индекса
     */
    public static function generate()
    {
        self::initDefaults(); // На случай, если configure() не вызывали

        if (!is_dir(self::$contentDir)) {
            throw new Exception("Директория с контентом не найдена: " . self::$contentDir);
        }

        // Создаём папку для outputFile, если её нет
        $outputDir = dirname(self::$outputFile);
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $structure = [
            'generated' => date('Y-m-d H:i:s'),
            'content' => self::scanDirectory(self::$contentDir, self::$contentDir)
        ];

        file_put_contents(
            self::$outputFile,
            json_encode($structure, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }
/**
     * Recursively scan directory for markdown files
     * 
     * @param string $dir Directory to scan
     * @param string $baseDir Base directory for relative paths
     * @return array Directory structure
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
            $relativePath = str_replace($baseDir . '/', '', $path);
            
            if (is_dir($path)) {
                $structure[$item] = [
                    'type' => 'directory',
                    'path' => $relativePath,
                    'children' => self::scanDirectory($path, $baseDir)
                ];
                
                // Check for README.md in directory
                $readmePath = $path . '/README.md';
                if (file_exists($readmePath)) {
                    $structure[$item]['readme'] = $relativePath . '/README.md';
                    $structure[$item]['preview'] = self::getMarkdownPreview($readmePath);
                }
            } elseif (pathinfo($item, PATHINFO_EXTENSION) === 'md') {
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
     * Get preview from markdown file
     * 
     * @param string $filePath Path to markdown file
     * @return string Preview content
     */
    private static function getMarkdownPreview($filePath)
    {
        if (!file_exists($filePath)) {
            return '';
        }
        
        $content = file($filePath, FILE_IGNORE_NEW_LINES);
        $preview = array_slice($content, 0, self::$previewLines);
        return implode("\n", $preview);
    }
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
