<?php
/////Для вывода ошибок на экран  ini_set('display_errors','on'); on || of
#print_r(function_exists('mb_internal_encoding')); //проверка 1-подключено, 0 - не подключено
/*
error_reporting(E_ALL);
ini_set('display_errors','on');
mb_internal_encoding('UTF-8');
*/
require_once 'DatabaseManager.php';

DatabaseManager::init('documents.sqlite', 'title=Заголовок,author=Автор');

if (isset($_GET['id'])) {
    try {
        DatabaseManager::deleteDocument((int)$_GET['id']);
        header("Location: table.php?deleted=".$_GET['id']);
    } catch (Exception $e) {
        die("Ошибка при удалении: " . $e->getMessage());
    }
} else {
    header("Location: table.php");
}
exit();
