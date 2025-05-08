# **MDS.CSS Библиотека**  
*(Markdown Style Framework)*  

```css
<!-- ===== Автоподключение CDN ===== -->  
@import url("https://cdn.jsdelivr.net/npm/bulma@1.0.0/css/bulma.min.css");  
@import url("https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css");  
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css");  

<!-- ===== БАЗОВЫЕ СТИЛИ ===== -->  
:root {  
  --mds-primary: #3b82f6;  
  --mds-radius: 0.5rem;  
}  

/* <!--MDS-->   

<!-- ===== КНОПКИ ===== -->  
.mds-btn {  
  button  
  tw-px-6 tw-py-2  
  tw-rounded-[var(--mds-radius)]  
  tw-transition-all  
  hover:tw-scale-105  
}  

.mds-btn-primary {  
  .mds-btn  
  is-primary  
  tw-bg-[var(--mds-primary)]  
  tw-text-white  
}  

.mds-btn-outline {  
  .mds-btn  
  is-outlined  
  tw-border-2  
  tw-border-[var(--mds-primary)]  
}  

<!-- Шаблоны кнопок -->  
.mdh-btn-download {  
  <0 .mds-btn-primary>  
    <1 i .fas .fa-download>  
    <1 span>Скачать  
}  

.mdh-btn-submit {  
  <0 .mds-btn .tw-w-full>  
    <1 span>Отправить форму  
}  

<!-- ===== КАРТОЧКИ ===== -->  
.mds-card {  
  card  
  tw-p-6  
  tw-rounded-[var(--mds-radius)]  
  tw-shadow-md  
}  

.mds-card-glass {  
  .mds-card  
  tw-bg-white/10  
  tw-backdrop-blur-sm  
}  

<!-- Шаблоны карточек -->  
.mdh-card-feature {  
  <0 .mds-card-glass>  
    <1 h3 .title .tw-mb-4>Заголовок  
    <1 p .tw-text-gray-600>Описание  
}  

<!-- ===== СЕКЦИИ ===== -->  
.mds-section {  
  section  
  is-fullwidth  
  tw-py-20  
}  

.mds-section-hero {  
  .mds-section  
  tw-bg-gradient-to-r  
  tw-from-blue-500  
  tw-to-purple-600  
}  

<!-- Шаблоны секций -->  
.mdh-section-hero {  
  <0 .mds-section-hero>  
    <1 .container>  
      <2 h1 .title .is-1>Заголовок  
      <2 .mdh-btn-download .tw-mt-8>  
}  

<!-- ===== ФОРМЫ ===== -->  
.mds-input {  
  input  
  tw-w-full  
  tw-mb-4  
  tw-rounded-[var(--mds-radius)]  
}  

<!-- Шаблон формы -->  
.mdh-form-contact {  
  <0 .mds-card>  
    <1 h3 .title .is-3>Контакты  
    <1 .mds-input placeholder="Имя">  
    <1 .mds-input placeholder="Email">  
    <1 .mdh-btn-submit>  
}  

<!-- ===== НАВИГАЦИЯ ===== -->  
.mds-nav-item {  
  navbar-item  
  tw-px-4  
  hover:tw-bg-gray-100  
}  

<!-- Шаблон меню -->  
.mdh-nav-main {  
  <0 navbar .tw-shadow-sm>  
    <1 .navbar-brand>  
      <2 .navbar-item .tw-font-bold>Лого  
    <1 .navbar-menu>  
      <2 .navbar-start>  
        <3 .mds-nav-item>Главная  
        <3 .mds-nav-item>О нас  
}  

<!--/MDS-->  */

<!-- ===== АДАПТИВНЫЕ УТИЛИТЫ ===== -->  
@media (max-width: 768px) {  
  .mds-section { tw-py-12 }  
  .mdh-nav-main { <0 navbar .is-spaced> }  
}  
```

## **Структура библиотеки**  
1. **Суперклассы (`.mds-`)**  
   - Комбинируют Bulma + Tailwind  
   - Задают базовые стили для элементов  

2. **Шаблоны (`.mdh-`)**  
   - Используют суперклассы  
   - Определяют структуру компонентов  
   - Поддерживают вложенность через `<0> <1>`  

3. **Особенности**  
   - CSS-переменные для быстрой кастомизации  
   - Адаптивные модификаторы  
   - Готовые иконки (Font Awesome)  

## **Пример использования**  
```markdown
<!-- В .md или .mds файле -->  
<!--MDH-->  
<0 .mdh-section-hero>  
  [Добро пожаловать, Скачать презентацию]  
</0>  

<0 .mdh-form-contact>  
  [Свяжитесь с нами, Ваше имя, Ваш email]  
</0>  
<!--/MDH-->  
```

## **Рекомендации по развитию**  
1. Добавить больше шаблонов:  
   - Галереи  
   - Таблицы  
   - Аккордеоны  

2. Реализовать темы:  
   - Светлая/тёмная  
   - Цветовые схемы  

3. Оптимизировать:  
   - Минификацию CSS  
   - Tree-shaking неиспользуемых стилей  

Библиотека готова к использованию! Для старта достаточно подключить `mds.css`.
