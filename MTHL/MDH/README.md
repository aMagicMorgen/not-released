## class MDHParser - Как это работает

### MDH  - Markdown Hybrid Text

**Извлечение блоков**: Парсер сканирует содержимое, находя блоки в формате `<!--ТИП параметры-->содержимое<!--/ТИП-->`, и извлекает их в порядке следования.

**Обработка обычного содержимого**: Любой контент вне этих блоков помечается как "RAW" (сырой) и включается в результат без изменений.

**Обработка блоков**: Каждый блок обрабатывается в соответствии со своим типом:
- Встроенные типы (MDS, MCSS, PUG) обрабатываются стандартными парсерами
- Для других типов блоков можно зарегистрировать пользовательские обработчики
- Блоки неизвестных типов включаются как сырое содержимое

**Сборка результата**: Результаты обработки всех блоков объединяются в исходном порядке для формирования итогового HTML.

## Настройка и расширение

Вы можете расширить функциональность парсера:
1. Добавляя дополнительные встроенные парсеры
2. Регистрируя пользовательские обработчики для конкретных типов блоков
3. Изменяя шаблон извлечения для работы с другими форматами блоков
4. Добавляя этапы предварительной или последующей обработки

Парсер сохраняет исходный порядок всех блоков контента и корректно обрабатывает PHP-код внутри содержимого (поскольку PHP выполняется до работы парсера).

# Документация MDH Parser

## Описание

MDH Parser - это PHP-класс для обработки гибридных Markdown-документов (MDH - Markdown Hybrid), которые могут содержать:
- Обычный HTML/текст
- Блоки Markdown (MDS)
- Блоки MCSS (стили)
- Блоки PUG (шаблоны)
- Любые другие пользовательские блоки

Парсер сохраняет порядок блоков и обрабатывает каждый тип содержимого соответствующим образом.

## Основные методы

### `registerParser(string $blockType, callable $parserCallback)`
Регистрирует пользовательский обработчик для определенного типа блока.

**Параметры:**
- `$blockType` - тип блока (например, 'CUSTOM')
- `$parserCallback` - функция обработки, принимает содержимое блока и параметры

### `parseString(string $content)`
Парсит MDH-контент из строки.

### `parseFile(string $filePath)`
Парсит MDH-контент из файла.

## Встроенные обработчики

- **MDS** - Markdown (использует Parsedown)
- **MCSS** - Стили (оборачивает в `<style>`)
- **PUG** - Шаблоны Pug (заглушка)
- **RAW** - Необработанный HTML/текст

## Примеры использования

### Базовый пример

```php
require 'vendor/autoload.php';
use Parsedown;

$mdh = new MDHParser();

$content = <<<MDH
<!--MDS
# Заголовок Markdown
Это **Markdown** текст
/MDS-->

<div>Обычный HTML</div>

<!--MCSS
body { color: #333; }
/MCSS-->
MDH;

$result = $mdh->parseString($content);
echo $result;
```

### Работа с файлами

```php
$mdh = new MDHParser();
$html = $mdh->parseFile('document.mdh');
file_put_contents('output.html', $html);
```

### Пользовательские обработчики

```php
$mdh = new MDHParser();

// Регистрируем обработчик для кастомного блока
$mdh->registerParser('CUSTOM', function($content, $params) {
    return "<div class='custom-block' data-params='{$params}'>"
         . strtoupper($content)
         . "</div>";
});

$content = <<<MDH
<!--CUSTOM param1 param2
Это будет обработано кастомным обработчиком
/CUSTOM-->
MDH;

echo $mdh->parseString($content);
```

### Комбинированный пример

```php
$mdh = new MDHParser();

// Кастомный обработчик для CSV данных
$mdh->registerParser('CSV', function($content) {
    $lines = explode("\n", trim($content));
    $html = '<table>';
    foreach ($lines as $line) {
        $html .= '<tr><td>' 
               . implode('</td><td>', str_getcsv($line)) 
               . '</td></tr>';
    }
    return $html . '</table>';
});

$content = <<<MDH
<!--MDS
# Отчет
Ниже представлены данные:
/MDS-->

<!--CSV
Имя,Возраст,Город
Анна,25,Москва
Иван,30,Санкт-Петербург
/CSV-->

<script>
// Обычный JavaScript
console.log('MDH rocks!');
</script>
MDH;

file_put_contents('report.html', $mdh->parseString($content));
```

## Формат MDH-документа

Блоки определяются следующим образом:

```
<!--TYPE параметры
содержимое блока
/TYPE-->
```

Где:
- `TYPE` - тип блока (MDS, MCSS, PUG и т.д.)
- `параметры` - необязательные параметры блока
- `содержимое блока` - текст для обработки

Все, что не заключено в такие блоки, считается "сырым" (RAW) содержимым и включается в вывод без изменений.

## Советы по использованию

1. Для обработки Markdown рекомендуется установить библиотеку Parsedown:
   ```
   composer require erusev/parsedown
   ```

2. Для сложных обработчиков выносите логику в отдельные классы.

3. MDH-файлы могут содержать PHP-код, который будет выполнен до обработки:
   ```php
   <?php $date = date('Y-m-d'); ?>
   <!--MDS
   Отчет за <?= $date ?>
   /MDS-->
   ```

