# mds-MarkdownStyle
MarkdownStyle наследует MarkdownExtra вводит новые возможности в md текст предлагаю писать в *.mds - MarkdownStyle или *.mdh - MarkdownHibrid   
https://amagicmorgen.github.io/mds-MarkdownStyle/  

**Автор:** Алексей Нечаев, г. Москва, +7(999) 637-60-01, nechaev72@list.ru   
**Версия:** 1.0 (15.04.2025)  
**Лицензия:** MIT  

---

# **Презентация MarkdownStyle Hybrid**  
# *(MTHL - Markup Text Hybrid Language)*  
https://amagicmorgen.github.io/mthl/  
---
## **🌌 Будущее разметки уже здесь!**  
### **Синтаксис MTHL** — гибрид Markdown, MDS и PHP  

---

### **🔮 1. Возможности MTHL**  
- **Комбинируем** Markdown + MDS + PHP  
- **Пишем** в файлах `.mds` / `.mdh` / `.mthl`  
- **Подключаем** прямо в PHP:  
  ```php
  require_once 'file.mthl';
  ```

---

### **🎨 2. Графическая структура синтаксиса**  

#### **Вариант 1: С цифрами (MDS) после `<` цыфра - это уровень вложенности**  
```html
<1 div #main header .class1 class2 class3 | data-test="value">
  <2 p >Текст <?= $variable ?>
```
**Структура:**  
```
<[уровень -цыфра или пробелы] [тег] #[id] [name] .[классы] | [атрибуты]>
```

#### **Вариант 2: С пробелами (Human-friendly) после `<...` делают теги вкладываевыми**  
```html
<section>
< div #main header .class1 class2 class3 | data-test="value">
<   p >Текст <?= $phpCode ?>
```
**Структура:**  
```
<[пробелы][тег] #[id] [name] .[классы] | [атрибуты]>
```

---

### **✨ 3. Магические комментарии**  
Встраиваем **любые языки** через теги:  
```markdown
<!-- mds 
<1 div .php-block>
  <?= $user->getName() ?>
-->

<!-- phug
div.alert= $message
-->

<!-- styled
Текст | color:<?= $color ?>
-->
```

---

### **🚀 4. PHP + MTHL = ❤️**  
**Пример файла `page.mthl`:**  
```html
<1 div .layout>
  <2 h1 ><?= $title ?>
  <2 p >Привет, <?= $userName ?>!
  
  <!-- mds
  <3 div .features>
    <?php foreach ($features as $item): ?>
      <4 p ><?= $item ?>
    <?php endforeach; ?>
  -->
```

---

### **🌈 5. Почему MTHL — это HTML 6.0?**  
1. **Читаемость** — понятнее, чем HTML.  
2. **Гибкость** — комбинируем стили.  
3. **Расширяемость** — подключаем любые языки.  
4. **PHP-дружественный** — вставки `<?= $var ?>` работают!  

---

### **📊 Сравнение синтаксисов**  
| **HTML** | **MTHL** |  
|----------|----------|  
| `<div class="header">` | `<1 div .header>` или `<div .header>` |  
| `<p id="main">` | `<2 p #main>` или  `< p #main>`| 
| требуют закрытия </...>| закрытие автоматическое|
| Много кода | Минимум символов |  

---

### **🎯 Итог**  
**MTHL** — это:  
- **Единый стандарт** для разметки и шаблонов.  
- **Будущее веба** с поддержкой гибридного синтаксиса.  
- **Альтернатива HTML** для версий 6+.  

---
### **🚀 Попробуйте MTHL уже сегодня!**  
```php
$html = MarkdownStyle::transform(file_get_contents('page.mthl'));
```  

*"Пишите меньше — делайте больше!"*  

