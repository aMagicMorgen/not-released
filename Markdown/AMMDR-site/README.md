# aMagic Markdown Site (ammdrSite)

Вот полный код для создания простого сайта из папок с Markdown файлами:

## 1. ammdr.php (сканер директорий)

```php
<?php
/**
 * aMagic Markdown Reader - Directory Scanner
 * Scans directories for .md files and creates a JSON index
 */

// Configuration
$contentDir = __DIR__ . '/content'; // Directory with your content
$outputFile = __DIR__ . '/ammdr.json'; // Output JSON file
$excludedDirs = ['.', '..', '.git', '.idea']; // Directories to exclude

function scanDirectory($dir, $baseDir, $excludedDirs) {
    $structure = [];
    $items = scandir($dir);
    
    foreach ($items as $item) {
        if (in_array($item, $excludedDirs)) {
            continue;
        }
        
        $path = $dir . '/' . $item;
        $relativePath = str_replace($baseDir . '/', '', $path);
        
        if (is_dir($path)) {
            $structure[$item] = [
                'type' => 'directory',
                'path' => $relativePath,
                'children' => scanDirectory($path, $baseDir, $excludedDirs)
            ];
            
            // Check for README.md in directory
            $readmePath = $path . '/README.md';
            if (file_exists($readmePath)) {
                $structure[$item]['readme'] = $relativePath . '/README.md';
                $structure[$item]['preview'] = getMarkdownPreview($readmePath);
            }
        } elseif (pathinfo($item, PATHINFO_EXTENSION) === 'md') {
            $structure[$item] = [
                'type' => 'file',
                'path' => $relativePath,
                'preview' => getMarkdownPreview($path)
            ];
        }
    }
    
    return $structure;
}

function getMarkdownPreview($filePath, $lines = 10) {
    if (!file_exists($filePath)) {
        return '';
    }
    
    $content = file($filePath, FILE_IGNORE_NEW_LINES);
    $preview = array_slice($content, 0, $lines);
    return implode("\n", $preview);
}

// Scan the content directory
$structure = [
    'generated' => date('Y-m-d H:i:s'),
    'content' => scanDirectory($contentDir, $contentDir, $excludedDirs)
];

// Save to JSON file
file_put_contents($outputFile, json_encode($structure, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

echo "Directory scanned and JSON index created successfully!\n";
?>
```

## 2. index.php (основной сайт)

```php
<?php
/**
 * aMagic Markdown Reader - Main Site
 */

// Load the JSON index
$jsonFile = __DIR__ . '/ammdr.json';
if (!file_exists($jsonFile)) {
    die("Error: ammdr.json not found. Please run ammdr.php first.");
}

$content = json_decode(file_get_contents($jsonFile), true);
$menuItems = $content['content'] ?? [];

// Function to render Markdown (simplified)
function renderMarkdown($text) {
    // Simple Markdown to HTML conversion (for more features use a library like Parsedown)
    $text = htmlspecialchars($text);
    $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
    $text = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $text);
    $text = preg_replace('/\[(.*?)\]\((.*?)\)/', '<a href="$2">$1</a>', $text);
    $text = preg_replace('/\#\s(.*?)\n/', '<h1>$1</h1>', $text);
    $text = preg_replace('/\#\#\s(.*?)\n/', '<h2>$1</h2>', $text);
    $text = preg_replace('/\#\#\#\s(.*?)\n/', '<h3>$1</h3>', $text);
    $text = nl2br($text);
    return $text;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>aMagic Markdown Reader</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            display: flex;
            max-width: 1200px;
            margin: 0 auto;
        }
        .sidebar {
            width: 250px;
            padding: 20px;
            background: #f5f5f5;
            height: 100vh;
            position: sticky;
            top: 0;
            overflow-y: auto;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        .menu-item {
            margin-bottom: 10px;
        }
        .menu-item a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }
        .menu-item a:hover {
            color: #0066cc;
        }
        .directory-content {
            margin-top: 20px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 5px;
        }
        .directory-preview {
            color: #666;
            font-size: 0.9em;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h1>Content</h1>
            <nav>
                <?php foreach ($menuItems as $name => $item): ?>
                    <div class="menu-item">
                        <a href="#<?= htmlspecialchars($name) ?>"><?= htmlspecialchars($name) ?></a>
                    </div>
                <?php endforeach; ?>
            </nav>
        </div>
        
        <div class="content">
            <h1>Welcome to aMagic Markdown Reader</h1>
            <p>Browse your Markdown content using the menu on the left.</p>
            
            <?php foreach ($menuItems as $name => $item): ?>
                <section id="<?= htmlspecialchars($name) ?>">
                    <h2><?= htmlspecialchars($name) ?></h2>
                    
                    <?php if ($item['type'] === 'directory'): ?>
                        <div class="directory-content">
                            <?php if (isset($item['readme'])): ?>
                                <h3>Description</h3>
                                <div class="directory-preview">
                                    <?= renderMarkdown($item['preview'] ?? 'No preview available') ?>
                                </div>
                                <p><a href="<?= htmlspecialchars($item['readme']) ?>">View full README</a></p>
                            <?php endif; ?>
                            
                            <h3>Files in this directory:</h3>
                            <ul>
                                <?php foreach ($item['children'] as $childName => $child): ?>
                                    <?php if ($child['type'] === 'file'): ?>
                                        <li><a href="<?= htmlspecialchars($child['path']) ?>"><?= htmlspecialchars($childName) ?></a></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php else: ?>
                        <div class="file-preview">
                            <?= renderMarkdown($item['preview'] ?? 'No preview available') ?>
                            <p><a href="<?= htmlspecialchars($item['path']) ?>">View full file</a></p>
                        </div>
                    <?php endif; ?>
                </section>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
```

## Инструкция по использованию:

1. Создайте структуру папок:
   ```
   /your-site-root/
   ├── ammdr.php
   ├── index.php
   └── content/
       ├── folder1/
       │   ├── README.md
       │   └── otherfile.md
       └── folder2/
           ├── README.md
           └── document.md
   ```

2. Запустите `ammdr.php` для создания индекса:
   ```bash
   php ammdr.php
   ```

3. После этого будет создан файл `ammdr.json` с структурой вашего контента.

4. Откройте `index.php` в браузере, и вы увидите список всех папок с превью из README.md файлов.

## Дополнительные улучшения:

1. Для более полной поддержки Markdown можно использовать библиотеку Parsedown:
   ```php
   require_once 'Parsedown.php';
   $parsedown = new Parsedown();
   echo $parsedown->text($markdownContent);
   ```

2. Для обработки прямого открытия .md файлов можно добавить .htaccess:
   ```apache
   RewriteEngine On
   RewriteRule ^content/(.*\.md)$ /markdown-viewer.php?file=$1 [L]
   ```

3. Создайте markdown-viewer.php для отображения полного содержимого файлов.

Это базовая реализация, которую можно расширять по мере необходимости!
