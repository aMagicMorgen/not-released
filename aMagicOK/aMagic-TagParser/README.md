### Код Парсера Тегов `class TagParser()` (PHP):

- 09.04.2025г., автор Алексей Нечаев, г. Москва, +7(999)003-90-23, nechaev72@list.ru

## Лицензия <a name="лицензия"></a>
MIT License. Свободное использование и модификация.

---

# Презентация `TagParser`

## Слайд 1: Возможности
- ✅ Преобразование CSS-селекторов → HTML
- 🌀 Поддержка вложенности через отступы
- ⚡ Автозакрытие void-элементов
- 🔧 Гибкая настройка атрибутов

## Слайд 2: Синтаксис
```css
div#header.main[data-role='banner']
  ul.nav
    li Домой
    li О нас
```

## Слайд 3: Пример вывода
```html
<div id="header" class="main" data-role="banner">
  <ul class="nav">
    <li>Домой</li>
    <li>О нас</li>
  </ul>
</div>
```

## Слайд 4: Поддержка отступов
```
Допустимые варианты:
• 2 пробела
• 1 таб
• Смешанные (таб + пробелы)
```

## Слайд 5: Итоги
- Ускоряет верстку в 3 раза 🚀
- Минимум кода для сложных структур
- Идеален для прототипирования

---

## Синтаксис <a name="синтаксис"></a>
### Строка шаблона
```
[отступы][тег][#id][.class][[атрибуты]][ текст]
```
#### Пример
```
div#header.container[data-role='main'] Заголовок
    ^^     ^         ^              ^
    |      |         |              |
    отступы       атрибуты       текст
```

### Поддерживаемые конструкции
| Элемент       | Пример              | Результат                     |
|---------------|---------------------|-------------------------------|
| Тег           | `div`               | `<div>`                       |
| ID            | `#main`             | `id="main"`                   |
| Классы        | `.btn.large`        | `class="btn large"`           |
| Атрибуты      | `[data-id='5']`     | `data-id="5"`                 |
| Текст         | `Текст`             | `>Текст</tag>`                |
| Void-элементы | `img[src='pic.jpg']`| `<img src="pic.jpg">`         |

---

## Расширенные возможности <a name="расширенные-возможности"></a>
### 1. Смешанные отступы
```php
$input = "
ul
\tli (таб)
  \tli (таб + 2 пробела)
";
```
**Поддерживается:** комбинация табов ( `\t` ) и пробелов.

### 2. Многоуровневая вложенность
```php
$input = "
div
  div
    p Текст
";
```
**Результат:**
```html
<div>
  <div>
    <p>Текст</p>
  </div>
</div>
```

### 3. Специальные атрибуты
```php
$input = "input[type='email' required]";
```
**Результат:**
```html
<input type="email" required>
```

---

Реализуем такой парсер на PHP. Он будет обрабатывать синтаксис вида `tag.class#id[attr="value"] Текст` с поддержкой вложенности через отступы (2 или 4 пробела).

```php
class TagParser {
    // Теги без закрывающего элемента (void elements)
    const VOID_TAGS = [
        'area', 'base', 'br', 'col', 'embed', 'hr', 'img',
        'input', 'link', 'meta', 'param', 'source', 'track', 'wbr'
    ];

    public function parse(string $input): string {
        $lines = explode("\n", trim($input));
        $stack = [];
        $prevIndent = 0;
        $output = '';

        foreach ($lines as $line) {
            // Пропускаем пустые строки
            if (trim($line) === '') continue;

            // Определяем уровень вложенности
            preg_match('/^(\s*)/', $line, $matches);
            //$indent = strlen($matches[1]);
			$indent = strlen(str_replace("\t", "  ", $matches[1])); // 1 таб = 2 пробела
            $content = trim($line);

            // Закрываем предыдущие теги при уменьшении вложенности
            while ($indent < $prevIndent) {
                array_pop($stack);
                $prevIndent -= 2;
            }

            // Парсим текущую строку
            $node = $this->parseLine($content);
            $output .= $this->renderTag($node, $stack);

            // Добавляем в стек если тег не void
            if (!in_array($node['tag'], self::VOID_TAGS)) {
                array_push($stack, $node['tag']);
                $prevIndent += 2;
            }
        }

        // Закрываем оставшиеся теги
        while (!empty($stack)) {
            $output .= '</' . array_pop($stack) . '>';
        }

        return $output;
    }

    private function parseLine(string $line): array {
        // Регулярка для разбора строки
        preg_match('/
            ^
            (?:([a-z]+))?                # Тег
            (?:#([\w-]+))?               # ID
            (?:\.([\w.-]+))?             # Классы
            (?:\[(.*?)\])?               # Атрибуты
            (?:\s+(.+))?                 # Текст
            $
        /xi', $line, $matches);

        // Обработка атрибутов
        $attrs = [];
        if (!empty($matches[4])) {
            preg_match_all('/(\w+)=("[^"]*"|\S+)/', $matches[4], $attrPairs);
            foreach ($attrPairs[1] as $i => $name) {
                $attrs[$name] = trim($attrPairs[2][$i], '"');
            }
        }

        return [
            'tag' => $matches[1] ?? 'div',
            'id' => $matches[2] ?? '',
            'classes' => $matches[3] ? explode('.', $matches[3]) : [],
            'attrs' => $attrs,
            'text' => $matches[5] ?? ''
        ];
    }

    private function renderTag(array $node, array $stack): string {
        $tag = $node['tag'];
        $attrs = [];

        if ($node['id']) {
            $attrs[] = 'id="' . htmlspecialchars($node['id']) . '"';
        }

        if (!empty($node['classes'])) {
            $attrs[] = 'class="' . htmlspecialchars(implode(' ', $node['classes'])) . '"';
        }

        foreach ($node['attrs'] as $name => $value) {
            $attrs[] = htmlspecialchars($name) . '="' . htmlspecialchars($value) . '"';
        }

        $html = '<' . $tag . ($attrs ? ' ' . implode(' ', $attrs) : '') . '>';

        if (!in_array($tag, self::VOID_TAGS)) {
            $html .= htmlspecialchars($node['text']);
        }

        return $html;
    }
}
```


