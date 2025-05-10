const MDS = {
  styles: {

    /* === Структурные элементы === */
    'html': 'scroll-behavior-smooth',
    'body': 'min-h-screen bg-gray-50 font-inter antialiased text-gray-900',
    'header': 'bg-white shadow-sm py-4 sticky top-0 z-50',
    'footer': 'bg-gray-800 text-gray-300 py-12 mt-20',
    'main': 'container mx-auto px-4 py-8',
    'section': 'py-16',
    'article': 'bg-white rounded-2xl shadow-md overflow-hidden mb-8',
    'aside': 'bg-gray-100 p-6 rounded-xl border-l-4 border-indigo-500',
    
    /* === Навигация === */
    'nav': 'navbar is-white px-6 py-3 shadow-sm backdrop-blur-sm bg-white/80',
    'nav ul': 'flex flex-wrap gap-4 md:gap-6 items-center',
    'nav li': 'list-none',
    'nav a': 'text-gray-700 hover:text-indigo-600 transition-colors font-medium',
    'menu': 'list-disc pl-6 space-y-2',
    
    /* === Типография === */
    'h1': 'text-4xl md:text-5xl font-extrabold mb-8 bg-gradient-to-r from-indigo-600 to-pink-500 bg-clip-text text-transparent',
    'h2': 'text-3xl font-bold mb-6 text-gray-900 border-b pb-2 border-gray-200',
    'h3': 'text-2xl font-semibold mb-4 text-gray-800',
    'h4': 'text-xl font-medium mb-3 text-gray-700',
    'h5': 'text-lg font-medium mb-2 text-gray-700',
    'h6': 'text-base font-medium mb-2 text-gray-700',
    'p': 'text-gray-700 mb-6 leading-relaxed text-base',
    'blockquote': 'border-l-4 border-indigo-500 pl-6 italic text-gray-600 my-8 py-2',
    'pre': 'bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto',
    'code': 'bg-gray-100 px-2 py-1 rounded text-sm font-mono text-gray-800',
    
    /* === Списки === */
    'ul': 'list-disc pl-6 mb-6 space-y-2',
    'ol': 'list-decimal pl-6 mb-6 space-y-2',
    'li': 'mb-2 pl-2',
    'dl': 'space-y-4 mb-6',
    'dt': 'font-bold text-gray-800',
    'dd': 'pl-4 text-gray-600 mb-2',
    
    /* === Формы === */
    'form': 'space-y-6 mb-8',
    'fieldset': 'border border-gray-300 rounded-lg p-4 mb-6',
    'legend': 'px-2 font-medium text-gray-700',
    'label': 'block text-gray-700 mb-2 font-medium',
    'input': 'input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500',
    'textarea': 'textarea w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 min-h-[120px]',
    'select': 'select w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200',
    'button': 'button is-primary px-6 py-3 rounded-lg font-medium bg-indigo-600 hover:bg-indigo-700 text-white shadow hover:shadow-md transition-all',
    'output': 'inline-block px-3 py-2 bg-gray-100 rounded-lg',
    
    /* === Таблицы === */
    'table': 'table-auto w-full border-collapse rounded-lg overflow-hidden shadow-sm mb-8',
    'thead': 'bg-gray-100',
    'tbody': 'divide-y divide-gray-200',
    'tr': 'hover:bg-gray-50',
    'th': 'px-6 py-3 text-left text-gray-700 font-semibold',
    'td': 'px-6 py-4 text-gray-600',
    'caption': 'text-gray-500 text-sm mt-2',
    
    /* === Медиа === */
    'figure': 'mb-8 rounded-xl overflow-hidden shadow-md',
    'img': 'w-full h-auto object-cover rounded-xl shadow-md',
    'picture': 'block mb-6',
    'video': 'rounded-xl shadow-md w-full mb-6',
    'audio': 'w-full mb-6',
    'iframe': 'rounded-xl shadow-md w-full aspect-video mb-6',
    
    /* === Интерактивные элементы === */
    'dialog': 'bg-white rounded-2xl shadow-xl p-6 border-0',
    'details': 'bg-gray-100 rounded-lg p-4 mb-4',
    'summary': 'font-medium cursor-pointer',
    
    /* === Специальные div-ы === */
    'div.container': 'container mx-auto px-4',
    'div.card': 'bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition-shadow',
    'div.glass': 'backdrop-blur-lg bg-white/30 border border-white/20 rounded-2xl p-6',
    'div.hero': 'py-20 text-center bg-gradient-to-r from-indigo-50 to-pink-50 rounded-3xl mb-12',
    'div.panel': 'bg-gray-100 rounded-xl p-6 border-l-4 border-indigo-500',
    
    /* === Время и данные === */
    'time': 'text-gray-500 text-sm',
    'data': 'font-mono bg-gray-100 px-2 rounded',
    'meter': 'w-full h-2 rounded-full overflow-hidden',
    'progress': 'w-full h-2 rounded-full overflow-hidden',
    
    /* === Семантические элементы === */
    'address': 'not-italic mb-6',
    'mark': 'bg-yellow-200 px-1 rounded',
    'small': 'text-sm text-gray-500',
    'strong': 'font-bold text-gray-800',
    'em': 'italic text-gray-700',
    'cite': 'text-gray-500 italic',
    
    /* === Интерактивные элементы === */
    'a[href^="http"]': 'after:content-["_↗"]',
    'a[download]': 'after:content-["_↓"]',

    /* === Базовые отступы для структурных элементов === */
   /* 'body': 'min-h-screen p-4 md:p-0', // Мобильные отступы
    'header': 'mb-8', // Отступ после хедера
   'section': 'py-12 first:pt-8 last:pb-16', // Вертикальные отступы
    'article': 'mb-10', // Отступ между карточками
    'h1': 'mb-8 mt-12', // Большие отступы для h1
    'h2': 'mb-6 mt-10', 
    'h3': 'mb-4 mt-8',
    'p': 'mb-6', // Отступ между параграфами
    'ul, ol': 'mb-6 pl-6', // Отступы списков
    'li': 'mb-2',
    'form': 'space-y-6', // Вертикальные отступы между элементами формы
    'table': 'mb-8', // Отступ после таблицы*/
    
    /* === Элементы с "умными" отступами === */
    'img': 'my-6', // Отступы вокруг изображений
    'blockquote': 'my-8 mx-4', // Выделенные цитаты
    'hr': 'my-12', // Разделители
    
    /* === Компоненты с комплексными отступами === */
    'div.card': 'p-6 mb-8', // Карточки
    'div.panel': 'p-6 mb-6' // Панели


  },

  // Инициализация системы
  init: function() {
    // Подключаем шрифт Inter
    const fontLink = document.createElement('link');
    fontLink.href = 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap';
    fontLink.rel = 'stylesheet';
    document.head.appendChild(fontLink);
    
    // Добавляем базовые стили
    const style = document.createElement('style');
    style.textContent = `
      :root {
        --mds-primary: #6366f1;
        --mds-accent: #ec4899;
      }
      body.mds-loaded {
        opacity: 1 !important;
        transition: opacity 0.3s ease;
      }
    `;
    document.head.appendChild(style);
  },

  // Применение стилей
  apply: function() {
    this.init();
    
    // Применяем все стили
    Object.entries(this.styles).forEach(([selector, classes]) => {
      try {
        document.querySelectorAll(selector).forEach(element => {
          classes.split(' ').forEach(c => {
            if (!element.classList.contains(c)) {
              element.classList.add(c);
            }
          });
        });
      } catch (e) {
        console.warn(`MDS: Invalid selector '${selector}'`);
      }
    });
    
    this.handleSpecialCases();
    document.body.classList.add('mds-loaded');
  },
  
  // Специальные обработчики
  handleSpecialCases: function() {
    // Кнопки в навигации
    document.querySelectorAll('nav button').forEach(btn => {
      btn.classList.add('is-light', 'ml-4', 'hover:bg-gray-100');
    });
    
    // Внешние ссылки
    document.querySelectorAll('a[href^="http"]:not([href*="'+location.hostname+'"])').forEach(a => {
      a.setAttribute('target', '_blank');
      a.setAttribute('rel', 'noopener noreferrer');
    });
  }
};

// Запуск системы
document.addEventListener('DOMContentLoaded', () => MDS.apply());

// Борьба с FOUC
document.write(`
  <style>
    body { opacity: 0; }
    @supports (backdrop-filter: blur(10px)) {
      nav { backdrop-filter: blur(10px); }
    }
  </style>
`);
