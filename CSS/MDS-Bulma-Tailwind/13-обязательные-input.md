### **Оптимизированная MDS Input Библиотека**  
*(Без типов input, с улучшенной системой иконок)*  

---

## **1. Базовый синтаксис**  
```html
<input class="mds-input-{размер}-{стиль}-{состояние} {иконка-позиция}">
```

---

## **2. Файл `mds-input.css`**  
```css
/*
<!--MDS-->
/* ===== БАЗОВЫЙ КЛАСС ===== */
.mds-input {
  input
  w-full
  border
  border-gray-300
  transition-all
  duration-200
  focus:outline-none
  focus:ring-2
  focus:ring-blue-200
  focus:border-blue-500
}

/* ===== РАЗМЕРЫ ===== */
.mds-input-xs { px-2 py-1 text-xs h-8 }
.mds-input-sm { px-3 py-2 text-sm h-10 }
.mds-input-md { px-4 py-3 h-12 }          /* По умолчанию */
.mds-input-lg { px-5 py-4 text-lg h-14 }
.mds-input-xl { px-6 py-5 text-xl h-16 }

/* ===== СТИЛИ ===== */
.mds-input-outline {
  bg-transparent
  border-2
  border-current
}
.mds-input-filled {
  bg-gray-100
  border-none
  focus:bg-white
}
.mds-input-underline {
  border-0
  border-b-2
  rounded-none
  px-1
}

/* ===== СОСТОЯНИЯ ===== */
.mds-input-disabled {
  opacity-50
  cursor-not-allowed
}
.mds-input-error {
  border-red-500
  focus:ring-red-200
}
.mds-input-success {
  border-green-500
  focus:ring-green-200
}

/* ===== ИКОНКИ ===== */
.mds-input-icon {
  absolute
  top-1/2
  -translate-y-1/2
  text-gray-400
}
.mds-input-icon-left { left-3 }
.mds-input-icon-right { right-3 }

/* ===== ОТСТУПЫ ДЛЯ ИКОНОК ===== */
.mds-input-has-icon-left { pl-10 }
.mds-input-has-icon-right { pr-10 }
.mds-input-has-icon-both { px-10 }
<!--/MDS-->
*/
```

---

## **3. Примеры использования**  

### **Базовое поле**  
```html
<input 
  type="text" 
  class="mds-input-md" 
  placeholder="Обычное поле">
```

### **Поле с иконкой**  
```html
<div class="relative">
  <input 
    type="email" 
    class="mds-input-lg mds-input-has-icon-left"
    placeholder="Email">
  <span class="mds-input-icon mds-input-icon-left">
    <i class="fas fa-envelope"></i>
  </span>
</div>
```

### **Специальные стили**  
```html
<!-- Поле с ошибкой -->
<input 
  class="mds-input-md mds-input-error"
  value="Неверный ввод">

<!-- Подчеркнутый стиль -->
<input 
  class="mds-input-xl mds-input-underline"
  placeholder="Поиск">
```

---

## **4. Ключевые изменения**  

1. **Убраны типы input**  
   - Тип указывается в HTML-атрибуте `type="..."`  
   - Не дублируется в CSS-классах

2. **Новая система иконок**  
   - Отдельные классы для позиционирования иконок:  
     ```html
     <span class="mds-input-icon mds-input-icon-right">...</span>
     ```
   - Классы-модификаторы для полей:  
     ```html
     <input class="mds-input-has-icon-left">
     ```

3. **Упрощенный парсинг**  
   - Нет сложных комбинаций типа `mds-input-email-md`  
   - Четкое разделение:  
     - Базовый стиль (`.mds-input-md`)  
     - Модификаторы (`.mds-input-error`)  
     - Иконки (отдельная система)  

---

## **5. Преимущества нового подхода**  

1. **Чистая семантика**  
   - HTML-атрибуты отвечают за тип поля  
   - CSS-классы — только за внешний вид  

2. **Гибкость позиционирования иконок**  
   - Можно кастомизировать иконки независимо от input  
   - Поддержка любых иконок (Font Awesome, SVG и др.)  

3. **Простота расширения**  
   - Добавление новых стилей не ломает существующую логику  
   ```css
   .mds-input-glass {
     bg-white/10 backdrop-blur-sm
   }
   ```

---

## **6. Интеграция с формами**  
Пример сложной формы:  
```html
<div class="space-y-4">
  <!-- Поле с иконкой слева -->
  <div class="relative">
    <input 
      type="text" 
      class="mds-input-lg mds-input-has-icon-left"
      placeholder="Имя пользователя">
    <span class="mds-input-icon mds-input-icon-left">
      <i class="fas fa-user"></i>
    </span>
  </div>

  <!-- Поле с ошибкой -->
  <input 
    type="password" 
    class="mds-input-lg mds-input-error"
    placeholder="Пароль">

  <!-- Кастомный стиль -->
  <input 
    type="email" 
    class="mds-input-lg mds-input-outline"
    placeholder="Email">
</div>
```

