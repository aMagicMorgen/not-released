Вот дополнительный CSS, который вы можете вставить в тег `<style>` вашего лендинга, чтобы сделать элементы более округлыми, изменить шрифт и добавить другие визуальные улучшения:

```html
<style>
  /* Основные настройки */
  :root {
    --primary-color: #485fc7; /* Основной цвет Bulma (можно изменить) */
    --border-radius: 12px;    /* Базовая округлость */
  }

  /* Подключение кастомного шрифта (Google Fonts) */
  @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');
  
  body {
    font-family: 'Roboto', sans-serif;
    padding-top: 3.25rem;
  }

  /* Закругленные углы для секций */
  .hero, .section, .box, .card, .modal-card {
    border-radius: var(--border-radius);
    overflow: hidden; /* Чтобы содержимое не выходило за скругленные углы */
  }

  /* Кнопки */
  .button {
    border-radius: 50px !important; /* Сильно закругленные кнопки */
    padding-left: 1.5em;
    padding-right: 1.5em;
    transition: all 0.3s ease;
  }

  /* Поля ввода */
  .input, .textarea {
    border-radius: 8px !important;
    transition: all 0.3s ease;
  }

  .input:focus, .textarea:focus {
    box-shadow: 0 0 0 0.125em rgba(72, 95, 199, 0.25);
  }

  /* Карточки */
  .card {
    border: none;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s, box-shadow 0.3s;
  }

  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  }

  /* Навигация */
  .navbar {
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  }

  .navbar-item {
    border-radius: 6px;
    margin: 0 2px;
  transition: background-color 0.3s;
  }

  .navbar-item:hover {
    background-color: rgba(255, 255, 255, 0.1) !important;
  }

  /* Иконки */
  .icon.is-large {
    margin-bottom: 1rem;
  }

  /* Тарифы */
  .box {
    transition: transform 0.3s;
  }

  .box:hover {
    transform: scale(1.02);
  }

  /* Модальное окно */
  .modal-card {
    border-radius: var(--border-radius);
  }

  /* Анимации */
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .section {
    animation: fadeIn 0.6s ease-out;
  }

  /* Адаптивные настройки */
  @media screen and (max-width: 768px) {
    :root {
      --border-radius: 8px;
    }
    
    .hero.is-medium .hero-body {
      padding: 4rem 1.5rem;
    }
    
    .button {
      padding-left: 1em;
      padding-right: 1em;
    }
  }
</style>
```

### Что делает этот CSS:

1. **Закругленные углы**:
   - Все основные элементы (секции, карточки, кнопки, поля ввода) теперь имеют закругленные углы
   - Кнопки стали "капсульными" с `border-radius: 50px`

2. **Новый шрифт**:
   - Подключен шрифт Roboto с Google Fonts
   - Заменен стандартный шрифт Bulma

3. **Улучшенные эффекты**:
   - Плавные переходы (transition) для всех интерактивных элементов
   - Тень при наведении на карточки
   - Анимация появления секций

4. **Улучшенные формы**:
   - Красивые закругленные поля ввода
   - Эффект фокуса с мягкой тенью

5. **Адаптивность**:
   - На мобильных устройствах скругления немного меньше
   - Уменьшенные отступы для кнопок

6. **Визуальные улучшения**:
   - Тени для навигации
   - Эффект масштабирования для тарифных карточек
   - Более приятные отступы для иконок

Просто скопируйте этот код и вставьте его в тег `<style>` вашего HTML-файла (заменив предыдущие стили или дополнив их). Все изменения будут применены автоматически.
