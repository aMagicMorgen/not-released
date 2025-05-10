const MDS = {
  styles: {
    /* === Основные структурные элементы === */
    'html': 'scroll-behavior-smooth',
    'body': 'min-h-screen bg-gray-50 font-sans text-gray-900 antialiased md:p-0', /*p-4*/
    'header': 'bg-white shadow-sm sticky top-0 z-50', /*py-4 px-6 mb-8 */
    'footer': 'bg-gray-800 text-gray-300 py-12 mt-20 px-6',
    'main': 'container mx-auto px-4 py-8 max-w-7xl',
    'section': 'py-16 first:pt-12 last:pb-20',
    'article': 'bg-white rounded-xl shadow-md overflow-hidden mb-10 p-6',
    'aside': 'bg-gray-100 p-6 rounded-lg border-l-4 border-indigo-500 mb-8',

    /* === Навигация === */
  /*  'nav': 'bg-white/80 backdrop-blur-md py-4 px-6 rounded-xl mb-8',
    'nav ul': 'flex flex-wrap gap-6 items-center',
    'nav li': 'list-none',*/ 
    'nav a': 'text-gray-700 hover:text-indigo-600 transition-colors px-3 py-2 rounded-lg hover:bg-gray-100/50',

    'nav': 'navbar is-white is-fixed-top', // Добавляем fixed-top
'nav ul': 'navbar-start',
'nav li': 'navbar-item',
/*'nav a': 'navbar-link',*/
    
    /* === Типография === */
    'h1': 'text-4xl md:text-5xl font-extrabold mb-8 mt-12 bg-gradient-to-r from-indigo-600 to-pink-500 bg-clip-text text-transparent',
    'h2': 'text-3xl font-bold mb-8 mt-10 text-gray-900 border-b pb-2 border-gray-200',
    'h3': 'text-2xl font-semibold mb-6 mt-8 text-gray-800',
    'h4': 'text-xl font-medium mb-4 mt-6 text-gray-700',
    'h5': 'text-lg font-medium mb-3 mt-5 text-gray-700',
    'h6': 'text-base font-medium mb-2 mt-4 text-gray-700',
    'p': 'text-gray-700 mb-6 leading-relaxed text-base',
    'blockquote': 'border-l-4 border-indigo-500 pl-6 italic text-gray-600 my-8 py-2',
    'pre': 'bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto my-8',
    'code': 'bg-gray-100 px-2 py-1 rounded text-sm font-mono text-gray-800',

    /* === Списки === */
    'ul': 'list-disc pl-6 mb-8 space-y-2',
    'ol': 'list-decimal pl-6 mb-8 space-y-2',
    'li': 'mb-2 pl-2',
    'dl': 'space-y-4 mb-8',
    'dt': 'font-bold text-gray-800',
    'dd': 'pl-4 text-gray-600 mb-2',

    /* === Формы === */
    'form': 'space-y-6 mb-10',
    'fieldset': 'border border-gray-300 rounded-lg p-6 mb-8',
    'legend': 'px-3 font-medium text-gray-700 mb-4',
    'label': 'block text-gray-700 mb-2 font-medium',
    'input': 'w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 mb-4',
    'textarea': 'w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 min-h-[120px] mb-4',
    'select': 'w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 mb-4',
    'button': 'px-6 py-3 rounded-lg font-medium bg-indigo-600 hover:bg-indigo-700 text-white shadow hover:shadow-md transition-all',
    'button[type="submit"]': 'w-full bg-green-600 hover:bg-green-700',

    /* === Таблицы === */
    'table': 'w-full border-collapse rounded-lg overflow-hidden shadow-sm mb-10',
    'thead': 'bg-gray-100',
    'tbody': 'divide-y divide-gray-200',
    'tr': 'hover:bg-gray-50',
    'th': 'px-6 py-4 text-left text-gray-700 font-semibold',
    'td': 'px-6 py-4 text-gray-600',
    'caption': 'text-gray-500 text-sm mt-2 text-center',

    /* === Медиа === */
    'figure': 'mb-10 rounded-xl overflow-hidden shadow-md',
    'img': 'w-full h-auto object-cover rounded-xl shadow-md my-6',
    'picture': 'block mb-8',
    'video': 'rounded-xl shadow-md w-full my-8',
    'audio': 'w-full my-8',
    'iframe': 'rounded-xl shadow-md w-full aspect-video my-8',

    /* === Специальные div-классы === */
    'div.div1': 'bg-white rounded-2xl shadow-xl p-8 mb-8 border-t-4 border-indigo-500', // Карточка с акцентной полосой
    'div.div2': 'bg-gradient-to-r from-indigo-50 to-blue-50 rounded-3xl p-10 mb-10 text-center', // Герой-блок
    'div.div3': 'bg-gray-100 rounded-lg p-6 mb-6 border-l-8 border-gray-300', // Панель
    'div.div4': 'grid grid-cols-1 md:grid-cols-2 gap-8 mb-10', // 2-колоночная сетка
    'div.div5': 'bg-black/90 text-white p-6 rounded-xl backdrop-blur-sm', // Темный стеклянный блок
    'div.div6': 'flex flex-col sm:flex-row gap-6 mb-8', // Горизонтальный контейнер
    'div.div7': 'bg-white/80 border border-gray-200 rounded-xl p-6 shadow-inner', // Светлый интерактивный блок

    /* === Прочие элементы === */
    'hr': 'my-12 border-t border-gray-200',
    'mark': 'bg-yellow-200 px-1 rounded',
    'small': 'text-sm text-gray-500 block mb-2',
    'strong': 'font-bold text-gray-800',
    'em': 'italic text-gray-700',
    'cite': 'text-gray-500 italic',
    'time': 'text-gray-500 text-sm',
    'dialog': 'bg-white rounded-2xl shadow-xl p-8 border-0 max-w-md mx-auto'
  },

  init: function() {
    // Подключение шрифта Inter
    const fontLink = document.createElement('link');
    fontLink.href = 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap';
    fontLink.rel = 'stylesheet';
    document.head.appendChild(fontLink);

    // Базовые стили
    const style = document.createElement('style');
    style.textContent = `
      :root { scroll-padding-top: 6rem; }
      body { opacity: 0; transition: opacity 0.3s; }
      body.mds-loaded { opacity: 1; }
      @media (max-width: 768px) {
        html { font-size: 15px; }
      }
    `;
    document.head.appendChild(style);
  },

  apply: function() {
    this.init();
    
    // Применяем стили
    Object.entries(this.styles).forEach(([selector, classes]) => {
      try {
        document.querySelectorAll(selector).forEach(el => {
          classes.split(' ').forEach(c => !el.classList.contains(c) && el.classList.add(c));
        });
      } catch(e) { console.warn(`MDS: Invalid selector "${selector}"`); }
    });

    // Особые обработчики
    this.handleSpecialCases();
    document.body.classList.add('mds-loaded');
  },

  handleSpecialCases: function() {
    // Автоматическое добавление иконки внешним ссылкам
    document.querySelectorAll('a[href^="http"]:not([href*="'+location.hostname+'"])')
      .forEach(a => {
        a.target = '_blank';
        a.rel = 'noopener noreferrer';
        a.innerHTML += ' <span class="text-xs">↗</span>';
      });
  }
};

// Запуск
document.addEventListener('DOMContentLoaded', () => MDS.apply());
