Напиши большой красивый лендинг с Cdn tailwind с подключением style.css в котором есть текст mcss

```html
<!DOCTYPE html>
<html lang="ru" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Красивый лендинг с Tailwind и MCSS</title>
  <!-- Tailwind CDN с кастомным конфигом -->
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#3B82F6',
            secondary: '#10B981',
            accent: '#F59E0B',
            dark: '#1F2937',
          },
          fontFamily: {
            sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            heading: ['"Poppins"', 'sans-serif'],
          },
          boxShadow: {
            'custom-lg': '0 10px 15px -3px rgba(59, 130, 246, 0.5), 0 4px 6px -2px rgba(59, 130, 246, 0.3)',
          }
        }
      }
    }
  </script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="style.css" />
</head>
<body class="bg-gray-50 font-sans text-gray-800">

  <!-- HEADER -->
  <header class="sticky top-0 bg-white shadow-md z-50">
    <nav class="container mx-auto flex items-center justify-between py-4 px-6">
      <a href="#" class="text-2xl font-heading font-bold text-primary">MagicBrand</a>
      <ul class="flex space-x-8 text-lg font-medium">
        <li><a href="#features" class="hover:text-primary transition">Возможности</a></li>
        <li><a href="#pricing" class="hover:text-primary transition">Цены</a></li>
        <li><a href="#contact" class="hover:text-primary transition">Контакты</a></li>
      </ul>
      <a href="#signup" class="btn-primary px-5 py-2 rounded-lg font-semibold shadow-md hover:shadow-lg transition">Начать</a>
    </nav>
  </header>

  <!-- HERO SECTION -->
  <section class="bg-gradient-to-r from-primary to-secondary text-white py-28">
    <div class="container mx-auto px-6 text-center max-w-4xl">
      <h1 class="text-5xl font-heading font-extrabold mb-6 leading-tight">Создавайте красивые сайты быстро и легко</h1>
      <p class="text-xl mb-10 max-w-3xl mx-auto">Используйте мощь Tailwind CSS и MCSS для динамической стилизации без лишних усилий.</p>
      <a href="#signup" class="inline-block bg-white text-primary font-semibold px-10 py-4 rounded-lg shadow-lg hover:bg-gray-100 transition">Попробовать бесплатно</a>
    </div>
  </section>

  <!-- FEATURES -->
  <section id="features" class="py-20 bg-white">
    <div class="container mx-auto px-6 max-w-6xl">
      <h2 class="text-4xl font-heading font-bold text-center mb-12">Наши возможности</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
        <div class="feature-card p-8 bg-gray-50 rounded-xl shadow-custom-lg text-center">
          <svg class="mx-auto mb-6 w-16 h-16 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>
          <h3 class="text-2xl font-semibold mb-3">Легкая интеграция</h3>
          <p>Просто подключите Tailwind и MCSS, и ваши стили применяются автоматически.</p>
        </div>
        <div class="feature-card p-8 bg-gray-50 rounded-xl shadow-custom-lg text-center">
          <svg class="mx-auto mb-6 w-16 h-16 text-secondary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/></svg>
          <h3 class="text-2xl font-semibold mb-3">Динамическая стилизация</h3>
          <p>Меняйте классы на лету через MCSS, не трогая HTML вручную.</p>
        </div>
        <div class="feature-card p-8 bg-gray-50 rounded-xl shadow-custom-lg text-center">
          <svg class="mx-auto mb-6 w-16 h-16 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          <h3 class="text-2xl font-semibold mb-3">Кастомизация</h3>
          <p>Настраивайте цвета, шрифты и эффекты через Tailwind config и MCSS.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- PRICING -->
  <section id="pricing" class="py-20 bg-gray-100">
    <div class="container mx-auto px-6 max-w-5xl">
      <h2 class="text-4xl font-heading font-bold text-center mb-12">Цены</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="p-8 bg-white rounded-xl shadow-lg text-center">
          <h3 class="text-2xl font-semibold mb-4">Бесплатно</h3>
          <p class="text-5xl font-extrabold mb-6">0 ₽</p>
          <ul class="mb-8 space-y-3 text-gray-600">
            <li>Доступ к базовым функциям</li>
            <li>Ограниченная кастомизация</li>
            <li>Поддержка сообщества</li>
          </ul>
          <a href="#signup" class="btn-primary px-6 py-3 rounded-lg font-semibold shadow hover:shadow-lg transition inline-block">Выбрать</a>
        </div>
        <div class="p-8 bg-white rounded-xl shadow-lg border-4 border-primary text-primary text-center">
          <h3 class="text-2xl font-semibold mb-4">Профессионал</h3>
          <p class="text-5xl font-extrabold mb-6">1490 ₽/мес</p>
          <ul class="mb-8 space-y-3">
            <li>Все функции бесплатно</li>
            <li>Полная кастомизация MCSS</li>
            <li>Приоритетная поддержка</li>
          </ul>
          <a href="#signup" class="bg-primary text-white px-6 py-3 rounded-lg font-semibold shadow hover:shadow-lg transition inline-block">Выбрать</a>
        </div>
        <div class="p-8 bg-white rounded-xl shadow-lg text-center">
          <h3 class="text-2xl font-semibold mb-4">Корпоративный</h3>
          <p class="text-5xl font-extrabold mb-6">Свяжитесь с нами</p>
          <ul class="mb-8 space-y-3 text-gray-600">
            <li>Персональный менеджер</li>
            <li>Интеграция и обучение</li>
            <li>Индивидуальные решения</li>
          </ul>
          <a href="#contact" class="btn-primary px-6 py-3 rounded-lg font-semibold shadow hover:shadow-lg transition inline-block">Связаться</a>
        </div>
      </div>
    </div>
  </section>

  <!-- SIGNUP FORM -->
  <section id="signup" class="py-20 bg-white">
    <div class="container mx-auto px-6 max-w-3xl">
      <h2 class="text-4xl font-heading font-bold text-center mb-8">Начните сейчас</h2>
      <form class="bg-gray-50 rounded-xl p-8 shadow-md max-w-xl mx-auto space-y-6" action="#" method="POST">
        <div>
          <label for="email" class="block mb-2 font-semibold">Email</label>
          <input type="email" id="email" name="email" required placeholder="you@example.com" class="w-full border border-gray-300 rounded px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary" />
        </div>
        <div>
          <label for="password" class="block mb-2 font-semibold">Пароль</label>
          <input type="password" id="password" name="password" required placeholder="••••••••" class="w-full border border-gray-300 rounded px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary" />
        </div>
        <button type="submit" class="btn-primary w-full py-3 rounded-lg font-semibold shadow hover:shadow-lg transition">Создать аккаунт</button>
      </form>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="bg-dark text-white py-10 mt-20">
    <div class="container mx-auto px-6 text-center text-sm text-gray-400">
      © 2025 MagicBrand. Все права защищены.
    </div>
  </footer>

  <!-- MCSS Script подключается после Tailwind -->
  <script src="mcjs-loader.js"></script>
  <script>
    // Пример инициализации MCSS, который загрузит style.css, вырежет /*mcss ... mcss*/ и применит классы
    MCssJsLoader.loadFromFile('style.css').then(() => {
      MCssJsLoader.apply();
    });
  </script>

  <style>
    /* Дополнительные стили кнопок */
    .btn-primary {
      @apply bg-primary text-white font-semibold shadow-md hover:bg-primary/90 transition px-6 py-3 rounded-lg;
    }
    .feature-card svg {
      stroke-width: 1.5;
    }
  </style>
</body>
</html>
```

