# aMagic-MCss
MCss это  php класс который создает js для подстановки классов в теги


последний штрих. Я решил всё-таки писать mcss в файле *.css но в магическом комментарии 
/*mcss
здесь mcss классы
mcss*/
 /*далее пишем как обычный css*/

/*mcss
здесь снова mcss классы
mcss*/

поэтому нужно предусмотреть загрузку контента из файла или из переменной, если из файла css то сразу вырезать куски между /*mcss
и 
mcss*/, а уже потом убирать все комментарии и далее по алгоритму

```php
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
```

---

### Пример использования:

```php
<?php
require_once 'MCss.php';

$loader = new MCss();
$loader->loadFromFile('style.css'); // или loadFromString($cssContent)
$script = $loader->generateScript();
?>

<script>
<?= $script ?>
</script>
```

---

### В `style.css` можно писать так:

```css
/*mcss
h1, h2, h3 {
  font-bold
  text-wrap:balance
}

.title, #header {
  text-lg
  text-primary
}

.btn {
  bg-blue-500
  hover:bg-blue-700
  text-white
  font-bold
  py-2
  px-4
  rounded
}

input[type=submit] {
  btn-submit
  cursor-pointer
}
mcss*/

/* обычный CSS дальше */
body {
  background: white;
}
```

---

Таким образом вы удобно храните MCSS в CSS-файле, отделяя обычные стили от ваших динамических классов, и легко генерируете JS для применения классов на странице.

# Документация MCss Manager

## Оглавление
1. [Введение](#введение)
2. [Класс MCssJsLoader](#класс-mcssjsloader)
   - [Методы](#методы-mcssjsloader)
3. [Класс MCssJs](#класс-mcssjs)
   - [Методы](#методы-mcssjs)
4. [Примеры использования](#примеры-использования)
5. [Особенности и ограничения](#особенности-и-ограничения)

---

## Введение

**MCss Manager** — инструмент для работы с динамическими CSS-классами, позволяющий:
- Хранить MCSS-правила в CSS-файлах внутри магических комментариев
- Генерировать JavaScript для автоматического применения классов
- Поддерживать сложные селекторы (теги, ID, атрибуты)

Формат файла:
```css
/* Обычный CSS */
body { background: white; }

/*mcss
h1 { text-xl font-bold } 
mcss*/

/* снова Обычный CSS */
block { background: white; }



/* Другой MCSS-блок */
/*mcss
.btn { py-2 px-4 }
mcss*/
```

---

## Класс MCss

### Методы MCssJsLoader

#### `loadFromFile(string $filePath): void`
Загружает CSS-файл и извлекает MCSS-блоки.

**Параметры:**
- `$filePath` - путь к CSS-файлу

**Исключения:**
- `RuntimeException` если файл не найден

#### `loadFromString(string $content): void`
Обрабатывает строку с CSS-контентом.

#### `generateScript(): string`
Генерирует JS-код для применения классов.

---

## Класс MCssJs

### Методы MCssJs

#### Конструктор `__construct(string $styleText)`
Инициализирует парсер с очищенным MCSS-кодом.

#### `generateScript(): string`
Создает готовый JavaScript код.

---

## Примеры использования

### 1. Базовое использование
```php
$loader = new MCssJsLoader();
$loader->loadFromFile('styles.css');
$script = $loader->generateScript();

echo "<script>{$script}</script>";
```

### 2. MCSS в строке
```php
$css = "/*mcss .card { border rounded } mcss*/";
$loader->loadFromString($css);
```

### 3. Комбинирование источников
```php
$loader->loadFromFile('base.css');
$loader->loadFromString('/*mcss a { link } mcss*/');
```

---

## Поддерживаемые селекторы

### 1. Теги
```css
div { 
  container 
  mx-auto
}
```

### 2. ID
```css
#header {
  bg-white
  shadow-sm
}
```

### 3. Классы (замена)
```css
.old-class {
  new-class1 new-class2
}
```

### 4. Атрибуты
```css
input[required] {
  border-red
}
```

---

## Генерируемый JavaScript

Пример вывода:
```javascript
document.addEventListener('DOMContentLoaded', function() {
  // Для тегов
  document.querySelectorAll('div').forEach(el => {
    el.classList.add('container', 'mx-auto');
  });

  // Для ID
  const header = document.getElementById('header');
  if (header) header.classList.add('bg-white', 'shadow-sm');

  // Замена классов
  document.querySelectorAll('.old-class').forEach(el => {
    el.classList.remove('old-class');
    el.classList.add('new-class1', 'new-class2');
  });
});
```

---

## Особенности и ограничения

1. **Объединение блоков**: Все MCSS-блоки объединяются в один.
   ```css
   /*mcss h1 { a } mcss*/
   /*mcss h1 { b } mcss*/
   ```
   Результат: `h1 { a b }`

2. **Приоритеты**: 
   - ID селекторы > атрибуты > классы > теги
   - Порядок обработки: как встретились в файле

3. **Ограничения**:
   - Не поддерживаются вложенные комментарии
   - Медиа-запросы внутри MCSS игнорируются
   - Псевдоклассы (`:hover`) обрабатываются как часть имени класса

4. **Безопасность**:
   ```php
   // Все классы экранируются через addslashes()
   $js .= " '{$selector}': '{$escaped}'";
   ```

---

## Рекомендации

1. Используйте отдельные блоки для разных сущностей:
   ```css
   /*mcss
   /* Кнопки */
   .btn { ... }
   mcss*/

   /*mcss
   /* Формы */
   .input { ... }
   mcss*/
   ```

2. Для сложной логики добавляйте комментарии в JS-вывод:
   ```css
   /*mcss
   /* debug: Добавляет рамки */
   * { debug-border }
   mcss*/
   ```

3. Проверяйте консоль браузера на ошибки при применении классов.

---

Теперь вы можете эффективно использовать MCSS-классы, храня их вместе с обычными CSS-правилами, и автоматически генерировать необходимый JavaScript для динамического применения стилей.
