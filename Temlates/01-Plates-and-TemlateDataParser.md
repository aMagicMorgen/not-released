Это идея сделать читаемый массив где первый элемент это массив переменных в шаблоне, а в остальных элементах массивы данных для подстановки в шаблон.
Вот полный пример с файловой структурой и кодом:

Файловая структура

```
project/
├── components/
│   └── TemplateDataParser.php
├── templates/
│   ├── layout.php
│   ├── breadcrumb.php
│   └── card.php
└── page.php
```

1. TemplateDataParser.php

```php
<?php

class TemplateDataParser {
    /**
     * Преобразует данные из удобного формата в ассоциативный массив
     * Формат: [['var1', 'var2', ...], ['val1', 'val2', ...], ...]
     */
    public static function parse($data, $defaults = []) {
        if (empty($data) || !is_array($data[0])) {
            return $data;
        }
        
        $variables = $data[0];
        $items = array_slice($data, 1);
        
        $result = [];
        foreach ($items as $item) {
            $assocItem = [];
            foreach ($variables as $index => $varName) {
                $assocItem[$varName] = $item[$index] ?? $defaults[$varName] ?? null;
            }
            $result[] = $assocItem;
        }
        return $result;
    }
    
    /**
     * Специализированные методы для разных компонентов
     */
    public static function breadcrumb($data) {
        return self::parse($data, ['active' => false, 'icon' => null]);
    }
    
    public static function card($data) {
        return self::parse($data, [
            'image' => null, 
            'button_text' => 'Подробнее',
            'button_url' => '#',
            'class' => ''
        ]);
    }
    
    public static function menu($data) {
        return self::parse($data, [
            'icon' => null,
            'badge' => null,
            'children' => null
        ]);
    }
}
```

2. templates/layout.php

```php
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->e($title ?? 'Мой сайт') ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/1.0.0/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <section class="section">
        <div class="container">
            <!-- Хлебные крошки -->
            <?php if (isset($breadcrumb)): ?>
                <?= $this->section('breadcrumb') ?>
            <?php endif; ?>
            
            <!-- Основной контент -->
            <div class="content">
                <?= $this->section('content') ?>
            </div>
        </div>
    </section>
</body>
</html>
```

3. templates/breadcrumb.php

```php
<nav class="breadcrumb" aria-label="breadcrumbs">
    <ul>
        <?php foreach ($items as $item): ?>
            <li class="<?= $item['active'] ? 'is-active' : '' ?>">
                <a href="<?= $item['url'] ?>" <?= $item['active'] ? 'aria-current="page"' : '' ?>>
                    <?php if (!empty($item['icon'])): ?>
                    <span class="icon is-small">
                        <i class="<?= $item['icon'] ?>" aria-hidden="true"></i>
                    </span>
                    <?php endif; ?>
                    <span><?= $item['text'] ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
```

4. templates/card.php

```php
<div class="card <?= $item['class'] ?>">
    <?php if (!empty($item['image'])): ?>
    <div class="card-image">
        <figure class="image is-4by3">
            <img src="<?= $item['image'] ?>" alt="<?= $item['title'] ?>">
        </figure>
    </div>
    <?php endif; ?>
    
    <div class="card-content">
        <div class="content">
            <h3 class="title is-4"><?= $item['title'] ?></h3>
            <p><?= $item['description'] ?></p>
            
            <?php if (!empty($item['button_text'])): ?>
            <a href="<?= $item['button_url'] ?>" class="button is-primary">
                <?= $item['button_text'] ?>
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>
```

5. page.php (исполняемый файл)

