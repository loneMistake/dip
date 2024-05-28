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

// Функция для загрузки сообщений чата
function loadMessages($conn) {
    $output = '';
    $sql = "SELECT * FROM chat ORDER BY id DESC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $output .= '<div><strong>' . $row['sender'] . '</strong>: ' . $row['message'] . '</div>';
        }
    } else {
        $output .= '<div>Нет сообщений</div>';
    }
    return $output;
}

// Функция для отправки сообщений в чат
function sendMessage($conn, $sender, $message) {
    $sql = "INSERT INTO chat (sender, message) VALUES ('$sender', '$message')";
    if ($conn->query($sql) === TRUE) {
        return loadMessages($conn);
    } else {
        return "Ошибка: " . $sql . "<br>" . $conn->error;
    }
}

// Проверяем, какое действие было отправлено через AJAX
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action == 'load') {
        echo loadMessages($conn);
    } elseif ($action == 'send') {
        $sender = $_SESSION['user_id'];
        $message = $_POST['message'];
        echo sendMessage($conn, $sender, $message);
    }
}

$conn->close();
?>
