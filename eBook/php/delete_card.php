<?php
session_start();

// Подключение к базе данных (замените данными вашей БД)
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "licony";

// Создаем подключение к базе данных
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверяем соединение
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Проверяем, был ли передан идентификатор карточки
if (isset($_POST['id_book'])) {
    // Получаем идентификатор карточки из запроса
    $id_book = $_POST['id_book'];

    // Подготавливаем SQL-запрос на удаление карточки
    $sql = "DELETE FROM book WHERE id_book = '$id_book'";

    // Выполняем SQL-запрос
    if ($conn->query($sql) === TRUE) {
        // Если удаление прошло успешно, возвращаем успешный статус
        echo "success";
    } else {
        // Если произошла ошибка при выполнении запроса, возвращаем сообщение об ошибке
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    // Если идентификатор карточки не был передан, возвращаем сообщение об ошибке
    echo "No card id provided";
}

// Закрываем соединение с базой данных
$conn->close();
?>