```php
<?php
// Подключаем автозагрузчик Composer (если используете)
// require 'vendor/autoload.php';

// Подключаем наш парсер
require 'components/TemplateDataParser.php';

// Подключаем Plates
require 'vendor/autoload.php'; // Если установили через composer
// Или скачайте plates вручную и подключите так:
// require 'plates/src/Template.php';

// Инициализируем шаблонизатор
$templates = new League\Plates\Engine('templates');

// Данные для хлебных крошек в удобном формате
$breadcrumbData = [
    ['url', 'icon', 'text', 'active'], // Переменные
    ['/', 'fas fa-home', 'Главная'],
    ['/catalog', 'fas fa-th-list', 'Каталог'],
    ['/catalog/electronics', null, 'Электроника'],
    ['#', null, 'Смартфоны', true]
];

// Данные для карточек товаров
$cardsData = [
    ['title', 'description', 'image', 'button_text', 'button_url', 'class'],
    [
        'iPhone 15', 
        'Новый iPhone с революционной камерой', 
        'https://example.com/iphone15.jpg',
        'Купить',
        '/buy/iphone15',
        'mb-4'
    ],
    [
        'Samsung Galaxy', 
        'Мощный Android-смартфон', 
        'https://example.com/galaxy.jpg',
        'В корзину',
        '/buy/galaxy',
        'mb-4'
    ],
    [
        'Google Pixel', 
        'Лучшая камера на рынке', 
        null, // Нет изображения
        'Подробнее',
        '/pixel',
        'mb-4 has-background-light'
    ]
];

// Преобразуем данные
$breadcrumbItems = TemplateDataParser::breadcrumb($breadcrumbData);
$cardItems = TemplateDataParser::card($cardsData);

// Рендерим страницу
echo $templates->render('layout', [
    'title' => 'Магазин электроники'
], [
    'breadcrumb' => $templates->render('breadcrumb', ['items' => $breadcrumbItems]),
    'content' => '
        <h1 class="title">Наши товары</h1>
        <div class="columns">
            <div class="column is-one-third">' . 
                $templates->render('card', ['item' => $cardItems[0]]) . 
            '</div>
            <div class="column is-one-third">' . 
                $templates->render('card', ['item' => $cardItems[1]]) . 
            '</div>
            <div class="column is-one-third">' . 
                $templates->render('card', ['item' => $cardItems[2]]) . 
            '</div>
        </div>
    '
]);
```

Альтернативная версия page.php (более структурированная)

```php
<?php
// Подключаем зависимости
require 'components/TemplateDataParser.php';
require 'vendor/autoload.php';

$templates = new League\Plates\Engine('templates');

// Подготовка данных
$breadcrumbItems = TemplateDataParser::breadcrumb([
    ['url', 'icon', 'text', 'active'],
    ['/', 'fas fa-home', 'Главная'],
    ['/catalog', 'fas fa-th-list', 'Каталог'],
    ['#', null, 'Смартфоны', true]
]);

$products = TemplateDataParser::card([
    ['title', 'description', 'image', 'button_text', 'button_url', 'class'],
    [
        'iPhone 15', 
        'Новый iPhone с революционной камерой', 
        'https://example.com/iphone15.jpg',
        'Купить',
        '/buy/iphone15',
        'mb-4'
    ],
    [
        'Samsung Galaxy', 
        'Мощный Android-смартфон', 
        'https://example.com/galaxy.jpg',
        'В корзину', 
        '/buy/galaxy',
        'mb-4'
    ]
]);

// Рендерим компоненты
$breadcrumbHtml = $templates->render('breadcrumb', ['items' => $breadcrumbItems]);

$productsHtml = '';
foreach ($products as $product) {
    $productsHtml .= '<div class="column is-one-third">' . 
        $templates->render('card', ['item' => $product]) . 
    '</div>';
}

// Финальный рендер
echo $templates->render('layout', [
    'title' => 'Магазин электроники'
], [
    'breadcrumb' => $breadcrumbHtml,
    'content' => '
        <h1 class="title">Наши товары</h1>
        <div class="columns">' . $productsHtml . '</div>
        <div class="notification is-info">
            <strong>Всего товаров:</strong> ' . count($products) . '
        </div>
    '
]);
```

Установка и запуск

1. Установите Plates (если еще не установлен):

```bash
composer require league/plates
```

1. Создайте структуру папок как показано выше
2. Поместите файлы в соответствующие папки
3. Запустите:

```bash
php page.php
```

Преимущества этого подхода:

1. Читаемость - данные в удобном формате
2. Переиспользование - один парсер для всех компонентов
3. Гибкость - легко добавлять новые компоненты
4. Структура - четкое разделение логики и представления
5. Расширяемость - легко добавить новые методы парсера

Теперь у вас есть полноценная система для работы с шаблонами!
