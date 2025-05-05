# Полный лендинг на Bulma (bumla.html)

Вот готовый HTML-файл, который объединяет все рассмотренные примеры в один полноценный лендинг с комментариями:

```html
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Учебный лендинг на Bulma</title>
  <!-- Bulma CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* Небольшие кастомные стили для наглядности */
    body {
      padding-top: 3.25rem; /* Отступ для фиксированного navbar */
    }
    .section {
      padding: 3rem 1.5rem;
    }
    .hero.is-medium .hero-body {
      padding: 6rem 1.5rem;
    }
    .card {
      height: 100%;
      transition: transform 0.3s;
    }
    .card:hover {
      transform: translateY(-5px);
    }
    .social-links .icon {
      margin: 0 10px;
    }
  </style>
</head>
<body>
  <!-- 1. Навигация (Navbar) -->
  <nav class="navbar is-primary is-fixed-top" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
      <a class="navbar-item" href="#">
        <strong>BulmaLand</strong>
      </a>
      <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasic">
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
      </a>
    </div>
    <div id="navbarBasic" class="navbar-menu">
      <div class="navbar-start">
        <a class="navbar-item" href="#features">Возможности</a>
        <a class="navbar-item" href="#pricing">Цены</a>
        <a class="navbar-item" href="#contact">Контакты</a>
      </div>
      <div class="navbar-end">
        <div class="navbar-item">
          <div class="buttons">
            <a class="button is-light">Войти</a>
            <a id="registerBtn" class="button is-warning">Регистрация</a>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <!-- 2. Герой-секция (Hero) -->
  <section class="hero is-medium is-link">
    <div class="hero-body">
      <div class="container has-text-centered">
        <h1 class="title is-1">Добро пожаловать в BulmaLand</h1>
        <h2 class="subtitle">Самый простой способ изучить Bulma на практике!</h2>
        <a class="button is-warning is-large">Начать бесплатно</a>
      </div>
    </div>
  </section>

  <!-- 3. Карточки (Cards) -->
  <section id="features" class="section">
    <div class="container">
      <h2 class="title has-text-centered">Наши возможности</h2>
      <div class="columns is-centered is-multiline">
        <div class="column is-4">
          <div class="card">
            <div class="card-content has-text-centered">
              <span class="icon is-large has-text-info">
                <i class="fas fa-3x fa-rocket"></i>
              </span>
              <h3 class="title is-4">Быстрота</h3>
              <p>Bulma ускоряет разработку в разы благодаря готовым компонентам.</p>
            </div>
          </div>
        </div>
        <div class="column is-4">
          <div class="card">
            <div class="card-content has-text-centered">
              <span class="icon is-large has-text-success">
                <i class="fas fa-3x fa-mobile-alt"></i>
              </span>
              <h3 class="title is-4">Адаптивность</h3>
              <p>Идеально выглядит на любых устройствах без лишнего кода.</p>
            </div>
          </div>
        </div>
        <div class="column is-4">
          <div class="card">
            <div class="card-content has-text-centered">
              <span class="icon is-large has-text-danger">
                <i class="fas fa-3x fa-cogs"></i>
              </span>
              <h3 class="title is-4">Гибкость</h3>
              <p>Легко настраивается под ваш уникальный дизайн.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 4. Таблица цен -->
  <section id="pricing" class="section has-background-light">
    <div class="container">
      <h2 class="title has-text-centered">Наши тарифы</h2>
      <div class="columns is-centered">
        <div class="column is-4">
          <div class="box">
            <h3 class="title is-4">Базовый</h3>
            <h4 class="subtitle is-6">10$/месяц</h4>
            <ul>
              <li>Доступ к базовым компонентам</li>
              <li>Поддержка по email</li>
              <li>Обновления 1 раз в месяц</li>
            </ul>
            <a class="button is-fullwidth is-primary">Выбрать</a>
          </div>
        </div>
        <div class="column is-4">
          <div class="box">
            <h3 class="title is-4">Профессиональный</h3>
            <h4 class="subtitle is-6">25$/месяц</h4>
            <ul>
              <li>Все компоненты Bulma</li>
              <li>Приоритетная поддержка</li>
              <li>Еженедельные обновления</li>
            </ul>
            <a class="button is-fullwidth is-warning">Выбрать</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 5. Форма обратной связи -->
  <section id="contact" class="section">
    <div class="container">
      <h2 class="title has-text-centered">Свяжитесь с нами</h2>
      <div class="box">
        <form>
          <div class="field">
            <label class="label">Имя</label>
            <div class="control">
              <input class="input" type="text" placeholder="Ваше имя">
            </div>
          </div>
          <div class="field">
            <label class="label">Email</label>
            <div class="control has-icons-left">
              <input class="input" type="email" placeholder="Email">
              <span class="icon is-small is-left">
                <i class="fas fa-envelope"></i>
              </span>
            </div>
          </div>
          <div class="field">
            <label class="label">Сообщение</label>
            <div class="control">
              <textarea class="textarea" placeholder="Ваше сообщение"></textarea>
            </div>
          </div>
          <div class="field">
            <div class="control">
              <button class="button is-primary">Отправить</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>

  <!-- 6. Модальное окно -->
  <div class="modal" id="modal">
    <div class="modal-background"></div>
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">Регистрация</p>
        <button class="delete" aria-label="close"></button>
      </header>
      <section class="modal-card-body">
        <div class="field">
          <label class="label">Имя</label>
          <div class="control">
            <input class="input" type="text" placeholder="Ваше имя">
          </div>
        </div>
        <div class="field">
          <label class="label">Email</label>
          <div class="control has-icons-left">
            <input class="input" type="email" placeholder="Email">
            <span class="icon is-small is-left">
              <i class="fas fa-envelope"></i>
            </span>
          </div>
        </div>
        <div class="field">
          <label class="label">Пароль</label>
          <div class="control has-icons-left">
            <input class="input" type="password" placeholder="Пароль">
            <span class="icon is-small is-left">
              <i class="fas fa-lock"></i>
            </span>
          </div>
        </div>
      </section>
      <footer class="modal-card-foot">
        <button class="button is-success">Зарегистрироваться</button>
        <button class="button">Отмена</button>
      </footer>
    </div>
  </div>

  <!-- 7. Футер -->
  <footer class="footer">
    <div class="content has-text-centered">
      <p>
        <strong>BulmaLand</strong> © 2023. Пример учебного лендинга.
      </p>
      <div class="social-links">
        <a class="icon has-text-info" href="#"><i class="fab fa-2x fa-vk"></i></a>
        <a class="icon has-text-primary" href="#"><i class="fab fa-2x fa-telegram"></i></a>
        <a class="icon has-text-danger" href="#"><i class="fab fa-2x fa-youtube"></i></a>
      </div>
    </div>
  </footer>

  <script>
    // JavaScript для функциональности
    document.addEventListener('DOMContentLoaded', () => {
      // Активация бургер-меню
      const navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
      if (navbarBurgers.length > 0) {
        navbarBurgers.forEach(el => {
          el.addEventListener('click', () => {
            const target = el.dataset.target;
            const targetEl = document.getElementById(target);
            el.classList.toggle('is-active');
            targetEl.classList.toggle('is-active');
          });
        });
      }

      // Модальное окно
      const modal = document.getElementById('modal');
      const modalBtn = document.getElementById('registerBtn');
      const closeBtns = document.querySelectorAll('.delete, .modal-background, .modal-card-foot .button:not(.is-success)');

      modalBtn.addEventListener('click', () => {
        modal.classList.add('is-active');
      });

      closeBtns.forEach(btn => {
        btn.addEventListener('click', () => {
          modal.classList.remove('is-active');
        });
      });
    });
  </script>
</body>
</html>
```

