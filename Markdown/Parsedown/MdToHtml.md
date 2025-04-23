Вот готовый PHP-класс для конвертации Markdown в HTML с использованием популярной библиотеки Parsedown:

```php
<?php
// Файл: MarkdownConverter.php
require_once 'Parsedown.php'; // Скачать с https://github.com/erusev/parsedown

class MarkdownConverter {
    private $parser;
    
    public function __construct() {
        $this->parser = new Parsedown();
        $this->parser->setSafeMode(true); // Безопасный режим (фильтрация HTML)
    }

    /**
     * Конвертирует Markdown-текст в HTML
     * @param string $markdown Исходный текст Markdown
     * @return string HTML-код
     */
    public function convertText($markdown) {
        return $this->parser->text($markdown);
    }

    /**
     * Конвертирует Markdown-файл в HTML
     * @param string $inputFile Путь к файлу .md
     * @param string|null $outputFile Путь для сохранения .html (если нужен)
     * @return string HTML-код
     */
    public function convertFile($inputFile, $outputFile = null) {
        if (!file_exists($inputFile)) {
            throw new Exception("Input file not found: $inputFile");
        }
        
        $markdown = file_get_contents($inputFile);
        $html = $this->parser->text($markdown);
        
        if ($outputFile) {
            file_put_contents($outputFile, $html);
        }
        
        return $html;
    }
}
?>
```

**Установка:**
1. Скачайте библиотеку Parsedown:  
   ```bash
   composer require erusev/parsedown
   ```
   Или вручную:  
   - Скачайте [Parsedown.php](https://raw.githubusercontent.com/erusev/parsedown/master/Parsedown.php)  
   - Положите в папку с вашим проектом

**Пример использования:**
```php
<?php
// Подключение класса
require_once 'MarkdownConverter.php';

// Создание конвертера
$converter = new MarkdownConverter();

// Пример 1: Конвертация текста
$mdText = "# Заголовок\n**Жирный текст**";
$html = $converter->convertText($mdText);
echo $html;

// Пример 2: Конвертация файла
$html = $converter->convertFile(
    'article.md', 
    'output.html'
);
?>
```

**Особенности класса:**
- Простая интеграция (требуется только Parsedown.php)
- Безопасный режим по умолчанию
- Поддержка всех возможностей Markdown:
  - Заголовки
  - Списки
  - Таблицы
  - Код (в т.ч. подсветка синтаксиса)
  - Цитаты
  - Ссылки
  - Изображения
- Два режима работы: текст ↔ файл
- Автоматическое сохранение в файл (опционально)

**Дополнительные настройки:**
```php
// Отключить безопасный режим (разрешить HTML)
$converter->parser->setSafeMode(false);

// Включить подсветку синтаксиса
$converter->parser->setMarkupEscaped(true);
$converter->parser->setBreaksEnabled(true);
```

Класс можно расширить добавлением:
- Кастомных CSS-стилей
- Обработки изображений
- Генерации оглавления (TOC)
- Поддержки GitHub Flavored Markdown
