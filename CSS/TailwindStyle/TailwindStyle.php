<?php
//РАБОЧИЙ СКРИПТ но идея еще не реализована
/////Для вывода ошибок на экран  ini_set('display_errors','on'); on || of
#print_r(function_exists('mb_internal_encoding')); //проверка 1-подключено, 0 - не подключено
error_reporting(E_ALL);
ini_set('display_errors','on');
mb_internal_encoding('UTF-8');


class TailwindStyle {
    private $input;
    private $outputPath;
    private $classMap = [];

    public function __construct($input) {
        $this->input = $input;
    }

    public function process() {
        $content = $this->loadContent();
        $content = $this->protectCodeBlocks($content);
        $content = $this->replaceClasses($content);
        $content = $this->restoreCodeBlocks($content);
        $content = $this->addMapping($content);
        return $this->saveContent($content);
    }

    private function loadContent() {
        if ($this->isHtmlString()) {
            return $this->input;
        }
        
        if (!file_exists($this->input)) {
            throw new Exception("Файл не найден: {$this->input}");
        }
        
        return file_get_contents($this->input);
    }

    private function isHtmlString() {
        return preg_match('/<\/?[a-z][^>]*>/i', $this->input) 
            || strpos($this->input, "\n") !== false;
    }

    private function protectCodeBlocks($content) {
        return preg_replace_callback(
            '/(<pre[^>]*>)(.*?)(<\/pre>)/is',
            function($matches) {
                $protected = preg_replace_callback(
                    '/(<code[^>]*>)(.*?)(<\/code>)/is',
                    function($codeMatches) {
                        // Кодируем содержимое code в base64
                        $encoded = base64_encode($codeMatches[2]);
                        return $codeMatches[1] . '###CODE_BLOCK_' . $encoded . '###' . $codeMatches[3];
                    },
                    $matches[2]
                );
                return $matches[1] . $protected . $matches[3];
            },
            $content
        );
    }

    private function replaceClasses($content) {
        return preg_replace_callback(
            '/class="([^"]*)"/i',
            function($matches) {
                $classes = $this->normalizeClassString($matches[1]);
                if (!isset($this->classMap[$classes])) {
                    $this->classMap[$classes] = 'tw-' . count($this->classMap);
                }
                return 'class="' . $this->classMap[$classes] . '"';
            },
            $content
        );
    }

    private function normalizeClassString($classString) {
        return implode(' ', array_filter(
            explode(' ', preg_replace('/\s+/', ' ', trim($classString)))
        ));
    }

    private function restoreCodeBlocks($content) {
        return preg_replace_callback(
            '/###CODE_BLOCK_([^#]+)###/',
            function($matches) {
                // Декодируем base64 обратно в исходный код
                return base64_decode($matches[1]);
            },
            $content
        );
    }

    private function addMapping($content) {
        $mapping = [];
        foreach ($this->classMap as $original => $key) {
            $mapping[] = ".".$key . "{". $original. "}";
        }

        $mappingComment = "<!-- mcss\n" . implode("\n", $mapping) . "\n/mcss -->";

        if (preg_match('/<\/body>/i', $content)) {
            return preg_replace('/<\/body>/i', "$mappingComment\n</body>", $content);
        } else {
            return $content . "\n" . $mappingComment;
        }
    }

    private function saveContent($content) {
        $this->outputPath = $this->isHtmlString() 
            ? 'output-' . time() . '-mcss.html'
            : preg_replace('/\.html$/', '-mcss.html', $this->input);
        
        file_put_contents($this->outputPath, $content);
        return $this->outputPath;
    }
}

/**************** Пример использования ****************/

// Вариант 1: Обработка HTML-файла
try {
    $processor = new TailwindStyle('page1.html');
    $resultFile = $processor->process();
    echo "Обработанный файл сохранен как: $resultFile\n";
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
}
// Вариант 2: Обработка HTML-строки
$html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Пример с кодом</title>
</head>
<body>
    <pre class="bg-gray-100 text-gray-800 p-6 rounded-xl overflow-x-auto my-6"><code class="language-html">&lt;!DOCTYPE html&gt;
&lt;meta charset="utf-8"&gt;
&lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
&lt;/code></pre>
</body>
</html>
HTML;

$processor = new TailwindStyle($html);
$resultFile = $processor->process();
echo "Результат сохранен в: $resultFile\n";
