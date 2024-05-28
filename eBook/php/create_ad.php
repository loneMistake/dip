<?php
session_start(); // Начинаем сессию

// Проверяем, установлен ли nickname в сессии
if(isset($_SESSION['nickname'])) {
    // Получаем nickname из сессии
    $nickname = $_SESSION['nickname'];
} else {
    // Если nickname не установлен в сессии, устанавливаем значение по умолчанию
    $nickname = "anonymous";
}

// Подключаемся к базе данных
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "licony";

// Создаем подключение
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверяем подключение
if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}

// Получаем данные из формы
$bookTitle = $_POST['bookTitle'];
$bookAuthor = $_POST['bookAuthor'];
$bookDescription = $_POST['bookDescription'];
$bookPrice = $_POST['bookPrice'];
$exchangePossible = $_POST['exchangePossible'];

// Получаем информацию о загруженном файле
$bookImage = $_FILES['bookImage'];

// Путь для сохранения файла
$uploadDir = 'uploads/';

// Получаем расширение файла
$extension = pathinfo($bookImage['name'], PATHINFO_EXTENSION);

// Генерируем уникальное имя файла
$fileName = 'image' . rand(1000, 9999) . '.' . $extension; // Генерируем случайное число и добавляем расширение

// Путь к файлу на сервере
$uploadFile = $uploadDir . $fileName;

// Сохраняем файл на сервере
if (move_uploaded_file($bookImage['tmp_name'], $uploadFile)) {
    echo "Файл успешно загружен.";
} else {
    echo "Ошибка при загрузке файла.";
}

// SQL-запрос для вставки данных в таблицу book
$sql = "INSERT INTO book (bookTitle, bookAuthor, bookDescription, bookPrice, bookImage, nickname, exchangePossible) 
        VALUES ('$bookTitle', '$bookAuthor', '$bookDescription', '$bookPrice', '$uploadFile', '$nickname', '$exchangePossible')";

// Выполняем SQL-запрос
if ($conn->query($sql) === TRUE) {
    echo "Новое объявление успешно создано";
} else {
    echo "Ошибка: " . $sql . "<br>" . $conn->error;
}

// Закрываем подключение к базе данных
$conn->close();
?>
