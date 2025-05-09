### **Полная библиотека кнопок (MDS-формат с нанизыванием свойств)**

Файл: `mds-btn.css`

```css
/*
<!--MDS-->
/* ===== БАЗОВЫЙ КЛАСС ===== */
.mds-btn { 
  button 
  px-6 py-3 
  font-medium 
  transition-all 
  duration-200 
  focus:outline-none 
}

/* ===== ЦВЕТА ===== */
.mds-btn[primary] { is-primary bg-blue-600 hover:bg-blue-700 text-white }
.mds-btn[danger] { is-danger bg-red-600 hover:bg-red-700 text-white }
.mds-btn[success] { is-success bg-green-600 hover:bg-green-700 text-white }
.mds-btn[warning] { is-warning bg-yellow-500 hover:bg-yellow-600 text-white }
.mds-btn[light] { is-light bg-gray-100 hover:bg-gray-200 text-gray-800 }
.mds-btn[dark] { is-dark bg-gray-800 hover:bg-gray-900 text-white }

/* ===== ФОРМЫ ===== */
.mds-btn[square] { rounded-none }          /* 1 */
.mds-btn[rounded] { rounded-lg }           /* 2 */
.mds-btn[pill] { rounded-full }            /* 3 */

/* ===== РАЗМЕРЫ ===== */
.mds-btn[xs] { px-3 py-1 text-xs }         /* 1 */
.mds-btn[sm] { px-4 py-2 text-sm }         /* 2 */
.mds-btn[md] { px-6 py-3 }                 /* 3 */
.mds-btn[lg] { px-8 py-4 text-lg }         /* 4 */
.mds-btn[xl] { px-10 py-5 text-xl }        /* 5 */

/* ===== СОСТОЯНИЯ ===== */
.mds-btn[disabled] { opacity-50 cursor-not-allowed }
.mds-btn[loading] { 
  relative pl-10 
  after:absolute after:left-3 after:top-1/2 after:-translate-y-1/2
  after:content-[""] after:border-2 after:border-current 
  after:border-t-transparent after:rounded-full 
  after:w-4 after:h-4 after:animate-spin
}

/* ===== ЭФФЕКТЫ ===== */
.mds-btn[shadow] { shadow-md hover:shadow-lg }
.mds-btn[glass] { 
  bg-white/10 backdrop-blur-sm 
  border border-white/20 hover:border-white/30
}
.mds-btn[scale] { hover:scale-105 transform transition-transform }
.mds-btn[outline] { 
  bg-transparent border-2 
  border-current hover:bg-current/10
}

/* ===== ГРУППЫ ===== */
.mds-btn[group-h] { 
  first:rounded-r-none last:rounded-l-none 
  not-first:border-l-0 not-last:border-r-0
}
.mds-btn[group-v] { 
  first:rounded-b-none last:rounded-t-none 
  not-first:border-t-0 not-last:border-b-0
}
<!--/MDS-->
*/
```

### **Примеры использования**

```html
<!-- Базовые кнопки -->
<button class="mds-btn-primary-md-rounded">Основная</button>
<button class="mds-btn-danger-sm-pill">Удалить</button>

<!-- Кнопки с эффектами -->
<button class="mds-btn-success-lg-glass-shadow">Премиум</button>
<button class="mds-btn-dark-xl-outline-scale">Интерактивная</button>

<!-- Группы кнопок -->
<div>
  <button class="mds-btn-primary-md-rounded-group-h">Да</button>
  <button class="mds-btn-light-md-rounded-group-h">Нет</button>
</div>

<!-- Состояния -->
<button class="mds-btn-warning-lg-loading">Загрузка...</button>
<button class="mds-btn-primary-md-disabled">Недоступно</button>
```

### **Принцип именования**
```
.mds-btn-{цвет}-{размер}-{форма}-{эффекты}-{группа}
```

1. **Порядок не важен** (можно `.mds-btn-primary-md-glass` или `.mds-btn-glass-md-primary`)
2. **Все параметры опциональны** (достаточно `.mds-btn-primary`)
3. **Автоматическая комбинация** свойств

### **Как работает парсер**
1. Разбивает класс на части после `mds-btn-`
2. Для каждой части ищет соответствующий `[модификатор]` в CSS
3. Объединяет все стили в правильном порядке:
   - Базовый `.mds-btn`
   - Цвет
   - Размер
   - Форма
   - Эффекты
   - Состояния

### **Преимущества системы**
1. **Минималистичный синтаксис** - короткие и понятные классы
2. **Гибкость** - 120+ комбинаций из 15 базовых модификаторов
3. **Масштабируемость** - легко добавить новые варианты
4. **Семантичность** - понятные названия параметров

Для полной реализации необходимо:
1. Написать парсер (PHP/JS)
2. Создать систему валидации комбинаций
3. Документировать все варианты

Какой модуль будем разрабатывать следующим?
