### **Эталонный файл `mds.css`**  
*(Главный файл системы, подключающий все зависимости и модули)*  

```css
/*
<!--MDS-->
/* ===== ЯДРО СИСТЕМЫ ===== */

/* 1. Подключение CDN */
.mds-core-bulma { 
  @import url('https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css');
}

.mds-core-tailwind { 
  @import url('https://cdn.jsdelivr.net/npm/@tailwindcss/utilities@latest');
}

/* 2. Шрифты и иконки */
.mds-core-fonts {
  /* Google Fonts */
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
  
  /* Font Awesome */
  @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
  
  :root {
    --mds-font-main: 'Inter', sans-serif;
  }
  body {
    font-family: var(--mds-font-main);
  }
}

/* 3. Базовые стили */
.mds-base-styles {
  :root {
    --mds-primary: #3b82f6;
    --mds-danger: #ef4444;
    --mds-radius: 0.375rem;
  }
  
  * {
    box-sizing: border-box;
    -webkit-tap-highlight-color: transparent;
  }
  
  html {
    scroll-behavior: smooth;
  }
}

/* ===== ПОДКЛЮЧЕНИЕ МОДУЛЕЙ ===== */

/* 4. Кнопки */
.mds-module-buttons { 
  @import 'mds-btn.css';
}

/* 5. Формы */
.mds-module-forms { 
  @import 'mds-input.css';
}

/* 6. Карточки (пример будущего модуля) */
.mds-module-cards {
  /* @import 'mds-card.css'; */
}

/* ===== УТИЛИТЫ ===== */

/* 7. Анимации */
.mds-utils-animations {
  .mds-fade-in {
    animation: mdsFadeIn 0.3s ease-out;
  }
  @keyframes mdsFadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
  }
}

/* 8. Кастомные утилиты */
.mds-utils-custom {
  .mds-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: var(--mds-primary) transparent;
  }
  .mds-shadow-depth {
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
  }
}
<!--/MDS-->
```

---

### **Что подключено и зачем**  

#### **1. Ядро системы**  
- **Bulma**: Готовые компоненты (сетка, модалки и др.)  
- **Tailwind Utilities**: Утилитарные классы (отступы, цвета)  
- **Шрифты**: Inter (современный шрифт) + Font Awesome  

#### **2. Модули**  
1. `mds-btn.css` - Все стили кнопок  
2. `mds-input.css` - Поля ввода, лейблы, иконки  
3. *Зарезервировано для*:  
   - `mds-card.css` - Карточки  
   - `mds-nav.css` - Навигация  

#### **3. Полезные дополнения**  
- **Анимации**: Готовые эффекты появления  
- **Кастомные утилиты**:  
  - Стилизация скроллбаров  
  - Глубинные тени  
- **Базовые переменные**: Цвета, радиусы  

---

### **Как использовать**  
1. Подключите `mds.css` в `<head>`:  
   ```html
   <link rel="stylesheet" href="css/mds.css">
   ```

2. Используйте компоненты:  
   ```html
   <!-- Кнопка -->
   <button class="mds-btn-blue-lg-rounded">Click</button>
   
   <!-- Поле с иконкой -->
   <div class="mdh-input-with-icon">
     <label>Email</label>
     <input type="email" class="mds-input-md-icon-left">
     <i class="fas fa-envelope"></i>
   </div>
   ```

3. Кастомизируйте через CSS-переменные:  
   ```css
   :root {
     --mds-primary: #8b5cf6; /* Изменяем основной цвет */
   }
   ```

---

### **Что можно добавить**  
1. **Доп. модули**:  
   ```css
   /* Таблицы */
   @import 'mds-table.css';
   
   /* Аккордеоны */
   @import 'mds-accordion.css';
   ```

2. **Темы**:  
   ```css
   .mds-theme-dark {
     --mds-bg: #1a1a1a;
     --mds-text: #ffffff;
   }
   ```

3. **Генератор компонентов**:  
   ```js
   MDS.render('button', { color: 'blue', size: 'lg' });
   ```

---

### **Преимущества структуры**  
- **Модульность**: Подключайте только нужное  
- **Скорость**: CDN + оптимизированные стили  
- **Согласованность**: Единые переменные и правила  
- **Масштабируемость**: Легко добавить новые компоненты  

Файл готов к использованию и дальнейшему расширению!
