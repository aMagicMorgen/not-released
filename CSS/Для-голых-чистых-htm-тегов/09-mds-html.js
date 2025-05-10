/**
 * MDS (Modern Design System) v2.0
 * Гибридный стилизатор HTML: Bulma + Tailwind
 */
const MDS = {
  // Конфигурация CDN-ресурсов
  resources: {
    bulmaCSS: 'https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css',
    fontAwesome: 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
    googleFonts: 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap',
    tailwindScript: 'https://cdn.tailwindcss.com'
  },

  // Комбинированные стили Bulma + Tailwind
  styles: {
    /* === Базовые элементы === */
    'html': 'scroll-behavior-smooth',
    'body': 'min-h-screen bg-gray-50 font-sans text-gray-900 antialiased p-4 md:p-0',
    /* === Основные структурные элементы === */
   /* 'html': 'scroll-behavior-smooth',
   /* 'body': 'min-h-screen bg-gray-50 font-sans text-gray-900 antialiased md:p-0', /*p-4*/
    'header': 'bg-white shadow-sm sticky top-0 z-50', /*py-4 px-6 mb-8 */
    'footer': 'bg-gray-800 text-gray-300 py-12 mt-20 px-6',
    'main': 'container mx-auto px-4 py-8 max-w-7xl',
    'section': 'py-16 first:pt-12 last:pb-20',
    'article': 'bg-white rounded-xl shadow-md overflow-hidden mb-10 p-6',
    'aside': 'bg-gray-100 p-6 rounded-lg border-l-4 border-indigo-500 mb-8',

    /* === Навигация === */
  /*  'nav': 'bg-white/80 backdrop-blur-md py-4 px-6 rounded-xl mb-8',
    'nav ul': 'flex flex-wrap gap-6 items-center',
    'nav li': 'list-none',
    'nav a': 'text-gray-700 hover:text-indigo-600 transition-colors px-3 py-2 rounded-lg hover:bg-gray-100/50',*/
/* === Навигация (Bulma) === */
    'nav': 'navbar is-white is-fixed-top',
    'nav ul': 'navbar-menu',
    'nav li': 'navbar-item',
    'nav a': 'navbar-link',
    
    /* === Типография (Tailwind) === */
    'h1': 'text-4xl md:text-5xl font-bold mb-8 mt-12 text-gray-900',
    'h2': 'text-3xl font-semibold mb-8 mt-10 text-gray-800',
    'h3': 'text-2xl font-semibold mb-6 mt-8 text-gray-700',
    'p': 'text-gray-700 mb-6 leading-relaxed',
    
    /* === Типография === */
  /*  'h1': 'text-4xl md:text-5xl font-extrabold mb-8 mt-12 bg-gradient-to-r from-indigo-600 to-pink-500 bg-clip-text text-transparent',
    'h2': 'text-3xl font-bold mb-8 mt-10 text-gray-900 border-b pb-2 border-gray-200',
    'h3': 'text-2xl font-semibold mb-6 mt-8 text-gray-800',*/
    'h4': 'text-xl font-medium mb-4 mt-6 text-gray-700',
    'h5': 'text-lg font-medium mb-3 mt-5 text-gray-700',
    'h6': 'text-base font-medium mb-2 mt-4 text-gray-700',
    /*'p': 'text-gray-700 mb-6 leading-relaxed text-base',*/
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
/*    'div.div1': 'bg-white rounded-2xl shadow-xl p-8 mb-8 border-t-4 border-indigo-500', // Карточка с акцентной полосой
    'div.div2': 'bg-gradient-to-r from-indigo-50 to-blue-50 rounded-3xl p-10 mb-10 text-center', // Герой-блок
    'div.div3': 'bg-gray-100 rounded-lg p-6 mb-6 border-l-8 border-gray-300', // Панель
    'div.div4': 'grid grid-cols-1 md:grid-cols-2 gap-8 mb-10', // 2-колоночная сетка
    'div.div5': 'bg-black/90 text-white p-6 rounded-xl backdrop-blur-sm', // Темный стеклянный блок
    'div.div6': 'flex flex-col sm:flex-row gap-6 mb-8', // Горизонтальный контейнер
    'div.div7': 'bg-white/80 border border-gray-200 rounded-xl p-6 shadow-inner', // Светлый интерактивный блок
*/
    /* === Специальные div-блоки === */
    'div.div1': 'card mb-6 p-6 shadow-md hover:shadow-lg transition-shadow', // Карточка
    'div.div2': 'box has-background-primary-light p-8 rounded-xl', // Акцентный блок
    'div.div3': 'notification is-info mb-6 p-5 rounded-lg border-l-4', // Уведомление
    'div.div4': 'columns is-multiline mb-10 gap-6', // Сетка
    'div.div5': 'panel is-dark p-6 rounded-xl backdrop-blur-sm bg-black/90', // Темный блок
    'div.div6': 'flex flex-wrap gap-4 items-center', // Флекс-контейнер
    'div.div7': 'message is-info', // Сообщение Bulma
    'div.div8': 'tile is-ancestor p-6 bg-white/80 rounded-xl', // Плиточная сетка
 
    /* === Прочие элементы === */
    'hr': 'my-12 border-t border-gray-200',
    'mark': 'bg-yellow-200 px-1 rounded',
    'small': 'text-sm text-gray-500 block mb-2',
    'strong': 'font-bold text-gray-800',
    'em': 'italic text-gray-700',
    'cite': 'text-gray-500 italic',
    'time': 'text-gray-500 text-sm',
    'dialog': 'bg-white rounded-2xl shadow-xl p-8 border-0 max-w-md mx-auto'
    
    
    
    /* === Компоненты Bulma с Tailwind-модификаторами === */
    'button': 'button is-primary shadow-md hover:shadow-lg',
    'button[type="submit"]': 'button is-success is-fullwidth',
    'input, textarea, select': 'input hover:border-gray-400',
    'table': 'table is-striped is-hoverable is-fullwidth rounded-lg overflow-hidden'
    
     },

  /**
   * Инициализация ресурсов
   */
  initResources: function() {
    return new Promise((resolve) => {
      // 1. Создаем прелоадер
      this.createPreloader();
      
      // 2. Подключаем Tailwind
      const tailwindScript = document.createElement('script');
      tailwindScript.src = this.resources.tailwindScript;
      tailwindScript.onload = () => {
        // Настройка Tailwind
        tailwind.config = {
          theme: {
            extend: {
              fontFamily: {
                sans: ['Inter', 'sans-serif']
              }
            }
          }
        };
        
        // 3. Подключаем остальные ресурсы
        this.loadCSSResources().then(resolve);
      };
      document.head.appendChild(tailwindScript);
    });
  },

  /**
   * Создает прелоадер
   */
  createPreloader: function() {
    const preloader = document.createElement('div');
    preloader.id = 'mds-preloader';
    preloader.style.cssText = `
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
    `;
    preloader.innerHTML = `
      <div class="loading-spinner" style="
        width: 50px;
        height: 50px;
        border: 3px solid rgba(79, 70, 229, 0.3);
        border-radius: 50%;
        border-top-color: #4f46e5;
        animation: spin 1s ease-in-out infinite;
      "></div>
    `;
    document.body.prepend(preloader);
    
    // Добавляем анимацию
    const style = document.createElement('style');
    style.textContent = `
      @keyframes spin {
        to { transform: rotate(360deg); }
      }
      body { opacity: 0; transition: opacity 0.4s ease; }
    `;
    document.head.appendChild(style);
  },

  /**
   * Загружает CSS-ресурсы
   */
  loadCSSResources: function() {
    const resources = [
      { url: this.resources.bulmaCSS, id: 'bulma-css' },
      { url: this.resources.fontAwesome, id: 'font-awesome' },
      { url: this.resources.googleFonts, id: 'google-fonts' }
    ];

    return Promise.all(
      resources.map(res => {
        return new Promise((resolve) => {
          if (!document.getElementById(res.id)) {
            const link = document.createElement('link');
            link.href = res.url;
            link.rel = 'stylesheet';
            link.id = res.id;
            link.onload = resolve;
            document.head.appendChild(link);
          } else {
            resolve();
          }
        });
      })
    );
  },

  /**
   * Применяет стили к элементам
   */
  applyStyles: function() {
    // Основные стили
    Object.entries(this.styles).forEach(([selector, classes]) => {
      try {
        document.querySelectorAll(selector).forEach(el => {
          el.classList.add(...classes.split(' '));
        });
      } catch(e) {
        console.warn(`MDS: Error applying styles for ${selector}`, e);
      }
    });

    // Специальные обработчики
    this.handleSpecialCases();
  },

  /**
   * Обработка особых случаев
   */
  handleSpecialCases: function() {
    // Инициализация бургер-меню Bulma
    document.addEventListener('DOMContentLoaded', () => {
      const $navbarBurgers = Array.prototype.slice.call(
        document.querySelectorAll('.navbar-burger'), 
        0
      );

      $navbarBurgers.forEach(el => {
        el.addEventListener('click', () => {
          const target = el.dataset.target;
          const $target = document.getElementById(target);

          el.classList.toggle('is-active');
          $target.classList.toggle('is-active');
        });
      });
    });

    // Добавление иконок к кнопкам
    document.querySelectorAll('button.is-primary:not(.no-icon)').forEach(btn => {
      if (!btn.querySelector('i, svg')) {
        btn.innerHTML = `<i class="fas fa-arrow-right mr-2"></i> ${btn.innerHTML}`;
      }
    });

    // Обработка внешних ссылок
    document.querySelectorAll('a[href^="http"]:not([href*="${window.location.host}"])').forEach(a => {
      a.target = '_blank';
      a.rel = 'noopener noreferrer';
      if (!a.querySelector('.external-icon')) {
        a.innerHTML += ' <span class="external-icon text-xs ml-1">↗</span>';
      }
    });
  },

  /**
   * Завершает инициализацию
   */
  finalize: function() {
    // Удаляем прелоадер
    const preloader = document.getElementById('mds-preloader');
    if (preloader) {
      preloader.style.transition = 'opacity 0.3s ease';
      preloader.style.opacity = '0';
      setTimeout(() => preloader.remove(), 300);
    }

    // Показываем страницу
    document.body.style.opacity = '1';
    document.body.classList.add('mds-loaded');

    // Инициализация компонентов Bulma
    if (typeof Bulma !== 'undefined') {
      Bulma().attach();
    }
  },

  /**
   * Запуск системы
   */
  start: function() {
    // Этап 1: Загрузка ресурсов
    this.initResources()
      // Этап 2: Применение стилей
      .then(() => this.applyStyles())
      // Этап 3: Завершение
      .then(() => this.finalize())
      .catch(err => {
        console.error('MDS initialization error:', err);
        this.finalize(); // Все равно показываем страницу
      });
  }
};

// Автозапуск только если MDS не используется в SPA
if (!window.MDS_MANUAL_INIT) {
  document.addEventListener('DOMContentLoaded', () => MDS.start());
}

// Экспорт для использования в модульных системах
if (typeof module !== 'undefined' && module.exports) {
  module.exports = MDS;
} else {
  window.MDS = MDS;
}