## Ключевые особенности этого лендинга:

1. **Полноценная структура**:
   - Фиксированная навигация (Navbar)
   - Герой-секция (Hero)
   - Секция с карточками возможностей
   - Таблица тарифов
   - Форма обратной связи
   - Модальное окно регистрации
   - Футер с соц. сетями

2. **Использованные компоненты Bulma**:
   - `navbar` - адаптивное меню с бургером для мобильных
   - `hero` - баннер с призывом к действию
   - `columns` и `card` - сетка карточек
   - `box` и `table` - блоки с тарифами
   - `form` с `field` и `control` - форма обратной связи
   - `modal` - всплывающее окно
   - `footer` - подвал сайта

3. **Интерактивность**:
   - Работающее бургер-меню на мобильных
   - Открытие/закрытие модального окна
   - Эффекты при наведении на карточки

4. **Адаптивность**:
   - Полностью адаптивный дизайн для всех устройств
   - Гибкие колонки, которые меняют расположение на мобильных

5. **Дополнительные улучшения**:
   - Небольшие кастомные стили для анимаций
   - Иконки Font Awesome
   - Простые JavaScript-обработчики

Этот файл можно сохранить как `bumla.html` и открыть в любом браузере - все будет работать без необходимости установки дополнительных зависимостей.
