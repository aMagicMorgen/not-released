- [Выйти в папку `main`](../)

# Документация к PHP-скрипту для отображения Markdown-файлов

## 📌 Описание проекта

Этот PHP-скрипт создает простой веб-интерфейс для просмотра Markdown-файлов (*.md) в текущей директории. Основные особенности:

1. Автоматически генерирует меню со списком всех .md файлов
2. Отображает выбранный Markdown-файл с красивым форматированием
3. Удаляет `target="_blank"` у всех ссылок для открытия в текущей вкладке
4. Адаптивный дизайн с приятной типографикой

## 🛠️ Установка и использование

1. Просто поместите этот скрипт (например, с именем `index.php`) в папку с вашими Markdown-файлами
2. Доступные .md файлы автоматически появятся в меню
3. Для просмотра файла просто нажмите на его название в списке

## 🌟 Особенности реализации

### Удаление target="_blank"
```javascript
document.addEventListener('DOMContentLoaded', function() {
    const links = document.querySelectorAll('a[target="_blank"]');
    links.forEach(link => {
        link.removeAttribute('target');
    });
    
    // Наблюдатель для динамически загружаемого контента
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                document.querySelectorAll('a[target="_blank"]').forEach(link => {
                    link.removeAttribute('target');
                });
            }
        });
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
});
```

### Генерация меню
```php
function getMarkdownFiles() {
    $files = [];
    foreach (glob("*.md") as $file) {
        if (is_file($file)) {
            $name = pathinfo($file, PATHINFO_FILENAME);
            $files[$name] = $file;
        }
    }
    return $files;
}
```

### Стилизация
```css
body {
  font: 400 16px/1.5 "Helvetica Neue", Helvetica, Arial, sans-serif;
  color: #111;
  margin: 20px;
  max-width: 800px;
  border: 1px solid #e1e4e8;
  padding: 10px 40px;
  padding-bottom: 20px;
  border-radius: 10px;
  margin-left: auto;
  margin-right: auto;
  box-shadow: 0 10px 15px -3px rgb(0 0 0 / 10%), 0 4px 6px -2px rgb(0 0 0 / 5%);
}
```


## 📝 Дополнительные заметки

1. Скрипт использует библиотеку [md-page.js](https://github.com/oscarmorrison/md-page) для рендеринга Markdown
2. Все ссылки открываются в текущей вкладке (удаляется `target="_blank"`)
3. Дизайн адаптирован под мобильные устройства
4. Для добавления новых документов просто загрузите .md файлы в ту же папку

## ⚠️ Безопасность

Скрипт включает базовые меры безопасности:
- Использует `basename()` для предотвращения Path Traversal
- Проверяет существование файла перед отображением
- Использует `urlencode()` для корректного формирования ссылок

## 🔮 Возможные улучшения

1. Добавить поиск по документам
2. Реализовать сортировку файлов в меню
3. Добавить поддержку вложенных папок
4. Реализовать подсветку синтаксиса для блоков кода
