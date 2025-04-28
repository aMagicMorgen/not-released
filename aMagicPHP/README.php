php у меня есть php код в одном файле, который сам создает структуру папок и файлов для нового сайта. Это aMagic.php
После первого запуска происходит магия, затем после запуска index.php происходит перемещение файла  aMagic.php в lib папку с библиотеками.
Изучи этот код очень тщательно. И напиши документацию.
Пример config.php тоже реализован так, что все настройки по выводу уже в нем.
После подключения его в index.php не в начале файла, а в конце он уже собирает все данные и только выводит html на экран.
Вот aMagic.php

```
<?php	
//aMagic_0.1
error_reporting(E_ALL);
ini_set('display_errors','on');
mb_internal_encoding('UTF-8');
//Подключаем aMagicDocGenerator.php для генерации документов
#$root = $_SERVER['SERVER_NAME'];
#$aMagicLib = 'lib/aMagic/';
require_once 'MDS.php';


$arrHtmls = ['lang', 'title', 'meta', 'link', 'my_style', 'script', 'style', 'scriptdown'];
	foreach ($arrHtmls as $a){
		$html[$a] = '';
	}
	foreach ($arrHtmls as $a){
		if(isset ($$a))	{
		if( is_array($$a)) $html[$a] .= implode ('', $$a);	
		else $html[$a] .= $$a;
		}
	}

function html($html){
		$pattern =     ['/>/', '/\s+/', '/> </', '#<!--.*-- >#sUi', '#\/\*.*\*\/#sUi', '/= \>/' ];
		$replacement = [' >',  ' ',     '>|<',   '',                 '', '=>'];
		$replacement2 =[' >',  ' ',     '><',    '',                 '', '=>'];
		if (is_array($html) !== false) {
			$html = implode("", array_unique(explode('|', preg_replace($pattern, $replacement, implode('|', array_map('trim', $html))))));
		} else {$html = preg_replace($pattern, $replacement2, trim($html));
			$pattern = ['<style ></style >', '</style ><style >', '<style ><style', '</style ></style >', '</header ></header >', '</footer ></footer >'];
			$replacement = ['',              '',                  '<style',         '</style >',          '</header >',            '</footer >'];
			$html = str_replace($pattern, $replacement, $html);
		}
	return ($html);
	}
	
function tags($html, $blocks = null) {
	$html = html("\xEF\xBB\xBF".$html);
	$dom = new DOMDocument();
    @$dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
	if ($blocks == null OR $blocks == '')$blocks = ['head', 'header', 'footer', 'body'];
	else $blocks = ['amagic', $blocks];
    $tags = ['title', 'meta', 'link', 'style', 'script'];
	// Инициализация массива для хранения результатов
	$results = [];
	
	$blokTags = $dom->getElementsByTagName('html');
		if ($blokTags->length > 0) {
			//$blokElement = $blokTags->item(0);
	$results['lang'] = $blokTags->item(0)->getAttribute('lang');
		}
	
	foreach ($blocks as $block){
		// Получаем элемент
		$blokTags = $dom->getElementsByTagName($block);
		if ($blokTags->length > 0) {
			$blokElement = $blokTags->item(0);
			foreach ($tags as $tag) {
				$arr = $tag;
				if($block !== 'head' AND $tag == 'script') $arr = 'scriptdown';
				// Проверяем, существует ли хотя бы один элемент с данным тегом
				if ($blokElement->getElementsByTagName($tag)->length > 0) {
					// Сначала собираем все найденные теги
					foreach ($blokElement->getElementsByTagName($tag) as $element) {
						$results[$arr][] = $dom->saveHTML($element);
					}
					// Удаляем теги из
					foreach ($results[$arr] as $tagHtml) {
						foreach ($blokElement->getElementsByTagName($tag) as $element) {
							if ($dom->saveHTML($element) === $tagHtml) {
								$element->parentNode->removeChild($element);
								break; // Выходим из цикла после удаления
							}
						}
					}
					if($tag == 'title') $results[$arr] = $element->textContent;
					
				}
			}
	// Удаляем теги 'header', 'footer' из <body>
			if($block == 'body'){
			$blocks = ['header', 'footer'];
				foreach ($blocks as $tag) {
				// Удаление тегов с помощью DOM
					while ($element = $dom->getElementsByTagName($tag)->item(0))
					$element->parentNode->removeChild($element);
				}
			}
			if($block !== 'amagic' AND $block !== 'head' AND $block !== 'title') {
			$attributes_arr = [];
				foreach ($blokElement->attributes as $attr) $attributes_arr[$attr->nodeName] = $attr->nodeValue;
			$results["attributes_$block"] = '';
				foreach($attributes_arr as $name => $value) $results["attributes_$block"] .= "$name='$value' ";
			$results[$block] = '';
				foreach($blokElement->childNodes as $child) $results[$block] .= $dom ->saveHTML($child);
			}
		}
	}
	$tags[] = 'scriptdown';
	foreach ($tags as $tag){
		if (isset($results[$tag])AND is_array ($results[$tag])) $results[$tag] = implode("\n", array_unique($results[$tag]));
	}
    return $results;
}

$configuration = [['lang' => 'ru', 
'title' => 'назначьте $title в этом файле',
'dirPage' => 'pages/',
'attributes_body' => '',
'sections' => 'section01',
'pageName' => 'index'], 
['config' => 'config',
'pageName' => 'index',
'head' => 'head',
'header' => 'header', 
'footer' => 'footer'
]];
//Проверяем существование переменных $lang, $title, $dirPage, $attributes_body, $sections
foreach ($configuration[0] as $key => $value){if (!isset($$key) OR $$key == '') $$key = $value;}

//Массив папок которые нужно создать
$aMagicFolders = [
$dirPage,
'lib/aMagic',
'static/css',
'static/scss',
'static/js',
'static/png',
'static/jpg'
];
//Функция для создания папок если они не существуют
foreach ($aMagicFolders as $folder) {
       // Проверка наличия папки для сохранения
        if (!is_dir($folder)) mkdir($folder, 0777, true);
		if ($folder == 'static/css' AND !file_exists('static/css/style.css'))file_put_contents('static/css/style.css', '');
		if ($folder == 'static/js' AND !file_exists('static/js/js.js'))file_put_contents('static/js/js.js', '');
    }
//Преобразуем $sections в строку для записи
if(is_array($sections) !== false){
$section01 = "['".implode("', '", $sections)."']";
}else $section01 = $sections;

$contents = ["\$root = __DIR__;\n\$aMagic = 'lib/aMagic/aMagic.php';//ПОКА НЕ МЕНЯТЬ
#\$dirPage = '';//'pages/';//назначается если все папки для страниц сайта будут в другой отдельной папке.
#\$attributes_body = '';//Атрибуты для тега body определить \$attributes_body если они на всех страницах одинаковые (переназначить можно на любой странице)\n
//Для переноса файла aMagic.php при первом запуске
if (!file_exists('lib/aMagic/aMagic.php')) {
	if (!is_dir('lib/aMagic/')) mkdir('lib/aMagic/', 0755, true); // Создаем директорию, если она не существует
	// Копируем файл
	if (copy('aMagic.php', 'lib/aMagic/aMagic.php')) {
		echo \"Файл успешно скопирован в 'lib/aMagic/aMagic.php'\";    
		// Удаляем оригинальный файл
		if (unlink('aMagic.php')) echo \"Библиотека aMagic.php перенесена в папку 'lib/aMagic/aMagic.php'\";
		else echo \"Не удалось удалить оригинальный файл 'aMagic.php'\";
	} else echo \"Не удалось скопировать файл 'aMagic.php'\";
}
include_once \$aMagic;\n#МЕНЮ можно здесь отключить\naMagicMenu();\necho \$aMagic;//выводит html в строку\n#echo \$aMagicF;//выводит форматированный html с отступами из строки
",
"
\$meta[] = \"\n<!--Эти теги уже есть в lib/aMagic.php-->
<!--meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<meta name='viewport'  content='width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0`>
<meta http-equiv=`X-UA-Compatible` content='ie=edge`-->\n\";
\$link[] = \"\n<!-- КАРТИНКА ДЛЯ ВКЛАДКИ -->
<link rel='icon' href='https://getbootstrap.com/docs/5.2/assets/img/favicons/favicon-16x16.png' sizes='16x16' type='image/png'>
<!-- ГЛАВНЫЙ css ДЛЯ ВСЕГО САЙТА -->\n<link rel='stylesheet' type='text/css' href=' https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css'>
<!-- ДРУГИЕ css ДЛЯ ВСЕГО САЙТА -->\n<!--link rel='stylesheet' type='text/css' href=''-->\n\";
//СВОЙ css ДЛЯ ВСЕГО САЙТА\n\$my_style[]  = \"\n<link rel='stylesheet' type='text/css' href='static/css/styles.css' >\n\";
//СКРИПТЫ В <head>\n\$script[] = \"\n<!-- jquery.min.js -->
<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js'></script>\n\";
//СВОЙ css ДЛЯ ВСЕГО САЙТА можно вставить без тэгов <style ></style >\n\$style[] = \"
body {\n#color: #000; /*Цвет текста: черный*/
  #background: #FFFFFF; /*Цвет фона: белый*/ 
  #word-wrap: break-word; /*Переносить слово, если не влезает: да*/
  #font-size: 100%; /*Размер текста: 100%*/
  #font-family: Verdana, Arial, Sans-Serif; /*Шрифты:Verdana, Arial, Sans-Serif*/\n}\n\";
//НИЖНИЙ СКРИПТ js ДЛЯ ВСЕГО САЙТА перед </body >\n\$scriptdown[] = \"
<script type='text/javascript' src='static/js/js.js' ></script >\n<!--script >\n\n</script -->
<script src='https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js' integrity='sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r' crossorigin='anonymous'></script>
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js' integrity='sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS' crossorigin='anonymous'></script>\n\";			
",
"
\$meta[] = \"\n<!--meta name='keywords' content='ключевые слова' -->\n\";
\$link[] = \"\n<!--link rel='stylesheet' type='text/css' href=''-->\n\";
\$my_style[]  = \"\n<!--link rel='stylesheet' type='text/css' href=''-->\n\";
\$script[] = \"\n<!--script type='text/javascript' src=''></script-->\n\";
\$style[] = \"\n\n\";
\$scriptdown[] = \"\n<!--script type='text/javascript' src='static/js/js.js'></script-->\n<!--script >\n\n</script -->\n\";
",
"
<?php\n//$pageName.php
\$pageName = pathinfo(__FILE__, PATHINFO_FILENAME);//название этой страницы
\$meta[] = \"\n<!--meta name='keywords' content='Ключевые слова' -->\n\";
\$title = '$pageName';//Напишите как будет называться страница на закладке в браузере
//СТРОКОЙ можно через запятую или с переносом строк\n\$sections = '$section01';
#\$pageName = 'page01';//название страницы, которую нужно создать\n
#\$attributes_body = ''; //подключить и назначить если не назначено в config.php
include 'config.php';\n#header('Location: '.\$pageName);//Включить если нужна переадресация на новую страницу
"];


//Проверяем существование переменных и создаем эти файлы если они не созданы
foreach ($configuration[1] as $key => $value){
	if (!isset($$key) OR $$key == '') {
		$content = "<?php\n//$value.php\n";
		$$key = $dirPage.$value;
		if($key == 'config'){$$key = $value; $content .= "include ('aMenu.php');\n" . $contents[0];
		}elseif($key == 'head')$content .= $contents[1];
		else $content .= $contents[2] . "#\$attributes_$value = \"\";//атрибуты для тега <$value >, например class = '$value'\n?>\n<!--html код для $dirPage$value.php-->\n<p>Сюда вставьте html код для $value в файл $dirPage$value.php<p>";
	}else {
		$$key = $pageName;
		$content = $contents[3];
	}
	if (!strpos($$key, '.')) $$key = $$key . '.php';
	if (!file_exists($$key)) file_put_contents($$key, $content);
}

//Создает в общей папку определенную в $dirPage папку для страницы
$dirPage = $dirPage . trim(explode('.', $pageName)[0]) . '/';
	if (!is_dir($dirPage. 'block')) mkdir($dirPage . 'block', 0777, true);

function aMagic($dirPage, $body, $aMagic){
	$attributes_header = $attributes_body = $attributes_footer = '';
	$html = $aMagic[0];
	$lang = @$html['lang'];
	$title = @$html['title'];
	$attributes_body = $body[0];
	foreach ($aMagic[1] as $a) {
		$a1 = pathinfo($a , PATHINFO_FILENAME); 
			// Подключаем файл
			ob_start(); // Начинаем буферизацию вывода
				include $a; // Включаем файл
	$arrHtmls = ['meta', 'link', 'my_style', 'script', 'style', 'scriptdown', 'mcss'];
	foreach ($arrHtmls as $a){
		if(isset ($$a))	{
			if( is_array($$a)) $html[$a] .= implode ('', $$a);
			else $html[$a] .= $$a;
		}
	}
		$$a1 = ob_get_clean(); // Получаем весь HTML-код из буфера
	
		}
		
	//если $body[1] это массив
	if (is_array($body[1]) !== false) $sections = $body[1];
	//если $body[1] это строка с ',' как разделитель
	elseif (strpos($body[1], ',') !== false) $sections = array_filter(array_map('trim', explode(',', $body[1])));
		//если $body[1] это строка с переносами строк "\n" как разделитель
	else $sections = array_filter(array_map('trim', explode("\n", $body[1])));
	$sections = array_filter($sections, function ($item){return $item[0] !== '#';});//strpos($item, '#') === false;
		$blocks = [];
		foreach ($sections as $section) {
			if($section[0] !== '<'){
				if (!strpos($section, '.')) $section = $section . '.php'; 
				$file = $dirPage . $section;
				
$content = "<?php\n//$file
\$meta[] = \"\n<!--meta name='keywords' content='ключевые слова' -->\n\";
\$link[] = \"\n<!--link rel='stylesheet' type='text/css' href=''-->\n\";
\$my_style[]  = \"\n<!--link rel='stylesheet' type='text/css' href=''-->\n\";
\$script[] = \"\n<!--script type='text/javascript' src=''></script-->\n\";
\$style[] = \"\n\n\";
\$scriptdown[] = \"\n<!--script type='text/javascript' src='static/js/js.js'></script-->\n<!--script >\n\n</script -->\n\";
?>\n<!--html код для $file-->\n<p>Сюда вставьте html код для $section в файл $dirPage$section<p>";
			if (!file_exists($file) !== false) file_put_contents($file, $content);
			// Подключаем файл
			ob_start(); // Начинаем буферизацию вывода
				include $file; // Включаем файл
			$arrHtmls = ['meta', 'link', 'my_style', 'script', 'style', 'scriptdown'];
	foreach ($arrHtmls as $a){
		if(isset ($$a))	{
			if( is_array($$a) !== false ) $html[$a] .= implode ('', $$a);
			else $html[$a] .= $$a;
		}
	}
			$section = ob_get_clean(); // Получаем весь HTML-код из буфера
			}//else $section = $section;
			$blocks[] = $section;
		}
	$body = implode ('', $blocks);
	$htmls = tags ('<body>'.$body.'</body>');
	$arrHtmls = ['meta', 'link', 'my_style', 'script', 'style', 'scriptdown', 'body'];
	foreach ($arrHtmls as $a){
		$html['body'] = '';
		if(isset($htmls[$a])) $html[$a] .= $htmls[$a];
		//if(array_key_exists($a, $htmls))$html[$a] .= $htmls[$a];
		}
		$body = $html['body'];
	//print_r ($body);
	/*$results = tags($html);
			foreach ($results as $key => $value){
				if ($key == 'main')$$key = $value;
				else $$key.'[]' =  $value;
			}
	*/
	
///// Конструктор выводит все в html
$head =	html(explode("\n", $html['meta'])) . html(explode("\n", $html['link'])) . html(explode("\n", $html['script'])) . html(explode("\n", $html['my_style']));
$style = html(explode("\n", str_replace(["><", ">\n</", "}"],[">\n<", "></", "}\n"], html($html['style']))));
$scriptdown =html(explode("\n", str_replace(["><", ">\n</"],[">\n<", "></"], html($html['scriptdown']))));

If(strpos($body, '<body') !== false OR strpos($header, '<body') !== false) $tag_body = '';
else $tag_body = '<body ' . $attributes_body .'>';
If(strpos($header, '<header') !== false) $tag_header = '';
else $tag_header = '<header ' . $attributes_header . '>';
If(strpos($footer, '<footer') !== false) $tag_footer = '';
else $tag_footer = '<footer ' . $attributes_footer . '>';

$html = "<!doctype html >
<html lang='$lang' >
<head >
	<title >$title</title >
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
	<meta name='viewport'  content='width=device-width, initial-scale=1'>
	<meta http-equiv='X-UA-Compatible' content='ie=edge'>" . 
	$head ."<style >" . $style . "</style >
</head >" .
$tag_body .
	$tag_header . $header .  "</header >" .
		$body.
	$tag_footer .  $footer.  "</footer >".
	$scriptdown .
"</body ></html >";
return html($html);
}
//Собираем все переменные и отправляем в aMagic();

$body = [$attributes_body, $sections];
$aMagic = [$html, [$head, $header, $footer]];
$aMagic = aMagic($dirPage, $body, $aMagic);//$aMagic назначена в config.php

// ФОРМАТИРОВАНИЕ по секциям $aMagicS
function formatS($html){
$html = str_replace (['<meta', '<head', '<link', '<script', '<style >', '</head', '<body', "</header >", "<footer", "</footer", "</body", "\n);", "$", "}", "}\n);", "\n;"], 
	["\n<meta", "\n<head", "\n<link", "\n<script", "\n<style >\n", "\n</head", "\n<body", "</header >\n", "\n<footer", "\n</footer", "\n</body", ");", "\n$", "}\n", "});\n", ";"], $html);
	return $html;
}
$aMagicS = formatS($aMagic);

// ФОРМАТИРОВАНИЕ Не обязательная Функция для форматирования строки html в форматированный текст с отступами
function formatHtml ($html){
	$tidy = new tidy();
	$options = ['indent' => true, 'output-xhtml' => true, 'wrap' => 300];
	$formattedHtml = $tidy->repairString($html, $options);
return $formattedHtml;
}
$aMagicF = formatHtml($aMagic);
//ЗАПИСЬ Созданых страниц в файл aPages.php
//$pageName = $pageName;
$page = "\n<li><a class='dropdown-item' href='$pageName' >$pageName</a></li>";//target='_blank'
// Дописываем содержимое в файл index.php
file_put_contents('aPages.php', $page, FILE_APPEND | LOCK_EX);
// Читаем содержимое файла aPages.php
$content = file_get_contents('aPages.php');
// Разделяем содержимое на массив строк
$lines = explode("\n", $content);
// Удаляем повторяющиеся строки
$uniqueLines = array_unique($lines);
// Объединяем массив строк обратно в текст с разделением через \n
$result = implode("\n", $uniqueLines);
// Записываем результат обратно в файл aPages.php
file_put_contents('aPages.php', $result);

//Для отобажения панели aMagic
function aMagicMenu(){
/*
return  '<div class="navbar navbar-expand-lg bg-body-tertiary" >
<a class="navbar-brand" href="./">ПАНЕЛЬ aMagic</a>
	<div class="container-fluid">
	<ul class="navbar-nav me-auto mb-2 mb-lg-0">
		<li class="nav-item dropdown">
		  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
			СОЗДАННЫЕ СТРАНИЦЫ
		  </a>
		  <ul class="dropdown-menu">'.
		  file_get_contents('aPages.php').
		  '</ul>
		</li>
	</ul>
	Cоздать : 
	<form class="d-flex">
			<input class="form-control me-2" type="search" name = "p" placeholder="Имя новой страницы" aria-label="Поиск">
			<input class="form-control me-2" type="search" name = "s" placeholder="Секции через запяту" aria-label="Секции">
			<button class="btn btn-outline-success" type="submit">СОЗДАТЬ</button>
		  </form>
	</div>
</div>
';
*/
return '<div class="mains-menu">
<li><a href="./">ПАНЕЛЬ aMagic</a></li>
<li>	Cоздать : 
<form>
<table><tbody><tr>
<td>
	<div class="placeholder-container">
			<input type="search" name = "p" placeholder="Имя новой страницы">
	</div>
</td><td>
	<div class="placeholder-container">
			<input type="search" name = "s" placeholder="Секции через запяту">
	</div>
</td><td>
			<button class="btn btn-primary" type="submit" style="float: right;">СОЗДАТЬ</button>
</td>
</tr></tbody></table>	
</form>
</li>
<li class="menu-children">
  <a href="#" >СОЗДАННЫЕ СТРАНИЦЫ </a>
  <ul>'.
  file_get_contents('aPages.php').
  '</ul>
</li>
	
</div>
';

}
$aMagicMenu = aMagicMenu();
```

ВОТ config.php

```
<?php
//config.php
include ('aMenu.php');
$root = __DIR__;
$aMagic = 'lib/aMagic/aMagic.php';//ПОКА НЕ МЕНЯТЬ
#$dirPage = '';//'pages/';//назначается если все папки для страниц сайта будут в другой отдельной папке.
#$attributes_body = '';//Атрибуты для тега body определить $attributes_body если они на всех страницах одинаковые (переназначить можно на любой странице)

//Для переноса файла aMagic.php при первом запуске
if (!file_exists('lib/aMagic/aMagic.php')) {
	if (!is_dir('lib/aMagic/')) mkdir('lib/aMagic/', 0755, true); // Создаем директорию, если она не существует
	// Копируем файл
	if (copy('aMagic.php', 'lib/aMagic/aMagic.php')) {
		echo "Файл успешно скопирован в 'lib/aMagic/aMagic.php'";    
		// Удаляем оригинальный файл
		if (unlink('aMagic.php')) echo "Библиотека aMagic.php перенесена в папку 'lib/aMagic/aMagic.php'";
		else echo "Не удалось удалить оригинальный файл 'aMagic.php'";
	} else echo "Не удалось скопировать файл 'aMagic.php'";
}
include_once $aMagic;

#МЕНЮ можно здесь отключить
aMagicMenu();
echo $aMagic;//выводит html в строку
#echo $aMagicF;//выводит форматированный html с отступами из строки
```
ВОТ эталонный index.php

  ```
  <?php
//index.php
$pageName = pathinfo(__FILE__, PATHINFO_FILENAME);//название этой страницы
$meta[] = "
<!--meta name='keywords' content='Ключевые слова' -->
";
$title = 'index';//Напишите как будет называться страница на закладке в браузере
//СТРОКОЙ можно через запятую или с переносом строк
$sections = 'section01';
#$pageName = 'page01';//название страницы, которую нужно создать

#$attributes_body = ''; //подключить и назначить если не назначено в config.php
include 'config.php';
#header('Location: '.$pageName);//Включить если нужна переадресация на новую страницу
```
