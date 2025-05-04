# метод indexJson
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
    protected string $indexFile = 'index.json';
    protected array $excludeDirs = ['.', '..', '.git', 'node_modules', 'vendor'];
    
    public function indexJson(?string $scanDir = null, array $excludeDirs = []): array {
        $scanDir = $scanDir ?? './';
        $this->excludeDirs = array_merge($this->excludeDirs, $excludeDirs);
        
        $files = [];
        $this->scanDirectory(rtrim($scanDir, '/') . '/', $files);
        
        $files = array_filter($files);
        sort($files);
        
        $indexPath = rtrim($scanDir, '/') . '/' . $this->indexFile;
        file_put_contents(
            $indexPath,
            json_encode($files, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
        
        return $files;
    }
    
    protected function scanDirectory(string $dir, array &$result): void {
        $items = scandir($dir);
        
        foreach ($items as $item) {
            if (in_array($item, $this->excludeDirs)) {
                continue;
            }
            
            $path = $dir . $item;
            
            if (is_dir($path)) {
                $this->scanDirectory($path . '/', $result);
            } elseif (preg_match('/\/\d{2}-/', $path)) {
                $result[] = ltrim(str_replace('//', '/', $path), './');
            }
        }
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