---
**Графические элементы:**  
(Вставьте здесь картинки:)
1. ![MTHL vs HTML](https://example.com/mthl-vs-html.png)  
2. ![Синтаксис](https://example.com/syntax-diagram.png)  
3. ![Лого MTHL](https://example.com/mthl-logo.png)  

---

Эта презентация подчеркивает:  
- Простоту MTHL.  
- Гибкость с PHP.  
- Визуальную структуру через графику.  
- Потенциал стать новым стандартом.
# Документация класса `MarkdownStyle`

## Обзор

`MarkdownStyle` - это гибридный процессор разметки, расширяющий `MarkdownExtra` с поддержкой MDS-разметки через специальные комментарии. Позволяет комбинировать обычный Markdown с MDS-разметкой в одном документе.

## Синтаксис файлов

### Вариант 1: Файлы `.mds` (MarkdownStyle)
```
# Обычный Markdown

<!-- mds
<1 div .container>
<2 p >Это MDS-разметка
-->
```

### Вариант 2: Файлы `.mdh` (Markdown Hybrid)
```
<!-- mds
<1 div .header>
<2 h1 >Гибридный документ
-->

Основной *Markdown* контент

<!-- mds
<1 div .footer>
<2 p >Футер в MDS
-->
```

## Подключение и использование

```php
require_once 'MarkdownStyle.php';

// Преобразование текста
$html = MarkdownStyle::transform($markdownText);

// Или через экземпляр
$parser = new MarkdownStyle();
$html = $parser->parse($markdownText);
```

## Наследование от MarkdownExtra

`MarkdownStyle` наследует все возможности `MarkdownExtra`:
- Таблицы
- Сноски
- Определения
- Атрибуты HTML

## Магические комментарии

### Основной синтаксис MDS:
```markdown
<!-- mds
<1 div .panel>
<2 h2 >Заголовок
<2 p >Текст панели
-->
```

### Расширяемость системы (пример с Pug):
```markdown
<!-- phug
div.panel
  h2 Заголовок
  p Текст панели
-->
```

### Кастомные стили:
```markdown
<!-- styled
Этот текст будет стилизован | color:red;font-weight:bold
-->
```

## Полный пример класса

```php
class MarkdownStyle extends \Michelf\MarkdownExtra {
    protected $block_gamut = [
        'parseMdsBlocks' => 5,
        'doHeaders' => 10,
        // ... остальные обработчики
    ];

    protected function parseMdsBlocks(string $text): string {
        return preg_replace_callback_array([
            '/<!--\s*mds\s*(.*?)\s*-->/s' => fn($m) => MDS::toHtml($m[1]),
            '/<!--\s*phug\s*(.*?)\s*-->/s' => fn($m) => Pug::compile($m[1]),
            '/<!--\s*styled\s*(.*?)\s*-->/s' => fn($m) => $this->parseStyled($m[1])
        ], $text);
    }
    
    protected function parseStyled(string $text): string {
        [$content, $style] = explode('|', $text);
        return '<span style="'.htmlspecialchars($style).'">'.trim($content).'</span>';
    }
}
```

## Рекомендации по использованию

1. **Именование файлов**:
   - `.mds` - для документов с преимущественно MDS-разметкой
   - `.mdh` - для гибридных документов

2. **Поддержка IDE**:
   - Создайте сниппеты для быстрой вставки MDS-блоков
   - Настройте подсветку синтаксиса для магических комментариев

3. **Оптимизация**:
   - Кэшируйте результат преобразования
   - Используйте препроцессинг для сложных документов

## Пример полного документа

```markdown
<!-- mds
<1 div .article>
<2 h1 >Гибридный документ
-->

Это *обычный* Markdown текст.

<!-- phug
div.alert.alert-warning
  | Предупреждение
-->

<!-- styled
Важный текст | color:red;font-size:1.2em
-->
```

Такой подход позволяет:
- Сочетать преимущества Markdown для текста
- Использовать MDS для сложных структур
- Встраивать другие шаблонизаторы через комментарии
- Добавлять кастомные стили к фрагментам

  
