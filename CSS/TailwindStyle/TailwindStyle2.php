<?php

class TailwindStyle {
    private $input;
    private $dom;
    private $originalToNormalized = [];
    private $normalizedToKey = [];
    private $outputPath;

    public function __construct($input) {
        $this->input = $input;
        $this->dom = new DOMDocument('1.0', 'UTF-8');
        $this->dom->preserveWhiteSpace = false;
        $this->dom->formatOutput = true;
    }

    public function process() {
        $this->loadHtml();
        $this->normalizeClasses();
        $this->generateUniqueKeys();
        $this->replaceWithKeys();
        $this->addMappingComment();
        $this->saveHtml();
    }

    private function isHtmlString() {
        return preg_match('/<\/?[a-z][^>]*>/i', $this->input) 
            || strpos($this->input, "\n") !== false;
    }

    private function loadHtml() {
        if ($this->isHtmlString()) {
            $this->loadFromString();
        } else {
            $this->loadFromFile();
        }
    }

    private function loadFromString() {
        @$this->dom->loadHTML($this->input, LIBXML_NOERROR | LIBXML_NOWARNING);
        $this->outputPath = 'output-' . time() . '-mcss.html';
    }

    private function loadFromFile() {
        if (!file_exists($this->input)) {
            throw new Exception("Файл не найден: {$this->input}");
        }
        @$this->dom->loadHTMLFile($this->input, LIBXML_NOERROR | LIBXML_NOWARNING);
        $this->outputPath = preg_replace('/\.html$/', '-mcss.html', $this->input);
    }

    private function normalizeClasses() {
        $xpath = new DOMXPath($this->dom);
        $elements = $xpath->query('//*[@class]');

        foreach ($elements as $element) {
            $original = $element->getAttribute('class');
            $normalized = $this->normalizeClassString($original);
            
            $this->originalToNormalized[$original] = $normalized;
            $element->setAttribute('class', $normalized);
        }
    }

    private function normalizeClassString($classString) {
        return implode(' ', array_filter(
            explode(' ', preg_replace('/\s+/', ' ', trim($classString)))
        );
    }

    private function generateUniqueKeys() {
        $uniqueClasses = array_unique(array_values($this->originalToNormalized));
        foreach ($uniqueClasses as $index => $classStr) {
            $this->normalizedToKey[$classStr] = "tw-" . $index;
        }
    }

    private function replaceWithKeys() {
        $xpath = new DOMXPath($this->dom);
        $elements = $xpath->query('//*[@class]');

        foreach ($elements as $element) {
            $currentClass = $element->getAttribute('class');
            if (isset($this->normalizedToKey[$currentClass])) {
                $element->setAttribute('class', $this->normalizedToKey[$currentClass]);
            }
        }
    }

    private function addMappingComment() {
        $mapping = [];
        foreach ($this->normalizedToKey as $classStr => $key) {
            $mapping[] = "{$key} {{$classStr}}";
        }

        $comment = $this->dom->createComment(
            "mcss\n" . implode("\n", $mapping) . "\n/mcss"
        );

        if ($body = $this->dom->getElementsByTagName('body')->item(0)) {
            $body->appendChild($comment);
        } else {
            $this->dom->appendChild($comment);
        }
    }

    private function saveHtml() {
        $htmlContent = $this->dom->saveHTML();
        file_put_contents($this->outputPath, $htmlContent);
    }
}

/**************** Примеры использования ****************/

// Вариант 1: Обработка HTML-файла
try {
    $processor = new TailwindStyle('source.html');
    $processor->process();
    echo "Обработанный файл сохранен как: source-mcss.html\n";
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
}

// Вариант 2: Обработка HTML-строки
$html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Пример</title>
</head>
<body>
    <div class="p-4 text-blue-500">Контейнер 1</div>
    <div class="text-blue-500  p-4  hover:bg-red-200">Контейнер 2</div>
</body>
</html>
HTML;

$processor = new TailwindStyle($html);
$processor->process();
echo "Результат сохранен в: output-1234567890-mcss.html\n";
