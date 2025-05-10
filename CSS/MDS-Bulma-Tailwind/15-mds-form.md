### **MDS Form Библиотека (Только контейнеры форм)**  
Файл: `mds-form.css`  

```css
/*
<!--MDS-->
/* ===== БАЗОВЫЙ КОНТЕЙНЕР ===== */
.mds-form {
  form
  space-y-4
  p-6
  bg-white
  rounded-lg
  shadow-sm
}

/* ===== ВАРИАНТЫ ФОРМ ===== */
/* Карточная форма */
.mds-form[card] {
  border
  border-gray-200
  shadow-md
}

/* Прозрачная форма */
.mds-form[transparent] {
  bg-transparent
  shadow-none
  p-0
}

/* Темная форма */
.mds-form[dark] {
  bg-gray-800
  text-white
}

/* Форма с разделителями */
.mds-form[divided] {
  space-y-0
  divide-y
  divide-gray-200
}

/* ===== РАЗМЕРЫ ===== */
.mds-form[sm] { p-4 }
.mds-form[lg] { p-8 }

/* ===== СПЕЦИАЛЬНЫЕ ЭФФЕКТЫ ===== */
.mds-form[glow] {
  shadow-lg
  shadow-blue-500/10
}
<!--/MDS-->
*/
```

### **Примеры использования**  
```html
<!-- Базовая форма -->
<form class="mds-form">
  <!-- Сюда добавляются inputs/buttons -->
</form>

<!-- Карточная форма -->
<form class="mds-form-card-lg-glow">
  <!-- Контент -->
</form>

<!-- Темная форма с разделителями -->
<form class="mds-form-dark-divided">
  <!-- Группы полей -->
</form>
```

### **Принцип именования**  
```
.mds-form-{стиль}-{размер}-{эффект}
```

### **Что делает этот файл**  
1. **Задает структуру**:  
   - Отступы (`space-y-4`)  
   - Фон/границы  
   - Тени/скругления  

2. **Предоставляет варианты**:  
   - `card` – форма в карточке  
   - `divided` – с разделителями  
   - `dark` – темная тема  

3. **Не включает**:  
   - Стили для `input`, `button` (это в своих модулях)  
   - Гриды/сложные layout (это для `mds-layout.css`)  

### **Как расширять**  
Добавляйте новые варианты по аналогии:  
```css
.mds-form[neumorphism] {
  background: #f0f0f0;
  box-shadow: 8px 8px 15px #d9d9d9, -8px -8px 15px #ffffff;
}
```
