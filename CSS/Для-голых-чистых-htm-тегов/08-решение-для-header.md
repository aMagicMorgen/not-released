Проблема с несворачивающимся навигационным меню в Bulma возникает из-за отсутствия необходимого JavaScript для работы "бургерного" меню. Вот как это исправить:

### Решение для адаптивного header в Bulma

1. **Добавьте недостающий HTML для бургер-меню**:
Замените ваш текущий `<nav>` на эту структуру:

```html
<header>
  <nav class="navbar is-white" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
      <a class="navbar-item" href="#">
        <span class="text-2xl font-bold text-indigo-600">MDS</span>
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
        <a class="navbar-item" href="#gallery">Галерея</a>
        <a class="navbar-item" href="#contact">Контакты</a>
      </div>

      <div class="navbar-end">
        <div class="navbar-item">
          <div class="buttons">
            <a class="button is-primary">Демо</a>
          </div>
        </div>
      </div>
    </div>
  </nav>
</header>
```

2. **Добавьте JavaScript для работы бургера**:
Добавьте этот скрипт перед закрывающим тегом `</body>`:

```html
<script>
document.addEventListener('DOMContentLoaded', () => {
  // Получаем все "бургеры" на странице
  const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

  // Добавляем обработчик клика на каждый
  $navbarBurgers.forEach( el => {
    el.addEventListener('click', () => {
      // Получаем цель из атрибута "data-target"
      const target = el.dataset.target;
      const $target = document.getElementById(target);

      // Переключаем класс "is-active"
      el.classList.toggle('is-active');
      $target.classList.toggle('is-active');
    });
  });
});
</script>
```

3. **Обновите стили для лучшего отображения** (добавьте в CSS):
```css
@media screen and (max-width: 1023px) {
  .navbar-menu {
    background-color: white;
    box-shadow: 0 8px 16px rgba(10, 10, 10, 0.1);
    padding: 0.5rem 0;
  }
  
  .navbar-item {
    padding: 0.75rem 1rem;
  }
}
```

### Почему это работает:

1. **navbar-burger** - это кнопка, которая будет отображаться только на мобильных устройствах
2. **navbar-menu** - скрывается на мобильных и показывается при клике на бургер
3. JavaScript переключает класс `is-active`, который управляет видимостью меню
4. Bulma автоматически скрывает меню на мобильных при ширине экрана менее 1024px

### Дополнительные улучшения:

Для лучшей интеграции с вашим текущим кодом, обновите стилизацию в MDS.js:

```javascript
'nav': 'navbar is-white is-fixed-top', // Добавляем fixed-top
'nav ul': 'navbar-start',
'nav li': 'navbar-item',
'nav a': 'navbar-link',
```

Это сделает навигационную панель:
- Липкой (прилипает к верху)
- Адаптивной (автоматически сворачивается на мобильных)
- Сохраняющей все ваши текущие стили

Теперь ваш header будет правильно работать на всех устройствах!