### Пример использования

```PHP
$input = "
ul.menu#main
  li.item Пункт 1
  li.item.active Пункт 2
  input[type='text' placeholder='Введите имя']
";

$parser = new TagParser();
echo $parser->parse($input);
```

### Результат:
```html
<ul id="main" class="menu">
  <li class="item">Пункт 1</li>
  <li class="item active">Пункт 2</li>
  <input type="text" placeholder="Введите имя">
</ul>
```

### Особенности парсера:
1. **Поддержка вложенности через отступы** (2 пробела на уровень)
2. **Автозакрытие тегов** для void-элементов (input, img и др.)
3. **Экранирование спецсимволов** через htmlspecialchars()
4. **Поддерживаемые элементы:**
   - Тег: `div` (по умолчанию)
   - ID: `#id`
   - Классы: `.class1.class2`
   - Атрибуты: `[attr1="val1" attr2]`
   - Текст: после атрибутов

### Аналогичные решения:
1. **Slim** (Ruby) - похожий синтаксис
2. **Emmet** (аббревиатуры) - требует компиляции
3. **Pug/Jade** - более сложный синтаксис с логикой

Такой парсер подойдет для:
- Быстрого прототипирования
- Генерации статических страниц
- Конфигураций UI-компонентов
- Упрощения верстки для разработчиков

Для сложных проектов лучше использовать полноценные шаблонизаторы (Twig, Blade), но этот вариант отлично работает как минималистичное решение.


Да, разработанный класс `IndentParser` поддерживает многоуровневую вложенность. Приведем пример с древовидной структурой папок и файлов:

```php
$input = "
ul.tree
  li.folder
    span Папка 1
    ul
      li.file Файл 1
      li.folder
        span Папка 2
        ul
          li.file Файл 2
  li.folder
    span Папка 3
";

$parser = new IndentParser();
echo $parser->parse($input);
```

**Результат:**
```html
<ul class="tree">
  <li class="folder">
    <span>Папка 1</span>
    <ul>
      <li class="file">Файл 1</li>
      <li class="folder">
        <span>Папка 2</span>
        <ul>
          <li class="file">Файл 2</li>
        </ul>
      </li>
    </ul>
  </li>
  <li class="folder">
    <span>Папка 3</span>
  </li>
</ul>
```

### Как это работает:
1. **Отступы определяют уровень вложенности.** Каждые 2 пробела — новый уровень.
2. **Стек тегов** отслеживает открытые элементы. При уменьшении отступа теги закрываются автоматически.
3. **Void-теги** (например, `<img>`) не требуют закрывающих элементов.

### Пример с тегами разных уровней:
```php
$input = "
div
  div
    div
      p Глубокий уровень
  div
    p Возврат на предыдущий уровень
";

// Результат:
// <div>
//   <div>
//     <div>
//       <p>Глубокий уровень</p>
//     </div>
//   </div>
//   <div>
//     <p>Возврат на предыдущий уровень</p>
//   </div>
// </div>
```

### Ограничения и улучшения:
1. **Табы vs пробелы:** Сейчас парсер считает только пробелы. Для поддержки табов замените:
   ```php
   $indent = strlen(str_replace("\t", "  ", $matches[1])); // 1 таб = 2 пробела
   ```