---

### В `style.css` (рядом с этим лендингом) пример MCSS:

```css
/*mcss
h1, h2, h3 {
  font-heading font-extrabold
  tracking-tight
  text-wrap:balance
}

.title, #header {
  text-lg
  text-primary
}

.btn-primary {
  bg-primary
  hover:bg-primary/90
  text-white
  font-semibold
  px-6
  py-3
  rounded-lg
  shadow-md
  transition
}

.feature-card {
  bg-gray-50
  rounded-xl
  shadow-custom-lg
  p-8
  text-center
}

input[type=submit] {
  btn-primary
  cursor-pointer
}
mcss*/

/* Обычный CSS */
body {
  font-family: 'Inter', sans-serif;
}
```

---

### Объяснение

- Лендинг построен на Tailwind CDN с кастомной темой.
- MCSS хранится в `style.css` внутри комментариев `/*mcss ... mcss*/`.
- JS-скрипт MCssJsLoader (ваш кастомный) загружает `style.css`, вырезает MCSS, генерирует и применяет классы динамически.
- HTML максимально семантичен и чист, классы добавляются автоматически.
- Структура: header, hero, features, pricing, signup, footer — современный и адаптивный лендинг.

---

Если нужно, могу помочь с реализацией MCssJsLoader.js или с серверной частью!

