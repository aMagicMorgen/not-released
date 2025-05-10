Вот полная модифицированная версия `mds.js` с интеграцией Bulma, Tailwind CSS, Font Awesome и Google Fonts, включающая все необходимые CDN-подключения и механизм отложенного отображения страницы:

```javascript
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
    
    /* === Компоненты Bulma с Tailwind-модификаторами === */
    'button': 'button is-primary shadow-md hover:shadow-lg',
    'button[type="submit"]': 'button is-success is-fullwidth',
    'input, textarea, select': 'input hover:border-gray-400',
    'table': 'table is-striped is-hoverable is-fullwidth rounded-lg overflow-hidden',
    
    /* === Специальные div-блоки === */
    'div.div1': 'card mb-6 p-6 shadow-md hover:shadow-lg transition-shadow', // Карточка
    'div.div2': 'box has-background-primary-light p-8 rounded-xl', // Акцентный блок
    'div.div3': 'notification is-info mb-6 p-5 rounded-lg border-l-4', // Уведомление
    'div.div4': 'columns is-multiline mb-10 gap-6', // Сетка
    'div.div5': 'panel is-dark p-6 rounded-xl backdrop-blur-sm bg-black/90', // Темный блок
    'div.div6': 'flex flex-wrap gap-4 items-center', // Флекс-контейнер
    'div.div7': 'message is-info', // Сообщение Bulma
    'div.div8': 'tile is-ancestor p-6 bg-white/80 rounded-xl' // Плиточная сетка
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
```

### Особенности реализации:

1. **Полноценная загрузка всех зависимостей**:
   - Bulma CSS через CDN
   - Tailwind CSS через официальный скрипт
   - Font Awesome для иконок
   - Google Fonts (Inter)

2. **Улучшенный прелоадер**:
   - CSS-анимация спиннера
   - Плавное исчезновение
   - Автоматическое удаление после загрузки

3. **Гибридные стили**:
   - Комбинация классов Bulma и Tailwind
   - Готовые стилизованные компоненты
   - 8 специальных div-блоков для быстрой верстки

4. **Оптимизации производительности**:
   - Последовательная загрузка ресурсов
   - Обработка ошибок
   - Поддержка SPA (можно отключить автостарт)

5. **Дополнительные функции**:
   - Автоматическое добавление иконок
   - Обработка внешних ссылок
   - Инициализация бургер-меню Bulma

Для использования просто подключите этот файл в `<head>` вашего HTML-документа:

```html
<script src="mds.js"></script>
```

Скрипт полностью самостоятельный и не требует дополнительных настроек, но при необходимости можно отключить автоинициализацию, установив `window.MDS_MANUAL_INIT = true` перед подключением скрипта.
