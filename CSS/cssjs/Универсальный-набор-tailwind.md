Вот расширенный вариант с базовыми стилями для основных HTML-тегов. Стили сделаны нейтральными и подходящими для большинства проектов:

```javascript
const elements = {
  // Заголовки
  'h1': 'text-4xl font-bold mb-6',
  'h2': 'text-3xl font-bold mb-5',
  'h3': 'text-2xl font-bold mb-4',
  'h4': 'text-xl font-bold mb-3',
  'h5': 'text-lg font-bold mb-2',
  'h6': 'text-base font-bold mb-2',

  // Основной текст
  'p': 'text-base mb-4 leading-relaxed',
  'blockquote': 'border-l-4 border-gray-300 pl-4 my-4 italic',
  'code': 'font-mono bg-gray-100 p-1 rounded text-sm',
  'pre': 'font-mono bg-gray-100 p-4 rounded-lg overflow-x-auto my-4',

  // Ссылки и интерактивные элементы
  'a': 'text-primary hover:underline transition-colors',
  'button': 'bg-primary text-white px-4 py-2 rounded hover:bg-primary/90 transition',

  // Списки
  'ul': 'list-disc pl-8 mb-4 space-y-2',
  'ol': 'list-decimal pl-8 mb-4 space-y-2',
  'li': 'mb-2',

  // Изображения
  'img': 'rounded-lg max-w-full h-auto',

  // Таблицы
  'table': 'w-full border-collapse my-6',
  'thead': 'bg-gray-50',
  'tbody': 'divide-y divide-gray-200',
  'tr': 'hover:bg-gray-50',
  'th': 'text-left py-3 px-4 font-semibold border-b',
  'td': 'py-3 px-4 border-b',

  // Формы
  'input': 'border rounded px-4 py-2 w-full focus:ring-2 focus:ring-primary',
  'textarea': 'border rounded px-4 py-2 w-full h-32 resize-y focus:ring-2 focus:ring-primary',
  'label': 'block mb-2 font-medium',
  'select': 'border rounded px-4 py-2 w-full bg-white',
  'option': 'py-2',

  // Разметка
  'div': 'mb-4 last:mb-0',
  'span': 'inline-block',
  'hr': 'my-8 border-t border-gray-200',

  // Семантические блоки
  'header': 'bg-white shadow-sm py-4',
  'footer': 'bg-dark text-white py-8',
  'section': 'py-12',
  'article': 'mb-8 p-6 bg-white rounded-lg shadow',
  'nav': 'space-x-4',
  'main': 'min-h-screen',
  'aside': 'bg-gray-50 p-4 rounded-lg',

  // Специальные элементы
  'dialog': 'p-6 rounded-lg shadow-xl',
  'details': 'bg-gray-50 p-4 rounded-lg',
  'summary': 'font-medium cursor-pointer',
};
```

Особенности этого подхода:
1. Универсальные базовые стили для быстрого старта
2. Консистентные отступы и типографика
3. Интерактивные состояния (hover, focus)
4. Адаптивные изображения
5. Семантическое разделение стилей
6. Поддержка современных HTML5-тегов

Рекомендации по использованию:
1. Можно легко кастомизировать через замену значений
2. Дополнять недостающие теги по мере необходимости
3. Для сложных проектов стоит разделить на разные группы стилей
4. Сочетать с глобальными стилями для container/wrapper-элементов
5. Использовать совместно с @layer base в CSS для расширения

Такой подход позволяет получить согласованный базовый дизайн системы для всего HTML-документа.