---

## **7. Рекомендации по развитию**  

1. **Добавить компоненты**:  
   - Группы полей (`.mds-input-group`)  
   - Плейсхолдер-анимации  
   - Кастомные `<select>`  

2. **Создать генератор**:  
   ```js
   MDS.renderInput({
     type: "password",
     size: "lg",
     icon: "left",
     style: "underline"
   });
   ```

3. **Оптимизировать парсер**:  
   - Поддержка сокращенных записей типа `.mds-input-lg.error`  

---

## **8. Итог**  
Теперь библиотека:  
✅ Четко разделяет логику и презентацию  
✅ Дает полный контроль над иконками  
✅ Легко масштабируется для новых проектов  

Следующие шаги:  
1. Реализовать эту систему для `select`  
2. Добавить валидацию полей  
3. Создать документацию с примерами  

### **MDS Input Библиотека с нанизыванием в массиве**

Файл: `mds-input.css`

```css
/*
<!--MDS-->
/* ===== БАЗОВЫЙ КЛАСС ===== */
.mds-input {
  input
  w-full
  border
  border-gray-300
  transition-all
  duration-200
  focus:outline-none
  focus:ring-2
  focus:ring-blue-200
  focus:border-blue-500
}

/* ===== МОДИФИКАТОРЫ ===== */

/* Размеры */
.mds-input[xs] { px-2 py-1 text-xs h-8 }
.mds-input[sm] { px-3 py-2 text-sm h-10 }
.mds-input[md] { px-4 py-3 h-12 }
.mds-input[lg] { px-5 py-4 text-lg h-14 }
.mds-input[xl] { px-6 py-5 text-xl h-16 }

/* Стили */
.mds-input[outline] {
  bg-transparent
  border-2
  border-current
}
.mds-input[filled] {
  bg-gray-100
  border-none
  focus:bg-white
}
.mds-input[underline] {
  border-0
  border-b-2
  rounded-none
  px-1
}

/* Состояния */
.mds-input[disabled] {
  opacity-50
  cursor-not-allowed
}
.mds-input[error] {
  border-red-500
  focus:ring-red-200
}
.mds-input[success] {
  border-green-500
  focus:ring-green-200
}

/* Иконки */
.mds-input[icon-left] { pl-10 }
.mds-input[icon-right] { pr-10 }
.mds-input[icon-both] { px-10 }

/* Дополнительно */
.mds-input[rounded] { rounded-full }
.mds-input[transparent] { bg-transparent }
<!--/MDS-->
*/
```

### **Примеры использования**

```html
<!-- Базовый input -->
<input class="mds-input-md">

<!-- Input с нанизанными свойствами -->
<input class="mds-input-lg-outline-error-icon-left">

<!-- Группа свойств -->
<input class="mds-input-xl-filled-success-rounded">
```

### **Как работает парсер**

1. Разбивает класс на части по шаблону:
   ```js
   "mds-input-lg-outline-error-icon-left" → ["lg", "outline", "error", "icon-left"]
   ```

2. Для каждой части ищет соответствующий `[модификатор]` в CSS:
   ```css
   .mds-input[lg] { ... }
   .mds-input[outline] { ... }
   ```

3. Комбинирует все найденные стили в правильном порядке.

### **Преимущества системы**

1. **Компактность**: Один класс вместо нескольких
2. **Гибкость**: 100+ комбинаций из 15 модификаторов
3. **Читаемость**: Логичная структура `размер-стиль-состояние`

### **Полный список модификаторов**

| Категория  | Модификаторы                     |
|------------|----------------------------------|
| Размеры    | `xs`, `sm`, `md`, `lg`, `xl`     |
| Стили      | `outline`, `filled`, `underline` |
| Состояния  | `disabled`, `error`, `success`   |
| Иконки     | `icon-left`, `icon-right`        |
| Дополнительно | `rounded`, `transparent`      |

### **Интеграция с иконками**

```html
<div class="relative">
  <input class="mds-input-lg-icon-left" placeholder="Поиск">
  <i class="absolute left-3 top-1/2 transform -translate-y-1/2 fas fa-search"></i>
</div>
```

Эта система:
- Сохраняет все преимущества нанизывания свойств
- Позволяет создавать сложные комбинации одним классом
- Остается легко расширяемой для новых модификаторов
