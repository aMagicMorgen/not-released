### 📚 **Обучающая шпаргалка по Bulma**  
**Полное руководство с примерами для быстрого освоения фреймворка**  

---

## **1. Установка Bulma**
### **Способ 1: CDN (быстрый старт)**
```html
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
```

### **Способ 2: npm (для проектов)**
```bash
npm install bulma
```
```scss
// В SCSS-файле:
@import "~bulma/bulma";
```

---

## **2. Базовая структура**
```html
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bulma</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
  <!-- Контент -->
</body>
</html>
```

---

## **3. Основные компоненты**
### **Кнопки**
```html
<a class="button">Базовая</a>
<a class="button is-primary">Основная</a>
<a class="button is-large is-rounded">Большая круглая</a>
<a class="button is-loading">Загрузка</a>
```
**Модификаторы**:  
- Цвета: `is-primary`, `is-danger`, `is-dark`...  
- Размеры: `is-small`, `is-medium`, `is-large`  
- Состояния: `is-focused`, `is-loading`, `is-disabled`  

---

### **Сетка (Columns)**
```html
<div class="columns">
  <div class="column">1</div>
  <div class="column">2</div>
  <div class="column">3</div>
</div>
```
**Гибкие варианты**:  
- `is-mobile` — колонки не переносятся  
- `is-centered` — выравнивание по центру  
- `is-vcentered` — вертикальное выравнивание  

---

### **Карточки (Card)**
```html
<div class="card">
  <div class="card-image">
    <img src="image.jpg">
  </div>
  <div class="card-content">
    <p class="title">Заголовок</p>
    <p>Описание</p>
  </div>
</div>
```

---

### **Навигация (Navbar)**
```html
<nav class="navbar is-dark">
  <div class="navbar-brand">
    <a class="navbar-item">Лого</a>
  </div>
  <div class="navbar-menu">
    <div class="navbar-start">
      <a class="navbar-item">Главная</a>
    </div>
  </div>
</nav>
```
**Добавьте бургер-меню для мобильных**:  
```javascript
document.addEventListener('DOMContentLoaded', () => {
  const $navbarBurgers = Array.prototype.slice.call(
    document.querySelectorAll('.navbar-burger'), 0
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
```

---

## **4. Формы**
```html
<div class="field">
  <label class="label">Имя</label>
  <div class="control">
    <input class="input" type="text">
  </div>
</div>

<div class="field">
  <div class="control">
    <textarea class="textarea" placeholder="Сообщение"></textarea>
  </div>
</div>

<div class="field">
  <div class="control">
    <button class="button is-primary">Отправить</button>
  </div>
</div>
```
**Дополнительно**:  
- `is-hovered`, `is-focused` — состояния  
- `has-icons-left/right` — иконки в полях  

---

## **5. Модальные окна**
```html
<div class="modal">
  <div class="modal-background"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">Заголовок</p>
      <button class="delete"></button>
    </header>
    <section class="modal-card-body">
      Контент
    </section>
  </div>
</div>
```
**Управление через JS**:  
```javascript
const modal = document.querySelector('.modal');
modal.classList.add('is-active'); // Открыть
modal.classList.remove('is-active'); // Закрыть
```

---

## **6. Адаптивность**
Используйте модификаторы для разных экранов:  
- `is-mobile` — только мобильные  
- `is-desktop` — только десктоп  
- `is-tablet` — планшеты  

**Пример**:  
```html
<div class="column is-6-mobile is-4-tablet is-3-desktop">
  Адаптивная колонка
</div>
```

---

## **7. Кастомизация**
### **SCSS-переменные**
```scss
// 1. Измените переменные перед импортом
$primary: #ff470f;
$family-sans-serif: "Inter", sans-serif;

// 2. Импортируйте Bulma
@import "~bulma/bulma";
```

### **Добавление своих стилей**
```css
/* Дополнительные классы */
.is-vertical-center {
  display: flex;
  align-items: center;
}

/* Анимации */
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
```

---

## **8. Полезные ресурсы**
1. [Официальная документация](https://bulma.io/documentation/)  
2. [Шпаргалка по классам](https://devhints.io/bulma)  
3. [Готовые шаблоны](https://bulma.io/examples/)  

---

## **Итог**
**Bulma — это:**  
✅ Простота (классы вроде `button is-primary`)  
✅ Готовые компоненты (navbar, card, modal)  
✅ Адаптивность из коробки  
✅ Легкая кастомизация через SCSS  

**Пример минимального лендинга**:  
```html
<section class="hero is-primary is-fullheight">
  <div class="hero-body">
    <div class="container has-text-centered">
      <h1 class="title">Привет, Bulma!</h1>
      <a class="button is-light is-large">Начать</a>
    </div>
  </div>
</section>
``` 

Теперь вы готовы к созданию красивых интерфейсов без сложного CSS! 🚀
