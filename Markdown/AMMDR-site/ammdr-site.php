<?php
/**
 * aMagic Markdown Reader - Directory Scanner
 * Scans directories for .md files and creates a JSON index
 */

// Configuration
$contentDir = __DIR__; // Directory with your content
$outputFile = __DIR__ . '/ammdr-site.json'; // Output JSON file
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

#echo "Все папки просканированы и JSON index создан!\n Перейдите на <a href='index-ammdr-site.php'>САЙТ</a>";
header("Location: index-ammdr-site.php");
