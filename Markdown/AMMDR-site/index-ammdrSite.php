<?php
/**
 * aMagic Markdown Site - Main Site with Tailwind CSS
 */
  require_once 'ammdrSite.php';
 $content = './';
 $ammdrSite_json = 'ammdrSite.json';
 /*
// Настройка параметров
ammdrSite::configure([
    'contentDir' => $content, //__DIR__ . '/content',  // Папка с Markdown-файлами
    'outputFile' => $ammdrSite_json,  //__DIR__ . '/data/' . $ammdrSite_json,  // Куда сохранить JSON
    'excludedDirs' => ['.', '..', '.git', 'node_modules'],  // Исключаемые папки
    'previewLines' => 5  // Сколько строк брать для превью
]);
$ammdrS = "aMagic Markdown Site";
$ammdrS_short = "ammdrSite";
$site_name = 'САЙТ из файлов *.md';
$site_intro = 'ТЕМЫ СЛЕВА';
$menu_name = 'ТЕМА: ';
$files = 'МАТЕРИАЛЫ: ';

// Запуск генерации
#ammdrSite::generate();
*/

$ammdrS = "aMagic Markdown Site";
$ammdrS_short = "ammdrSite";
$site_name = 'САЙТ из файлов *.md';
$site_intro = 'ТЕМЫ СЛЕВА';
$menu_name = 'ТЕМА: ';
$files = 'МАТЕРИАЛЫ: ';


 if(!file_exists($ammdrSite_json)) ammdrSite:: generate();
// Load the JSON index
$jsonFile = __DIR__ . '/ammdrSite.json';
if (!file_exists($jsonFile)) {
    die("Error: ammdrSite.json not found. Сделайте разрешение записи в Вашей дериктории");
}

$content = json_decode(file_get_contents($jsonFile), true);
$menuItems = $content['content'] ?? [];


