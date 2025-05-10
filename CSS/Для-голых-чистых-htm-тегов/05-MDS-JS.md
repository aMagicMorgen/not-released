Вот полная реализация **MDS (Markdown Style)** — JavaScript-решения для автоматического оформления чистого HTML с комбинацией Bulma и Tailwind:

```javascript
// MARKDOWN STYLE (MDS) - система стилизации чистого HTML
const MDS = {
  // Базовые стили (аналог :root в CSS)
  init: function() {
    // Добавляем шрифт Inter, если не подключен
    const fontLink = document.createElement('link');
    fontLink.href = 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap';
    fontLink.rel = 'stylesheet';
    document.head.appendChild(fontLink);
    
    // Инжектим базовые стили
    const style = document.createElement('style');
    style.textContent = `
      body {
        font-family: 'Inter', sans-serif;
        line-height: 1.6;
        color: #1f2937;
        background-color: #f9fafb;
      }
    `;
    document.head.appendChild(style);
  },

  // Стили для элементов
  styles: {
    // Структура
    'body': 'min-h-screen bg-gray-50',
    'header': 'bg-white shadow-sm py-4',
    'footer': 'bg-gray-800 text-white py-8 mt-12',
    'section': 'py-12',
    'div': 'mb-4',
    
    // Навигация
    'nav': 'navbar is-white px-6 py-3 shadow-sm',
    'nav ul': 'flex gap-6 items-center',
    'nav li': 'list-none',
    'nav a': 'text-gray-700 hover:text-indigo-600 transition-colors',
    
    // Типография
    'h1': 'text-4xl md:text-5xl font-extrabold mb-6 bg-gradient-to-r from-indigo-600 to-pink-500 bg-clip-text text-transparent',
    'h2': 'text-3xl font-bold mb-6 text-gray-900 border-b pb-2 border-gray-200',
    'h3': 'text-2xl font-semibold mb-4 text-gray-800',
    'p': 'text-gray-700 mb-4 leading-relaxed',
    'a': 'text-indigo-600 hover:text-indigo-800 hover:underline',
    
    // Формы
    'input': 'input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500',
    'textarea': 'textarea w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200',
    'select': 'select w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200',
    'button': 'button is-primary px-6 py-3 rounded-lg font-medium bg-indigo-600 hover:bg-indigo-700 text-white shadow hover:shadow-md transition-all',
    'label': 'block text-gray-700 mb-2',
    
    // Карточки
    'article': 'bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow',
    'article header': 'p-6 border-b border-gray-100',
    'article section': 'p-6',
    
    // Списки
    'ul': 'list-disc pl-6 mb-4 space-y-2',
    'ol': 'list-decimal pl-6 mb-4 space-y-2',
    'li': 'mb-1',
    
    // Таблицы
    'table': 'table-auto w-full border-collapse rounded-lg overflow-hidden shadow-sm',
    'th': 'px-6 py-3 bg-gray-100 text-left text-gray-700 font-semibold',
    'td': 'px-6 py-4 border-b border-gray-200',
    
    // Медиа
    'img': 'rounded-lg shadow-md w-full h-auto',
    'figure': 'mb-6',
    'figcaption': 'text-center text-gray-500 mt-2',
    
    // Доп. компоненты
    'blockquote': 'border-l-4 border-indigo-500 pl-4 italic text-gray-600 my-6',
    'hr': 'my-8 border-t border-gray-200',
    'code': 'bg-gray-100 px-2 py-1 rounded text-sm font-mono'
  },

  // Применяем стили
  apply: function() {
    // Инициализация
    this.init();
    
    // Проходим по всем стилям
    Object.entries(this.styles).forEach(([selector, classes]) => {
      document.querySelectorAll(selector).forEach(element => {
        // Добавляем классы, избегая дублирования
        const newClasses = classes.split(' ').filter(c => !element.classList.contains(c));
        if (newClasses.length) {
          element.classList.add(...newClasses);
        }
      });
    });
    
    // Специальные обработчики
    this.handleSpecialCases();
  },
  
  // Специальные случаи
  handleSpecialCases: function() {
    // Кнопки в навигации
    document.querySelectorAll('nav button').forEach(btn => {
      btn.classList.add('is-light', 'ml-4');
    });
    
    // Основная кнопка в hero-секции
    const heroBtn = document.querySelector('section:first-child button');
    if (heroBtn) {
      heroBtn.classList.add('text-lg', 'px-8');
    }
  }
};

// Запускаем после загрузки DOM
document.addEventListener('DOMContentLoaded', () => {
  MDS.apply();
  
  // Показываем контент после применения стилей (борьба с FOUC)
  document.body.style.opacity = '1';
});

// Скрываем контент до загрузки стилей
document.write(`
  <style>
    body { opacity: 0; transition: opacity 0.3s; }
  </style>
`);
```

### Как использовать:
1. Подключите в `<head>` вашего HTML:
```html
<!-- Bulma CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">

<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- MDS System -->
<script src="mds.js"></script>
```

2. Пишите чистый HTML без классов:
```html
<body>
  <nav>
    <ul>
      <li><a href="#">Главная</a></li>
      <li><a href="#">О нас</a></li>
    </ul>
    <button>Войти</button>
  </nav>
  
  <section>
    <h1>Добро пожаловать</h1>
    <p>Этот текст будет автоматически стилизован</p>
    <button>Основное действие</button>
  </section>
</body>
```

### Ключевые особенности:
1. **Полная автостилизация**:
   - Все основные HTML-теги уже прописаны в `MDS.styles`
   - Комбинация Bulma (`.button`, `.navbar`) + Tailwind (`bg-indigo-600`, `shadow-md`)

2. **Гибкость**:
   ```javascript
   // Можно добавить свои стили
   MDS.styles['.my-class'] = 'bg-blue-500 text-white';
   ```

3. **Оптимизация**:
   - Избегание дублирования классов
   - Специальные обработчики для сложных случаев

4. **Борьба с FOUC**:
   - Контент скрыт до полной загрузки стилей

Для расширения просто добавьте новые селекторы в объект `MDS.styles`. Например:
```javascript
MDS.styles['dialog'] = 'bg-white rounded-xl shadow-xl p-6';
```