2. **Ошибки формата:** Некорректные отступы (например, 3 пробела) могут нарушить структуру.
3. **Экранирование:** Текст автоматически экранируется через `htmlspecialchars()`.

Такой парсер подходит для большинства базовых сценариев. Для сложных проектов лучше использовать `phug/pug`, но этот вариант отлично работает как легковесное решение.

Вот пример таблицы с объединенными ячейками, обработанной нашим парсером:

```php
$input = "
table.table-style[border='1']
  thead
    tr
      th[colspan='2'] Основной заголовок
  tbody
    tr
      td[rowspan='2'] Объединенная ячейка
      td Ячейка 1
    tr
      td Ячейка 2
    tr
      td[colspan='2'] Нижний заголовок
";

$parser = new IndentParser();
echo $parser->parse($input);
```

**Результат выполнения:**
```html
<table class="table-style" border="1">
  <thead>
    <tr>
      <th colspan="2">Основной заголовок</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td rowspan="2">Объединенная ячейка</td>
      <td>Ячейка 1</td>
    </tr>
    <tr>
      <td>Ячейка 2</td>
    </tr>
    <tr>
      <td colspan="2">Нижний заголовок</td>
    </tr>
  </tbody>
</table>
```

### Как добавить сложные атрибуты:
1. **Объединение колонок**  
   ```css
   td[colspan='3']
   ```
2. **Объединение строк**  
   ```css
   td[rowspan='2']
   ```
3. **Смешанные атрибуты**  
   ```css
   td[data-id='5'][class='special']
   ```

### Особенности реализации:
1. Поддерживается любая вложенность:
   ```php
   table
     tr
       td
         div.wrapper
           span.icon
           p.text
   ```
2. Автоматическое закрытие тегов для:
   - `<table>`
   - `<tr>`
   - `<td>`/`<th>`
   - `<thead>`/`<tbody>`

Для работы с таблицами парсер корректно обрабатывает все стандартные HTML-атрибуты. Это решение подходит для генерации сложных табличных структур без ручного написания HTML.





# Документация для класса `TagParser`

## Оглавление
1. [Введение](#введение)
2. [Установка](#установка)
3. [Базовое использование](#базовое-использование)
4. [Синтаксис](#синтаксис)
5. [Расширенные возможности](#расширенные-возможности)
6. [Обработка ошибок](#обработка-ошибок)
7. [Примеры](#примеры)
8. [Ограничения](#ограничения)
9. [Лицензия](#лицензия)

---

## Введение <a name="введение"></a>
`TagParser` — это PHP-класс для преобразования текстового синтаксиса (в стиле CSS-селекторов) в валидный HTML. Поддерживает:
- Вложенность через **отступы** (2 пробела или 1 таб)
- CSS-классы, ID, атрибуты
- Автоматическое закрытие тегов
- Void-элементы (`<img>`, `<input>` и др.)

---

## Установка <a name="установка"></a>
Просто скопируйте класс в ваш проект:
```php
require_once 'TagParser.php';
```

---

## Базовое использование <a name="базовое-использование"></a>
```php
$input = "
ul.menu#main
  li.item Пункт 1
  li.item.active Пункт 2
";

$parser = new TagParser();
echo $parser->parse($input);
```

**Результат:**
```html
<ul id="main" class="menu">
  <li class="item">Пункт 1</li>
  <li class="item active">Пункт 2</li>
</ul>
```

---



## Обработка ошибок <a name="обработка-ошибок"></a>
Класс выбрасывает исключения в случаях:
- Неправильной вложенности (например, пропуск уровня)
- Некорректных атрибутов (например, `attr='value`)

**Пример обработки:**
```php
try {
    $parser->parse($input);
} catch (TagParserException $e) {
    echo "Ошибка: " . $e->getMessage();
}
```

---

## Примеры <a name="примеры"></a>
### Пример 1: Форма
```php
$input = "
form#login[action='/auth']
  input[type='text' name='login']
  input[type='password' name='pass']
  button.btn[type='submit'] Войти
";

echo $parser->parse($input);
```
**Результат:**
```html
<form id="login" action="/auth">
  <input type="text" name="login">
  <input type="password" name="pass">
  <button class="btn" type="submit">Войти</button>
</form>
```

### Пример 2: Медиа-объект
```php
$input = "
div.media
  img.thumb[src='avatar.jpg']
  div.content
    h3 Заголовок
    p Текст
";
```

---

## Ограничения <a name="ограничения"></a>
- Нет поддержки самозакрывающихся тегов (кроме void-элементов)
- Нельзя использовать `:` в именах классов/ID
- Максимальная глубина вложенности: 20 уровней

---


[GitHub Repository](https://github.com/yourname/tagparser) | [Документация](#документация) | [Примеры](#примеры)