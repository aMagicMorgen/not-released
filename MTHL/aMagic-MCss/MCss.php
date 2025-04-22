<?php

/**
 * Класс MCss — загрузчик и парсер кастомного MCSS из CSS-файла или строки.
 *
 * Особенности:
 * - Извлекает блоки MCSS из комментариев вида /*mcss ... mcss*\/
 * - Удаляет все комментарии из MCSS-кода
 * - Парсит MCSS по алгоритму MCssJs (CSS-подобный синтаксис с классами внутри фигурных скобок)
 * - Генерирует JS-скрипт для динамического применения классов
 *
 * Пример использования:
 * $loader = new MCssJsLoader();
 * $loader->loadFromFile('style.css');
 * // или $loader->loadFromString($cssContent);
 * $script = $loader->generateScript();
 */
class MCss
{
    private ?string $rawMcss = null;
    private ?MCssJs $parser = null;

    /**
     * Загружает MCSS из файла .css, извлекая все блоки между /*mcss и mcss*\/
     * @param string $filePath
     * @throws RuntimeException если файл не найден
     */
    public function loadFromFile(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new RuntimeException("File not found: {$filePath}");
        }
        $content = file_get_contents($filePath);
        $this->extractMcssBlocks($content);
    }

    /**
     * Загружает MCSS из строки, извлекая все блоки между /*mcss и mcss*\/
     * @param string $content
     */
    public function loadFromString(string $content): void
    {
        $this->extractMcssBlocks($content);
    }

    /**
     * Извлекает все MCSS-блоки из текста (между /*mcss и mcss*\/) и объединяет их
     * @param string $content
     */
    private function extractMcssBlocks(string $content): void
    {
        $pattern = '#/\*mcss(.*?)mcss\*/#s';
        preg_match_all($pattern, $content, $matches);
        if (!empty($matches[1])) {
            // Объединяем все найденные блоки через перевод строки
            $this->rawMcss = implode("\n", array_map('trim', $matches[1]));
            // Создаём парсер MCssJs с этим кодом
            $this->parser = new MCssJs($this->rawMcss);
        } else {
            $this->rawMcss = '';
            $this->parser = new MCssJs('');
        }
    }

    /**
     * Генерирует JS-скрипт для динамического применения классов
     * @return string
     */
    public function generateScript(): string
    {
        if ($this->parser === null) {
            return '';
        }
        return $this->parser->generateScript();
    }
}

/**
 * Класс MCssJs — парсер MCSS (CSS-подобный синтаксис с классами внутри фигурных скобок)
 * (тот же, что мы делали ранее, с поддержкой тегов, классов, id, атрибутных селекторов)
 */
class MCssJs
{
    private array $stylesMap = [];
    private array $replaceSelectors = [];
    private array $idSelectors = [];
    private array $attrSelectors = [];

    public function __construct(string $styleText)
    {
        $cleaned = $this->removeComments($styleText);
        $this->parseStyleText($cleaned);
    }

    private function removeComments(string $text): string
    {
        return preg_replace('#/\*.*?\*/#s', '', $text);
    }

    private function parseStyleText(string $text): void
    {
        $blocks = preg_split('/\}/', $text);

        foreach ($blocks as $block) {
            $block = trim($block);
            if ($block === '') continue;

            $parts = preg_split('/\{/', $block, 2);
            if (count($parts) < 2) continue;

            $selectorsPart = trim($parts[0]);
            $classesPart = trim($parts[1]);

            $classesPart = preg_replace('/\s+/', ' ', $classesPart);

            $selectors = preg_split('/\s*,\s*/', $selectorsPart);

            foreach ($selectors as $selector) {
                $selector = trim($selector);
                if ($selector === '') continue;

                if (preg_match('/^#([\w\-]+)$/', $selector, $m)) {
                    $id = $m[1];
                    if (!isset($this->idSelectors[$id])) {
                        $this->idSelectors[$id] = [];
                    }
                    $this->idSelectors[$id][] = $classesPart;
                } elseif (preg_match('/^\.[\w\-]+$/', $selector)) {
                    if (!isset($this->replaceSelectors[$selector])) {
                        $this->replaceSelectors[$selector] = '';
                    }
                    if ($this->replaceSelectors[$selector] !== '') {
                        $this->replaceSelectors[$selector] .= ' ';
                    }
                    $this->replaceSelectors[$selector] .= $classesPart;
                } elseif (preg_match('/^\w+$/', $selector)) {
                    if (!isset($this->stylesMap[$selector])) {
                        $this->stylesMap[$selector] = '';
                    }
                    if ($this->stylesMap[$selector] !== '') {
                        $this->stylesMap[$selector] .= ' ';
                    }
                    $this->stylesMap[$selector] .= $classesPart;
                } elseif (preg_match('/^(\w+)?\[(.+)\]$/', $selector, $m)) {
                    $tag = $m[1] ?: '*';
                    $attr = $m[2];
                    $this->attrSelectors[] = [
                        'tag' => $tag,
                        'attr' => $attr,
                        'classes' => $classesPart,
                    ];
                }
            }
        }
    }

    public function generateScript(): string
    {
        $js = "document.addEventListener('DOMContentLoaded', function() {\n";

        $js .= "  const addClasses = {\n";
        foreach ($this->stylesMap as $selector => $classes) {
            $escaped = addslashes($classes);
            $js .= "    '{$selector}': '{$escaped}',\n";
        }
        $js .= "  };\n\n";

        $js .= "  Object.entries(addClasses).forEach(([selector, classes]) => {\n";
        $js .= "    document.querySelectorAll(selector).forEach(el => {\n";
        $js .= "      el.classList.add(...classes.split(' '));\n";
        $js .= "    });\n";
        $js .= "  });\n\n";

        foreach ($this->replaceSelectors as $selector => $classes) {
            $escaped = addslashes($classes);
            $className = substr($selector, 1);
            $js .= "  document.querySelectorAll('{$selector}').forEach(el => {\n";
            $js .= "    if (el.classList.contains('{$className}')) {\n";
            $js .= "      el.classList.remove('{$className}');\n";
            $js .= "      el.classList.add(...'{$escaped}'.split(' '));\n";
            $js .= "    }\n";
            $js .= "  });\n\n";
        }

        foreach ($this->idSelectors as $id => $classesList) {
            $js .= "  {\n";
            $js .= "    const el = document.getElementById('{$id}');\n";
            $js .= "    if (el) {\n";
            foreach ($classesList as $classes) {
                $escapedClasses = json_encode(explode(' ', $classes));
                $js .= "      el.classList.add(...{$escapedClasses});\n";
            }
            $js .= "    }\n";
            $js .= "  }\n\n";
        }

        foreach ($this->attrSelectors as $sel) {
            $tag = $sel['tag'];
            $attr = $sel['attr'];
            $classes = $sel['classes'];
            $escapedClasses = addslashes($classes);
            $cssSelector = $tag . '[' . $attr . ']';

            $js .= "  document.querySelectorAll('{$cssSelector}').forEach(el => {\n";
            $js .= "    el.classList.add(...'{$escapedClasses}'.split(' '));\n";
            $js .= "  });\n\n";
        }

        $js .= "});\n";

        return $js;
    }
}
