Ваша идея с введением формата **`.mdh` (Markdown Hybrid)** — это отличное решение для разделения ответственности между файлами. Вот детальный анализ и рекомендации:

---

### **1. Зачем нужно разделение `*.mds` и `*.mdh`?**
| Формат       | Назначение                          | Пример использования               |
|--------------|-------------------------------------|------------------------------------|
| **`.mds`**   | Чистая стилизованная разметка       | Шаблоны компонентов (кнопки, карточки) |
| **`.mdh`**   | Гибридный файл с PHP/HTML           | Страницы с логикой (лендинги, формы)  |

**Преимущества:**
- **Чёткое разделение**:  
  `.mds` — только структура и стили, `.mdh` — логика и смешанный контент.
- **Безопасность**:  
  Ограничение PHP-кода в `.mds` снижает риск инъекций.
- **Поддержка IDE**:  
  Редакторы лучше подсвечивают синтаксис, когда файл имеет конкретное назначение.

---

### **2. Реализация `*.mdh` на практике**

#### **Пример файла `page.mdh`**
```php
<!-- Подключаем MDSL-шаблон -->
<!--mdsl header-->
title = <?= htmlspecialchars($pageTitle) ?>
<!--/mdsl-->

<!-- PHP-логика -->
<?php
$users = getUsers();
?>

<!-- HTML + Markdown -->
<main class="container">
  <?php foreach ($users as $user): ?>
    <div class="card">
      ## <?= $user['name'] ?>
      Email: <?= $user['email'] ?>
    </div>
  <?php endforeach; ?>

  <!-- Вставка PHUG-шаблона -->
  <?= $pug->render('component.pug') ?>
</main>
```

#### **Обработчик в `index.php`**
```php
function renderMDH(string $file, array $data = []): string {
    ob_start();
    extract($data, EXTR_SKIP);
    include $file; // PHP выполняется напрямую
    $html = ob_get_clean();
    
    // Обработка Markdown (если есть)
    if (str_contains($html, '## ')) {
        $html = (new Parsedown())->text($html);
    }
    
    return $html;
}

// Использование:
echo renderMDH('page.mdh', ['pageTitle' => 'Главная']);
```

---

### **3. Интеграция с PHUG (бывший Pug)**
Для поддержки синтаксиса вроде:
```pug
// component.pug
each user in users
  .card
    h2= user.name
    p= user.email
```

1. Установите пакет:
   ```bash
   composer require pug-php/pug
   ```

2. Добавьте в `renderMDH()`:
   ```php
   $pug = new Pug\Pug(['cache' => 'cache/pug']);
   $html = $pug->render($pugContent);
   ```

---

### **4. Почему это лучше, чем смешивание в `.mds`?**
- **Предсказуемость**:  
  `.mds` — только стили и структура, `.mdh` — логика и шаблоны.
- **Безопасность**:  
  Файлы `.mds` можно кэшировать "как есть", а `.mdh` требуют обработки PHP.
- **Масштабируемость**:  
  Легко добавить новые движки (Twig, Blade) для `.mdh`.

---

### **5. Дополнительные рекомендации**
1. **Правила именования**:  
   - `components/button.mds` — для переиспользуемых компонентов  
   - `pages/home.mdh` — для страниц с логикой  

2. **Линтеры**:  
   Настройте ESLint/PHPStan для проверки синтаксиса в `.mdh`.

3. **Документация**:  
   Чётко опишите в проекте:
   ```
   *.mds — Pure Markdown + DSL (без PHP)
   *.mdh — Hybrid (Markdown + PHP/HTML/Pug)
   ```

---

### **Итог**
Предложение ввести **`.mdh`** — это:
✅ Чистая архитектура  
✅ Лучшая безопасность  
✅ Гибкость для разработчиков  

Готов помочь с реализацией или адаптацией под ваш проект! 😊  

P.S. Для старта достаточно переименовать файлы с PHP-кодом в `.mdh` и использовать `renderMDH()` вместо общего парсера.
