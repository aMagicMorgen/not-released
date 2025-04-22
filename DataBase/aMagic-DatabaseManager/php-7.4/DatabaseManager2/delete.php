<?php
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
