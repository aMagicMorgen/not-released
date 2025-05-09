### **Полная MDS Input Библиотека с иконками и лейблами**

Файл: `mds-input.css`

```css
/*
<!--MDS-->
/* ===== БАЗОВЫЕ СТИЛИ ===== */
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

/* ===== МОДИФИКАТОРЫ INPUT ===== */
/* Размеры */
.mds-input[xs] { px-2 py-1 text-xs h-8 }
.mds-input[sm] { px-3 py-2 text-sm h-10 }
.mds-input[md] { px-4 py-3 h-12 }
.mds-input[lg] { px-5 py-4 text-lg h-14 }
.mds-input[xl] { px-6 py-5 text-xl h-16 }

/* Стили */
.mds-input[outline] { bg-transparent border-2 border-current }
.mds-input[filled] { bg-gray-100 border-none focus:bg-white }
.mds-input[underline] { border-0 border-b-2 rounded-none px-1 }

/* Состояния */
.mds-input[disabled] { opacity-50 cursor-not-allowed }
.mds-input[error] { border-red-500 focus:ring-red-200 }
.mds-input[success] { border-green-500 focus:ring-green-200 }

/* Иконки */
.mds-input[icon-left] { pl-10 }
.mds-input[icon-right] { pr-10 }
.mds-input[icon-both] { px-10 }

/* ===== ИКОНКИ (MDS-I) ===== */
.mds-i {
  absolute 
  top-1/2 
  -translate-y-1/2 
  text-gray-400
}
.mds-i[left] { left-3 }
.mds-i[right] { right-3 }
.mds-i[primary] { text-blue-500 }
.mds-i[error] { text-red-500 }

/* ===== ЛЕЙБЛЫ (MDS-LABEL) ===== */
.mds-label {
  block 
  mb-1 
  text-sm 
  font-medium 
  text-gray-700
}
.mds-label[required]:after { content: "*" text-red-500 ml-1 }
.mds-label[error] { text-red-500 }

/* ===== ШАБЛОНЫ (MDH-INPUT) ===== */
.mdh-input-default {
<0 div .relative>
  <1 label .mds-label>
  <2 input .mds-input-md>
</0>
}

.mdh-input-with-icon {
<0 div .relative>
  <1 label .mds-label>
  <2 input .mds-input-md-icon-left>
  <3 i .mds-i .mds-i-left .fas .fa-user>
</0>
}

.mdh-input-error-state {
<0 div .relative>
  <1 label .mds-label .mds-label-error>
  <2 input .mds-input-md-error>
  <3 i .mds-i .mds-i-right .mds-i-error .fas .fa-exclamation-circle>
</0>
}
<!--/MDS-->
*/
```

### **Примеры использования**

```html
<!-- Простое поле с лейблом -->
<div class="mdh-input-default">
  <label>Username</label>
  <input type="text">
</div>

<!-- Поле с иконкой -->
<div class="mdh-input-with-icon">
  <label>Email</label>
  <input type="email">
  <i class="fas fa-envelope"></i>
</div>

<!-- Поле с ошибкой -->
<div class="mdh-input-error-state">
  <label>Password</label>
  <input type="password" value="wrong">
  <i class="fas fa-exclamation-circle"></i>
</div>
```

### **Автоматическая генерация классов**

Парсер будет преобразовывать:
```html
<input class="mds-input-lg-outline-error-icon-left">
```
В:
```css
input {
  /* Базовые стили */
  width: 100%;
  border: 1px solid #d1d5db;
  transition: all 0.2s;
  
  /* Модификаторы */
  padding: 1.25rem 1.5rem;
  height: 3.5rem;
  font-size: 1.125rem;
  background-color: transparent;
  border-width: 2px;
  border-color: currentColor;
  border-color: #ef4444;
  padding-left: 2.5rem;
  --tw-ring-color: #fecaca;
}
```

### **Ключевые особенности**

1. **Полная система компонентов**:
   - Инпуты с модификаторами
   - Иконки с позиционированием
   - Лейблы с состояниями

2. **Готовые шаблоны**:
   - `mdh-input-default` - базовая версия
   - `mdh-input-with-icon` - с иконкой
   - `mdh-input-error-state` - с валидацией

3. **Гибкое нанизывание**:
   - Комбинируйте любые модификаторы
   - Порядок не важен (`mds-input-lg-error` = `mds-input-error-lg`)

4. **Простая кастомизация**:
   ```html
   <div class="mdh-input-with-icon">
     <label class="text-purple-600">Custom Label</label>
     <input class="mds-input-xl-rounded">
     <i class="fas fa-search text-purple-400"></i>
   </div>
   ```

### **Рекомендации по развитию**

1. Добавить поддержку:
   - `<select>` элементов
   - Чекбоксов и радиокнопок
   - Валидации в реальном времени

2. Создать:
   - Документацию с примерами
   - Генератор кода для форм
   - Визуальный конструктор

Эта система обеспечивает:
✅ Полную готовность к использованию  
✅ Гибкость настройки  
✅ Согласованность стилей  
✅ Простоту поддержки
