<?php
session_start();

// Подключение к базе данных
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "licony";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Получение данных из формы
$sender_id = $_POST['sender_id'];
$recipient_id = $_POST['recipient_id'];
$otziv = $_POST['otziv'];
$date = date('Y-m-d H:i:s');

// Вставка отзыва в базу данных
$sql = "INSERT INTO otziv (id_user, recipient_id, otziv, date) VALUES ('$sender_id', '$recipient_id', '$otziv', '$date')";

if ($conn->query($sql) === TRUE) {
    echo "Отзыв успешно добавлен!";
} else {
    echo "Ошибка: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
