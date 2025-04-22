# Вот CSS для адаптивной таблицы, которая перестраивается из горизонтального вида в вертикальный при ширине экрана ≤ 600px:  

### **HTML (пример таблицы)**  
```html
<table class="responsive-table">
  <thead>
    <tr>
      <th>Заголовок 1</th>
      <th>Заголовок 2</th>
      <th>Заголовок 3</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td data-label="Заголовок 1">Данные 1</td>
      <td data-label="Заголовок 2">Данные 2</td>
      <td data-label="Заголовок 3">Данные 3</td>
    </tr>
  </tbody>
</table>
```

### **CSS (адаптивный стиль)**  
```css
.responsive-table {
  width: 100%;
  border-collapse: collapse;
  margin: 1em 0;
}

.responsive-table th, 
.responsive-table td {
  padding: 0.75em;
  text-align: left;
  border: 1px solid #ddd;
}

/* Горизонтальный вид (ширина > 600px) */
@media (min-width: 601px) {
  .responsive-table thead {
    background-color: #f5f5f5;
  }
}

/* Вертикальный вид (ширина ≤ 600px) */
@media (max-width: 600px) {
  .responsive-table thead {
    display: none; /* Скрываем заголовки */
  }

  .responsive-table tr {
    display: block;
    margin-bottom: 1em;
    border: 1px solid #ddd;
  }

  .responsive-table td {
    display: block;
    text-align: right;
    position: relative;
    padding-left: 50%;
  }

  .responsive-table td::before {
    content: attr(data-label);
    position: absolute;
    left: 0.75em;
    font-weight: bold;
    text-align: left;
  }
}
```

### **Как это работает?**  
1. **На больших экранах (>600px)** — таблица выглядит как обычная, с горизонтальными рядами.  
2. **На мобильных (≤600px)**:
   - Заголовки скрываются (`display: none`).
   - Каждая ячейка (`td`) превращается в блок (`display: block`).
   - Текст выравнивается по правому краю, а слева добавляется псевдоэлемент `::before` с подписью из `data-label`.  

### **Дополнительные улучшения**  
- Можно добавить `word-break: break-word`, чтобы длинный текст не вылезал за границы.  
- Для лучшего UX на мобильных можно уменьшить `padding`.  

Такой подход делает таблицу удобочитаемой на любом устройстве! 📱💻
