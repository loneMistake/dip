<?php
// Запускаем сессию
session_start();

// Удаляем все переменные сессии
session_unset();

// Уничтожаем сессию
session_destroy();

// Перенаправляем пользователя на страницу входа или на другую страницу
header("Location: register.php");
exit();
?>
