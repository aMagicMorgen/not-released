# Полезные-css для голого HTML
Да, существуют библиотеки, которые автоматически стилизуют чистый HTML без необходимости вручную прописывать классы. Они применяют стили непосредственно к тегам (`<h1>`, `<p>`, `<table>` и т.д.), делая страницу визуально приятной "из коробки". Вот несколько популярных решений:

---

### 1. **Water.css**  
**Особенности**:  
- Просто подключите CSS-файл — библиотека стилизует стандартные HTML-теги.  
- Поддерживает светлую и тёмную тему (автоподстройка под систему).  
- Нет зависимостей от JS, только CSS.  

**Пример подключения**:  
```html
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
```

**Демо**: [https://watercss.kognise.dev/](https://watercss.kognise.dev/)  

---

### 2. **MVP.css**  
**Особенности**:  
- Стилизует тексты, формы, таблицы и даже `<code>` без классов.  
- Адаптивный дизайн для мобильных.  
- Минимум кода (всего ~10 КБ).  

**Пример подключения**:  
```html
<link rel="stylesheet" href="https://unpkg.com/mvp.css">
```

**Демо**: [https://andybrewer.github.io/mvp/](https://andybrewer.github.io/mvp/)  

---

### 3. **Pico.css**  
**Особенности**:  
- Семантический дизайн (стили для `<header>`, `<nav>`, `<section>`).  
- Встроенная сетка на CSS Grid.  
- Поддержка темной/светлой темы.  

**Пример подключения**:  
```html
<link rel="stylesheet" href="https://unpkg.com/@picocss/pico@latest/css/pico.min.css">
```

**Демо**: [https://picocss.com/examples/preview/](https://picocss.com/examples/preview/)  

---

### 4. **Sakura** (для минималистов)  
**Особенности**:  
- Очень легкий (~7 КБ).  
- Стилизует базовые теги в нежных тонах.  

**Пример подключения**:  
```html
<link rel="stylesheet" href="https://unpkg.com/sakura.css/css/sakura.css">
```

**Демо**: [https://oxal.org/projects/sakura/](https://oxal.org/projects/sakura/)  

---

### 5. **Tacit** (для графиков и данных)  
**Особенности**:  
- Фокус на контенте (хорошо стилизует `<table>`, `<pre>`).  
- Подходит для технической документации.  

**Пример подключения**:  
```html
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/yegor256/tacit@gh-pages/tacit-css.min.css">
```

**Демо**: [https://yegor256.github.io/tacit/](https://yegor256.github.io/tacit/)  

---

### Как выбрать?  
- Для **блога/документации**: Water.css или MVP.css.  
- Для **сложных лэндингов**: Pico.css.  
- Для **минимализма**: Sakura.  

Все они работают без JavaScript и не требуют добавления классов — просто пишите чистый HTML, и стили применятся автоматически! 🎨

Да, существуют JavaScript-библиотеки, которые автоматически анализируют структуру HTML и добавляют классы (или изменяют DOM) для применения стилей. Они полезны, когда нужно сохранить чистый HTML, но при этом получить сложное оформление. Вот несколько популярных вариантов:  

---

### **1. TailwindCSS (с плагином `@tailwindcss/typography`)**  
**Особенности**:  
- Не требует ручного добавления классов в HTML.  
- Плагин `typography` автоматически стилизует стандартные теги (`<h1>`, `<p>`, `<ul>` и т.д.) в эстетичный вид.  
- Работает через PostCSS.  

**Как подключить**:  
```html
<!-- Подключаем Tailwind -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2/dist/tailwind.min.css" rel="stylesheet">
<!-- Плагин для типографики -->
<link href="https://cdn.jsdelivr.net/npm/@tailwindcss/typography@0.5.x/dist/typography.min.css" rel="stylesheet">
```

**Пример**:  
```html
<article class="prose">
  <h1>Заголовок</h1>
  <p>Текст с автоматическими стилями.</p>
</article>
```
**Демо**: [Tailwind Typography](https://tailwindcss.com/docs/typography-plugin)  

---

### **2. ReMarked.js**  
**Особенности**:  
- Анализирует HTML и добавляет классы для типографики (например, стилизует `<blockquote>`, `<code>`).  
- Не требует изменений в исходном HTML.  

**Как подключить**:  
```html
<script src="https://cdn.jsdelivr.net/npm/remarked@1.0.0/dist/remarked.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => ReMarked.init());
</script>
```

**Демо**: [ReMarked.js GitHub](https://github.com/glitchbone/remarked)  

---

### **3. Automatic.css (ACSS)**  
**Особенности**:  
- JavaScript-библиотека, которая сканирует DOM и добавляет классы на основе семантических тегов.  
- Поддерживает адаптивность и темы.  

**Пример**:  
```html
<script src="https://cdn.jsdelivr.net/npm/automatic.css@latest/dist/automatic.min.js"></script>
```
После подключения все `<h1>`, `<table>`, `<ul>` получат стили автоматически.  

---

### **4. Beauter**  
**Особенности**:  
- Мини-фреймворк, который добавляет классы для сетки, кнопок и типографики.  
- Можно использовать как с классами, так и без (стилизует базовые теги).  

**Как подключить**:  
```html
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/beauter@0.1.1/dist/beauter.min.css">
```

**Демо**: [Beauter GitHub](https://github.com/naoyeye/beauter)  

---

### **5. CSSBed (JIT-стилизация)**  
**Особенности**:  
- JavaScript-библиотека, которая динамически добавляет классы для элементов на странице.  
- Позволяет кастомизировать стили через API.  

**Пример**:  
```html
<script src="https://cdn.jsdelivr.net/npm/cssbed@1.0.5/dist/cssbed.min.js"></script>
<script>
  CSSBed.apply(); // Стилизует все элементы
</script>
```

---

### **Как выбрать?**  
- **Для блогов/документации**: Tailwind Typography или ReMarked.js.  
- **Для динамических сайтов**: Automatic.css или CSSBed.  
- **Минимум кода**: Beauter.  

Эти библиотеки позволяют сохранить HTML чистым, но при этом получить современный дизайн без ручной работы. 🛠️  

**Важно**: Некоторые из них (например, Tailwind) требуют сборки, поэтому для простых проектов лучше выбирать варианты с CDN.
