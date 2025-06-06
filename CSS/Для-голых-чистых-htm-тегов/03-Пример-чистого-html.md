Вот чистый HTML лендинг с семантической разметкой, который превратится в красивую современную страницу при подключении `mds-html.css`:

```html
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Design System Demo</title>
    <link rel="stylesheet" href="mds-html.css">
</head>
<body>
    <!-- Навигация -->
    <nav>
        <div>
            <a href="#">
                <span>MDS</span>
            </a>
            <ul>
                <li><a href="#features">Возможности</a></li>
                <li><a href="#pricing">Цены</a></li>
                <li><a href="#contact">Контакты</a></li>
            </ul>
            <button>Войти</button>
        </div>
    </nav>

    <!-- Герой секция -->
    <section>
        <div>
            <div>
                <h1>Современный дизайн для вашего сайта</h1>
                <p>Автоматическое преобразование чистого HTML в стильный современный интерфейс с помощью MDS</p>
                <div>
                    <button>Начать бесплатно</button>
                    <button>Демо</button>
                </div>
            </div>
            <img src="https://via.placeholder.com/800x500" alt="Главное изображение">
        </div>
    </section>

    <!-- Возможности -->
    <section id="features">
        <div>
            <h2>Наши возможности</h2>
            <p>MDS предлагает все необходимое для создания современного сайта</p>
            
            <div>
                <article>
                    <i class="fas fa-bolt"></i>
                    <h3>Мгновенный дизайн</h3>
                    <p>Просто добавьте HTML-теги и получите готовый дизайн без лишних классов</p>
                </article>
                
                <article>
                    <i class="fas fa-mobile-alt"></i>
                    <h3>Полная адаптивность</h3>
                    <p>Идеальное отображение на всех устройствах автоматически</p>
                </article>
                
                <article>
                    <i class="fas fa-palette"></i>
                    <h3>Гибкая настройка</h3>
                    <p>Легко меняйте цвета, шрифты и другие параметры через CSS-переменные</p>
                </article>
            </div>
        </div>
    </section>

    <!-- Пример формы -->
    <section id="contact">
        <div>
            <h2>Свяжитесь с нами</h2>
            <p>Оставьте заявку и мы ответим в течение 24 часов</p>
            
            <form>
                <div>
                    <label>Ваше имя</label>
                    <input type="text" placeholder="Иван Иванов">
                </div>
                
                <div>
                    <label>Email</label>
                    <input type="email" placeholder="example@mail.com">
                </div>
                
                <div>
                    <label>Сообщение</label>
                    <textarea rows="5" placeholder="Ваше сообщение..."></textarea>
                </div>
                
                <button type="submit">Отправить</button>
            </form>
        </div>
    </section>

    <!-- Цены -->
    <section id="pricing">
        <div>
            <h2>Наши тарифы</h2>
            <p>Выберите подходящий вариант для вашего проекта</p>
            
            <div>
                <article>
                    <h3>Базовый</h3>
                    <p><span>$9</span>/месяц</p>
                    <ul>
                        <li>До 10 страниц</li>
                        <li>Базовые компоненты</li>
                        <li>Email поддержка</li>
                    </ul>
                    <button>Выбрать</button>
                </article>
                
                <article>
                    <h3>Профессиональный</h3>
                    <p><span>$29</span>/месяц</p>
                    <ul>
                        <li>До 100 страниц</li>
                        <li>Все компоненты</li>
                        <li>Приоритетная поддержка</li>
                    </ul>
                    <button>Выбрать</button>
                </article>
                
                <article>
                    <h3>Премиум</h3>
                    <p><span>$99</span>/месяц</p>
                    <ul>
                        <li>Неограниченно страниц</li>
                        <li>Эксклюзивные компоненты</li>
                        <li>Персональный менеджер</li>
                    </ul>
                    <button>Выбрать</button>
                </article>
            </div>
        </div>
    </section>

    <!-- Футер -->
    <footer>
        <div>
            <div>
                <h3>MDS</h3>
                <p>Современная система дизайна для вашего сайта</p>
            </div>
            
            <div>
                <h4>Навигация</h4>
                <ul>
                    <li><a href="#features">Возможности</a></li>
                    <li><a href="#pricing">Цены</a></li>
                    <li><a href="#contact">Контакты</a></li>
                </ul>
            </div>
            
            <div>
                <h4>Контакты</h4>
                <address>
                    <p>email: info@mds.example</p>
                    <p>телефон: +7 (123) 456-78-90</p>
                </address>
            </div>
        </div>
        
        <div>
            <p>© 2023 Modern Design System. Все права защищены.</p>
        </div>
    </footer>
</body>
</html>
```

### Что включает этот лендинг:
1. **Полноценную структуру**:
   - Семантические теги (`nav`, `section`, `article`, `footer`)
   - Иерархию заголовков (`h1`-`h4`)
   - Логические разделы (герой, возможности, цены, контакты)

2. **Все основные элементы**:
   - Навигационное меню
   - Кнопки разных типов
   - Форму с инпутами
   - Карточки с иконками
   - Тарифные планы
   - Футер с контактами

3. **Адаптивность**:
   - Гибкая сетка (преобразуется в колонки на мобильных)
   - Правильные viewport-метатеги
   - Отзывчивые изображения

4. **Готовые стили через MDS**:
   - Автоматические стили для всех элементов
   - Современные анимации и эффекты
   - Градиенты и тени
   - Красивые формы

После подключения `mds-html.css` этот чистый HTML превратится в:
- Современный лендинг с градиентами
- Адаптивную сетку
- Стильные карточки с hover-эффектами
- Красивые формы с фокус-состояниями
- Единое стилевое пространство

Все это работает без добавления классов - только семантическая HTML-разметка!
