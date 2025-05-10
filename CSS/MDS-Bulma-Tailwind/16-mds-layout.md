### **MDS Layout Библиотека**  
*(Гибкая система сеток и контейнеров)*  

Файл: `mds-layout.css`  

```css
/*
<!--MDS-->
/* ===== БАЗОВЫЕ КОНТЕЙНЕРЫ ===== */
.mds-container {
  container
  mx-auto
  px-4
  w-full
}

.mds-section {
  section
  py-12
  md:py-16
}

/* ===== СЕТКИ ===== */
/* Горизонтальные */
.mds-grid {
  grid
  gap-4
  grid-cols-1
}

.mds-grid[cols-2] { grid-cols-2 }
.mds-grid[cols-3] { grid-cols-3 }
.mds-grid[cols-4] { grid-cols-4 }

/* Адаптивные */
.mds-grid[responsive] {
  grid-cols-1 
  sm:grid-cols-2 
  lg:grid-cols-3
}

/* ===== FLEX-РАСКЛАДКИ ===== */
.mds-flex-row {
  flex
  flex-row
  gap-4
}

.mds-flex-col {
  flex
  flex-col
  gap-4
}

/* Выравнивание */
.mds-flex[center] { items-center justify-center }
.mds-flex[between] { justify-between }
.mds-flex[around] { justify-around }

/* ===== СПЕЦИАЛЬНЫЕ МАКЕТЫ ===== */
/* Карточный грид */
.mds-layout-card-grid {
  grid
  gap-6
  grid-cols-1
  md:grid-cols-2
  lg:grid-cols-3
}

/* Sidebar + Main */
.mds-layout-sidebar {
  grid
  grid-cols-1
  lg:grid-cols-[300px_1fr]
  gap-8
}

/* Fullscreen Hero */
.mds-layout-hero {
  min-h-screen
  flex
  flex-col
  justify-center
}
<!--/MDS-->
*/
```

---

### **Примеры использования**  

#### **1. Адаптивная сетка**  
```html
<div class="mds-grid-responsive">
  <div>Блок 1</div>
  <div>Блок 2</div>
  <div>Блок 3</div>
</div>
```

#### **2. Flex-раскладка**  
```html
<div class="mds-flex-row mds-flex-between">
  <div>Левый</div>
  <div>Правый</div>
</div>
```

#### **3. Сайдбар + Контент**  
```html
<div class="mds-layout-sidebar">
  <aside>Сайдбар</aside>
  <main>Основной контент</main>
</div>
```

#### **4. Герой-секция**  
```html
<section class="mds-layout-hero">
  <h1>Fullscreen Hero</h1>
</section>
```

---

### **Ключевые особенности**  

1. **Модульная система**:  
   - Отдельные классы для сеток (`mds-grid`), флекс-контейнеров (`mds-flex`)  
   - Специальные макеты (`mds-layout-sidebar`)  

2. **Адаптивность из коробки**:  
   ```css
   .mds-grid[responsive] {
     grid-cols-1 
     sm:grid-cols-2 
     lg:grid-cols-3
   }
   ```

3. **Настраиваемые варианты**:  
   - Разное количество колонок (`cols-2`, `cols-3`)  
   - Готовые решения для типовых страниц  

4. **Интеграция с другими модулями**:  
   ```html
   <div class="mds-grid">
     <div class="mds-card">...</div>
     <div class="mds-card">...</div>
   </div>
   ```

---

### **Рекомендации по расширению**  
1. Добавить:  
   ```css
   /* Masonry-раскладка */
   .mds-layout-masonry {
     columns: 3;
     gap: 1rem;
   }
   ```

2. Включить утилиты для:  
   - Отступов между секциями  
   - Выравнивания контента  
   - Кастомных grid-шаблонов  

Файл готов к интеграции в ваш проект! Для сложных сценариев можно добавить больше специализированных классов.
