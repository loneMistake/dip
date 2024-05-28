<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "licony";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$target_dir = "uploads/";
if (!file_exists($target_dir)) {
    if (mkdir($target_dir, 0777, true)) {
        echo "Папка 'uploads' успешно создана.";
    } else {
        die("Ошибка при создании папки 'uploads'.");
    }
}

$id_user = $_SESSION['user_id'];

$fieldsToUpdate = array(); // Массив для хранения полей, которые нужно обновить

if (!empty($_POST['email'])) {
    $email = $_POST['email'];
    $fieldsToUpdate[] = "email='$email'";
}

if (!empty($_POST['phoneNumber'])) {
    $phoneNumber = $_POST['phoneNumber'];
    $fieldsToUpdate[] = "phoneNumber='$phoneNumber'";
}

if (!empty($_POST['password'])) {
    $password = $_POST['password'];
    $fieldsToUpdate[] = "password='$password'";
}

$avatar = '';
if (!empty($_FILES["avatar"]["name"])) {
    $target_file = $target_dir . basename($_FILES["avatar"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Проверяем, является ли файл изображением
    $check = getimagesize($_FILES["avatar"]["tmp_name"]);
    if ($check !== false) {
        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
            echo "Файл " . htmlspecialchars(basename($_FILES["avatar"]["name"])) . " был загружен.<br>";
            $avatar = $target_file;
            $fieldsToUpdate[] = "avatar='$avatar'";
        } else {
            echo "Ошибка при загрузке файла.";
        }
    } else {
        echo "Файл не является изображением.";
    }
}


if (!empty($fieldsToUpdate)) {
    $updateFields = implode(", ", $fieldsToUpdate);
    $sql = "UPDATE users SET $updateFields WHERE id_user='$id_user'";

    if ($conn->query($sql) === TRUE) {
        echo "Данные успешно обновлены.";
    } else {
        echo "Ошибка: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Нет данных для обновления.";
}

$conn->close();
?>