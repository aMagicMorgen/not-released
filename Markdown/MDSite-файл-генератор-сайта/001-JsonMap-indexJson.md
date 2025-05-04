# методы indexJson и contentJson
- (
- ?string $scanDir = null, 
- array $excludeDirs = []
- )
> ...мы ищем файлы с чиловым индексом и собираем массив строк просто с путями до файла папка1/01-файл.ххх, папка1/папка2/01-файл.ххх и т.д.
>  Пропускаем пустые папки и файлы без чмслового индекса,
> метод должен быть очень простым без заумных решений,
> затем массив переводим в json и сохраняем в index.json, сканирование начинаем с "./" с текущей директории

> 2. настройку вынесем так, чтобы пользователь мог указать путь к директории с которой начать сканирование, и исключения для папок не сканировать
> 3. сохраняем папку в директории сканирования
> 4. по умолчанию сканирует из текущей директории и пропускаем служебные папки по-твоему усмотрению

# class JsonMap() 01

```php
<?php

class JsonMap {
    private static array $excludeDirs = ['.', '..', '.git', 'node_modules', 'vendor'];
    private static string $rootDir;
    private static string $filePattern = '/\d{2}-/';
    
    /**
     * Создает/обновляет index.json
     * @return array Массив найденных файлов
     */
    public static function indexJson(): array {
        self::$rootDir = self::findRootDir();
        $files = [];
        self::scanDirectory(self::$rootDir . '/', $files);
        
        $files = array_values(array_filter($files));
        sort($files);
        
        $jsonData = json_encode($files, 
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
        
        file_put_contents(self::$rootDir . '/index.json', $jsonData);
        return $files;
    }
    
    /**
     * Создает content.json с древовидной структурой
     * @param int $previewLines Количество строк для превью (default: 10)
     * @return array Сгенерированная структура
     */
	   public static function contentJson(int $previewLines = 10): array {
        self::$rootDir = self::findRootDir();
        $indexFile = self::$rootDir . '/index.json';
        
        // Если index.json не найден - создаем сразу
        if (!file_exists($indexFile)) {
            $files = self::indexJson();
        } else {
            $files = json_decode(file_get_contents($indexFile), true);
            if (!is_array($files)) {
                $files = self::indexJson();
            }
        }

        $result = [
            'generated' => date('c'),
            'content' => []
        ];

        foreach ($files as $filePath) {
            $parts = explode('/', $filePath);
            $current = &$result['content'];
            
            for ($i = 0; $i < count($parts); $i++) {
                $part = $parts[$i];
                $isLast = ($i === count($parts) - 1);
                
                if (!isset($current[$part])) {
                    $current[$part] = [
                        'type' => $isLast ? 'file' : 'directory',
                        'path' => implode('/', array_slice($parts, 0, $i + 1)),
                        'name' => $isLast 
                            ? self::generateFileName($part)
                            : self::generateDirName($part)
                    ];
                    
                    if (!$isLast) {
                        $current[$part]['children'] = [];
                    }
                }
                
                if (!$isLast) {
                    $current = &$current[$part]['children'];
                } else {
                    $fullPath = self::$rootDir . '/' . $filePath;
                    $current[$part]['preview'] = self::getFilePreview($fullPath, $previewLines);
                }
            }
        }

        file_put_contents(
            self::$rootDir . '/content.json',
            json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );
        
        return $result;
    }	 

    private static function buildTree(array &$tree, string $filePath, int $previewLines): void {
        $parts = explode('/', $filePath);
        $current = &$tree;
        
        for ($i = 0; $i < count($parts); $i++) {
            $part = $parts[$i];
            $isLast = ($i === count($parts) - 1);
            
            if (!isset($current[$part])) {
                $current[$part] = [
                    'type' => $isLast ? 'file' : 'directory',
                    'path' => implode('/', array_slice($parts, 0, $i + 1)),
                    'name' => $isLast 
                        ? self::generateFileName($part)
                        : self::generateDirName($part)
                ];
                
                if ($isLast) {
                    $fullPath = self::$rootDir . '/' . $filePath;
                    $current[$part]['preview'] = self::getFilePreview($fullPath, $previewLines);
                } else {
                    $current[$part]['children'] = [];
                }
            }
            
            if (!$isLast) {
                $current = &$current[$part]['children'];
            }
        }
    }
 	
private static function getFilePreview(string $filePath, int $lines): string {
        if (!file_exists($filePath)) {
            return '';
        }

        // Для не-Markdown файлов возвращаем ссылку
        if (pathinfo($filePath, PATHINFO_EXTENSION) !== 'md') {
            $relativePath = substr($filePath, strlen(self::$rootDir) + 1);
            #return "<a href='$relativePath'>" . basename($filePath) . "</a>";
			return $relativePath;
        }

        // Для Markdown файлов - первые N строк
        $content = file_get_contents($filePath);
        $contentLines = explode("\n", $content);
        return implode("\n", array_slice($contentLines, 0, $lines));
    }	
	  
    private static function generateFileName(string $filename): string {
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $name = preg_replace('/^\d+-/', '', $name);
        $name = str_replace(['-', '_'], ' ', $name);
        return mb_strtoupper($name, 'UTF-8');
    }
    
    private static function generateDirName(string $dirname): string {
        return str_replace(['-', '_'], ' ', preg_replace('/^\d+-/', '', $dirname));
    }
    
    private static function scanDirectory(string $dir, array &$result): void {
        $items = scandir($dir);
        if ($items === false) return;
        
        foreach ($items as $item) {
            if (in_array($item, self::$excludeDirs)) continue;
            
            $path = $dir . $item;
            if (is_dir($path)) {
                self::scanDirectory($path . '/', $result);
            } elseif (preg_match(self::$filePattern, $path)) {
                $result[] = substr($path, strlen(self::$rootDir) + 1);
            }
        }
    }
 
 
 private static function findRootDir(): string {
        $searchDirs = [
            getcwd(),
            dirname(__DIR__),
            dirname(__DIR__, 2)
        ];
        
        foreach ($searchDirs as $dir) {
            if (file_exists($dir . '/index.json')) {
                return $dir;
            }
        }
        
        // Не нашли index.json - создаем в текущей директории
        return getcwd();
    }
  
    /**
     * Настройка исключаемых директорий
     */
    public static function setExcludedDirs(array $dirs): void {
        self::$excludeDirs = array_merge(['.', '..'], $dirs);
    }
    
    /**
     * Настройка паттерна для файлов
     */
    public static function setFilePattern(string $pattern): void {
        self::$filePattern = $pattern;
    }
}

```

### Ключевые изменения:

1. **Переименованный метод**:
   - `buildIndex` → `indexJson`

2. **Гибкая настройка сканирования**:
   - Параметр `$scanDir` - начальная директория (по умолчанию текущая `./`)
   - Параметр `$excludeDirs` - дополнительные исключаемые папки

3. **Сохранение индекса**:
   - Файл сохраняется в той же директории, откуда начинается сканирование

4. **Стандартные исключения**:
   - Системные папки (`.`, `..`)
   - Папки зависимостей (`.git`, `node_modules`, `vendor`)

### Примеры использования:

1. **Стандартное использование** (сканирование текущей директории):
```php
$mapper = new JsonMap();
$index = $mapper->indexJson();
```

2. **С указанием директории**:
```php
$index = $mapper->indexJson('/path/to/project');
```

3. **С дополнительными исключениями**:
```php
$index = $mapper->indexJson('./', ['temp', 'cache']);
```

### Особенности реализации:
- Нормализация путей (удаление лишних слэшей)
- Рекурсивное сканирование с фильтрацией
- Сохранение относительных путей (без `./` в начале)
- Гибкая система исключений папок