4. Используйте пользовательские обработчики для специализированных форматов данных.

# MDH Parser for PHP

Here's a PHP implementation of a parser for your MDH (Markdown Hybrid) format:

```php
class MDHParser {
    private $parsers = [];
    
    /**
     * Register a custom parser for a specific block type
     * @param string $blockType The block type (e.g., 'MDS', 'MCSS', 'PUG')
     * @param callable $parserCallback A function that takes the block content and returns parsed output
     */
    public function registerParser(string $blockType, callable $parserCallback): void {
        $this->parsers[strtoupper($blockType)] = $parserCallback;
    }
    
    /**
     * Parse MDH content from a string
     * @param string $content The MDH content to parse
     * @return string The resulting HTML
     */
    public function parseString(string $content): string {
        $blocks = $this->extractBlocks($content);
        return $this->processBlocks($blocks);
    }
    
    /**
     * Parse MDH content from a file
     * @param string $filePath Path to the MDH file
     * @return string The resulting HTML
     */
    public function parseFile(string $filePath): string {
        $content = file_get_contents($filePath);
        return $this->parseString($content);
    }
    
    /**
     * Extract all blocks from the content
     * @param string $content The MDH content
     * @return array Array of blocks in order of appearance
     */
    private function extractBlocks(string $content): array {
        $pattern = '/<!--\s*([A-Z]+)\s*(.*?)\s*-->(.*?)\s*<!--\s*\/\1\s*-->/is';
        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);
        
        $blocks = [];
        $lastPos = 0;
        
        foreach ($matches as $match) {
            // Add raw content before this block
            $preContent = substr($content, $lastPos, $match[0][1] - $lastPos);
            if (trim($preContent) !== '') {
                $blocks[] = [
                    'type' => 'RAW',
                    'content' => $preContent
                ];
            }
            
            // Add the block
            $blocks[] = [
                'type' => strtoupper($match[1]),
                'content' => $match[3],
                'params' => $match[2]
            ];
            
            $lastPos = $match[0][1] + strlen($match[0][0]);
        }
        
        // Add remaining content after last block
        $postContent = substr($content, $lastPos);
        if (trim($postContent) !== '') {
            $blocks[] = [
                'type' => 'RAW',
                'content' => $postContent
            ];
        }
        
        return $blocks;
    }
    
    /**
     * Process all blocks through their respective parsers
     * @param array $blocks Array of blocks
     * @return string Combined HTML output
     */
    private function processBlocks(array $blocks): string {
        $output = '';
        
        foreach ($blocks as $block) {
            switch ($block['type']) {
                case 'RAW':
                    $output .= $block['content'];
                    break;
                    
                case 'MDS':
                    $output .= $this->parseMarkdown($block['content']);
                    break;
                    
                case 'MCSS':
                    $output .= $this->parseMCSS($block['content']);
                    break;
                    
                case 'PUG':
                    $output .= $this->parsePug($block['content']);
                    break;
                    
                default:
                    if (isset($this->parsers[$block['type']])) {
                        $output .= call_user_func($this->parsers[$block['type']], $block['content'], $block['params']);
                    } else {
                        // Unknown block type - include as raw
                        $output .= $block['content'];
                    }
            }
        }
        
        return $output;
    }
    
    /**
     * Default Markdown parser
     * @param string $content Markdown content
     * @return string HTML
     */
    private function parseMarkdown(string $content): string {
        // Use Parsedown or another Markdown parser
        $parsedown = new Parsedown();
        return $parsedown->text($content);
    }
    
    /**
     * Default MCSS parser (example - would need implementation)
     * @param string $content MCSS content
     * @return string CSS
     */
    private function parseMCSS(string $content): string {
        // Implement your MCSS parsing logic here
        return '<style>' . $content . '</style>';
    }
    
    /**
     * Default PUG parser (example - would need implementation)
     * @param string $content PUG content
     * @return string HTML
     */
    private function parsePug(string $content): string {
        // Implement PUG parsing or use a library
        return $content; // Placeholder
    }
}
```

## Usage Example

```php
// Initialize the parser
$mdh = new MDHParser();

// Parse a string
$html = $mdh->parseString($yourMdhContent);

// Or parse a file
$html = $mdh->parseFile('path/to/your/file.mdh');

// Register custom parsers
$mdh->registerParser('CUSTOM', function($content, $params) {
    return "<div class='custom'>" . htmlspecialchars($content) . "</div>";
});
```

## How It Works

1. **Block Extraction**: The parser scans the content for blocks in the format `<!--TYPE params-->content<!--/TYPE-->` and extracts them in order.

2. **Raw Content Handling**: Any content outside of these blocks is treated as "RAW" and included as-is.

3. **Block Processing**: Each block is processed according to its type:
   - Built-in types (MDS, MCSS, PUG) have default parsers
   - Custom parsers can be registered for other block types
   - Unknown block types are included as raw content

4. **Output Assembly**: The results from all parsers are combined in the original order to produce the final HTML.

## Customization

You can extend this parser by:
1. Adding more built-in parsers
2. Registering custom parsers for specific block types
3. Modifying the extraction pattern to handle different block formats
4. Adding preprocessing/postprocessing steps

The parser maintains the original order of all content blocks and handles PHP code within the content (since PHP is processed before the parser runs).