// Function to render Markdown (simplified)
function renderMarkdown($text) {
    // Simple Markdown to HTML conversion (for more features use a library like Parsedown)
    $text = htmlspecialchars($text);
    $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
    $text = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $text);
    $text = preg_replace('/\[(.*?)\]\((.*?)\)/', '<a href="$2" class="text-blue-600 hover:text-blue-800 hover:underline">$1</a>', $text);
    $text = preg_replace('/\#\s(.*?)\n/', '<h1 class="text-2xl font-bold mt-6 mb-4">$1</h1>', $text);
    $text = preg_replace('/\#\#\s(.*?)\n/', '<h2 class="text-xl font-bold mt-5 mb-3">$1</h2>', $text);
    $text = preg_replace('/\#\#\#\s(.*?)\n/', '<h3 class="text-lg font-bold mt-4 mb-2">$1</h3>', $text);
    $text = nl2br($text);
    return $text;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($ammdrS) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style type="text/tailwindcss">
        @layer utilities {
            .full-title { display: block; }
            .short-title { display: none; }
            @media (max-width: 640px) {
                .full-title { display: none; }
                .short-title { display: block; }
            }
            
            #main-nav {
                transform: translateX(-100%);
                @apply fixed top-0 left-0 h-full w-64 bg-gray-800 text-white z-50 transition-transform duration-300 ease-in-out;
            }
            
            #main-nav.active {
                transform: translateX(0);
            }
            
            .mobile-menu-btn span {
                @apply block w-6 h-0.5 bg-white mb-1 transition-all duration-300;
            }
            
            .mobile-menu-btn.active span:nth-child(1) {
                transform: rotate(45deg) translate(5px, 5px);
            }
            
            .mobile-menu-btn.active span:nth-child(2) {
                opacity: 0;
            }
            
            .mobile-menu-btn.active span:nth-child(3) {
                transform: rotate(-45deg) translate(5px, -5px);
            }
            
            .content-wrapper {
                @apply pl-0;
            }
            
            @media (min-width: 768px) {
                #main-nav {
                    transform: translateX(0);
                    @apply relative h-auto w-auto bg-transparent text-gray-800 z-auto;
                }
                
                .content-wrapper {
                    @apply pl-64;
                }
            }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <header class="bg-blue-600 text-white shadow-md">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <h1 class="text-xl font-bold">
                <span class="full-title"><?= htmlspecialchars($ammdrS) ?></span>
                <span class="short-title"><?= htmlspecialchars($ammdrS_short) ?></span>
            </h1>
            <div class="mobile-menu-btn md:hidden">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </header>
	
	<div class="flex h-screen">
    <!-- Навигация - фиксированная -->
    <nav id="main-nav" class="md:block fixed h-full overflow-y-auto w-64 bg-gray-800 text-white z-50">
    <!--div class="flex">
        <nav id="main-nav" class="md:block"-->
            <div class="p-4 md:p-6">
                <div id="nav-controls" class="mb-6">
                    <h1 class="text-xl font-bold text-white md:text-gray-800"><?= $menu_name; ?></h1>
                </div>
                <div class="space-y-2">
                    <?php foreach ($menuItems as $name => $item): ?>
                        <div class="menu-item">
                            <a href="#<?= htmlspecialchars($name) ?>" 
                               class="block px-3 py-2 rounded hover:bg-blue-700 hover:text-white md:hover:bg-blue-100 md:hover:text-blue-800 transition">
                                <?= htmlspecialchars($name) ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </nav>

		<main class="flex-1 overflow-auto"> 
			<div class="mx-auto w-full  sm:px-6 md:px-8 lg:px-8 max-w-7xl">
				<div class="container mx-auto sm:px-6 md:px-8 py-6 w-full">
                    <div class="bg-white rounded-lg shadow-md md:p-4 sm:py-4 sm:px-0">
                        <h1 class="text-3xl font-bold text-gray-800 mb-4"><?= $site_name; ?></h1>
                        <p class="text-gray-600 mb-6"><?= $site_intro; ?></p>
                        
                        <?php foreach ($menuItems as $name => $item): ?>
    <section id="<?= htmlspecialchars($name) ?>" class="mb-12 p-6 rounded-xl shadow-lg bg-gradient-to-br from-white to-gray-50 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
        <!-- Заголовок секции с градиентом -->
        <div class="mb-6 p-4 rounded-lg bg-gradient-to-r from-blue-500 to-purple-600">
            <h1 class="text-3xl font-bold text-white">
                <?= $menu_name; ?>
                <a href="<?= htmlspecialchars($name) ?>/ammdr.php" class="hover:text-blue-200 transition-colors duration-200">
                    <?= htmlspecialchars($name) ?>
                </a>
            </h1>
        </div>
        
        <?php if ($item['type'] === 'directory'): ?>
            <!-- Контент директории -->
            <div class="directory-content bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-inner border border-gray-200/50">
                <?php if (isset($item['readme'])): ?>
                    <!-- Блок с содержанием -->
                    <div class="mb-4">
                        <div class="flex items-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-800">СОДЕРЖАНИЕ</h3>
                        </div>
                        
                        <div class="directory-preview bg-gray-50/50 p-5 rounded-lg mb-5 text-gray-700 border-l-4 border-blue-400 pl-4">
                            <?= renderMarkdown($item['preview'] ?? 'Нет доступного описания') ?>
                        </div>
                        
                        <a href="<?= htmlspecialchars($name) ?>/ammdr.php?md=README.md" 
                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-400 to-blue-600 text-white rounded-lg hover:from-blue-500 hover:to-blue-700 transition-all duration-300 shadow hover:shadow-md">
                            <span>ЧИТАТЬ ВСЕ</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                <?php endif; ?>
                
                <!-- Список файлов -->
                <div class="mt-8">
                    <div class="flex items-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-800"><?= $files; ?></h3>
                    </div>
                    
                    <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        <?php foreach ($item['children'] as $childName => $child): ?>
                            <?php if ($child['type'] === 'file'): ?>
                                <li>
                                    <a href="<?= htmlspecialchars($name) ?>/ammdr.php?md=<?= htmlspecialchars($childName) ?>" 
                                       class="flex items-center p-3 bg-white border border-gray-200 rounded-lg hover:bg-green-50 hover:border-green-200 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="text-green-600 hover:text-green-800 truncate">
                                            <?= htmlspecialchars($childName) ?>
                                        </span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php else: ?>
            <!-- Одиночный файл -->
            <div class="file-preview bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-inner border border-gray-200/50">
                <div class="flex items-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-800">СОДЕРЖАНИЕ ФАЙЛА</h3>
                </div>
                
                <div class="prose max-w-none bg-gray-50/50 p-5 rounded-lg mb-5 text-gray-700 border-l-4 border-green-400 pl-4">
                    <?= renderMarkdown($item['preview'] ?? 'Нет доступного описания') ?>
                </div>
                
                <a href="./ammdr.php?md=<?= htmlspecialchars($item['path']) ?>" 
                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-400 to-green-600 text-white rounded-lg hover:from-green-500 hover:to-green-700 transition-all duration-300 shadow hover:shadow-md">
                    <span>ПОСМОТРЕТЬ ПОЛНОСТЬЮ</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        <?php endif; ?>
        
        <!-- Декоративный элемент внизу секции -->
        <div class="mt-6 pt-4 border-t border-gray-200/50 flex justify-end">
            <span class="text-xs text-gray-400">Последнее обновление: <?= date('d.m.Y H:i') ?></span>
        </div>
    </section>
<?php endforeach; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Меню
        $('.mobile-menu-btn').click(function() {
            $(this).toggleClass('active');
            $('#main-nav').toggleClass('active');
        });
        
        // Закрытие меню при клике вне его области
        $(document).click(function(e) {
            if (!$(e.target).closest('#main-nav').length && !$(e.target).closest('.mobile-menu-btn').length) {
                $('.mobile-menu-btn').removeClass('active');
                $('#main-nav').removeClass('active');
            }
        });
    });
    </script>
</body>
</html>
