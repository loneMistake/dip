<!DOCTYPE html>
<html>
<head>
  <title>Объявление о продаже/обмене книги</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../css/home.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<nav class="navbar navbar-expand-lg navbar-dark p-3 " id="headerNav">
      <div class="container-fluid">
        <a class="navbar-brand d-block d-lg-none" href="#">
          <img src="../image/logo.png" height="80" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class=" collapse navbar-collapse" id="navbarNavDropdown">
          <ul class="navbar-nav mx-auto ">
            <li class="nav-item">
              <a class="nav-link mx-2 active" aria-current="page" href="home.php">Главная</a>
            </li>
            <li class="nav-item d-none d-lg-block">
              <a class="nav-link mx-2" href="#">
                <img src="../image/logo.png" height="80" />
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link mx-2" href="profile.php">Профиль</a>
            </li>
            <li class="nav-item dropdown">
            </li>
          </ul>
        </div>
      </div>
    </nav>
<body>
<div class="top__left">
  <strong class="top__title"><h1 class="top">Все объявления</h1></strong>
  <a href="#" class="top__search"id="editDataBtn" data-bs-toggle="modal" data-bs-target="#editDataModal">Создать объявление</a>
</div>


<?php
session_start(); 
if (isset($_SESSION['nickname'])) {
    $nickname = htmlspecialchars($_SESSION['nickname']);
} else {
    $nickname = "anonymous";
}
if (isset($_GET['nickname']) && isset($_GET['id_user'])) {
    $profileNickname = $_GET['nickname'];
    $id_user = $_GET['id_user']; // Изменено с $_POST на $_GET
} 
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "licony";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}
$sql = "SELECT book.*, users.nickname, users.phoneNumber, users.id_user 
        FROM book 
        INNER JOIN users ON book.nickname = users.nickname";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    echo '<div class="card-container">';
    while ($row = $result->fetch_assoc()) {
        $bookTitle = $row['bookTitle'];
        $bookAuthor = $row['bookAuthor'];
        $bookDescription = $row['bookDescription'];
        $bookPrice = $row['bookPrice'];
        $bookImage = $row['bookImage'];
        $exchangePossible = $row['exchangePossible'];
        $nickname = htmlspecialchars($row['nickname']);
        $phoneNumber = htmlspecialchars($row['phoneNumber']);
        $id_user = htmlspecialchars($row['id_user']); // Получаем id_user из базы данных

        echo '<div class="card1">';
        echo '<div class="image-container">';
        echo '<img class="card-img-top " width="300px" src="' . $bookImage .'">';
        echo '</div>'; 
        echo '<h2 class="top12">' . $bookTitle . ' - ' . $bookAuthor . '</h2>';
        echo '<p class="top12"><strong>Продавец:</strong> <a href="profileG.php?nickname=' . urlencode($nickname) . '&amp;id_user=' . $id_user . '">' . $nickname . '</a></p>';
        echo '<p class="top12"><strong>Описание:</strong> ' . $bookDescription . '</p>';
        echo '<p class="top12"><strong> ' . $bookPrice . 'BYN</strong></p>';
        echo '<p class="top12"><strong>Обмен:</strong> ' . ($exchangePossible ? "Да" : "Нет") . '</p>'; 
        echo '<div class="button-container">';
        echo '<a href="#" class=" delete-button top__search" onclick="showPhoneNumber(\''.$phoneNumber.'\')">Связаться с продавцом</a>'; 
        echo '</div>';   
        echo '</div>';
    }
    echo '</div>';
}
$conn->close();
?>

<div class="modal fade" id="editDataModal" tabindex="-1" aria-labelledby="editDataModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content1">
            <div class="modal-header">
                <h5 class="modal-title" id="editDataModalLabel">Редактировать данные</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form action="create_ad.php" class="formUpload" method="post" enctype="multipart/form-data">
            <input type="text" id="bookTitle" name="bookTitle" placeholder="Название книги"><br>
            <input type="text" id="bookAuthor" name="bookAuthor" placeholder="Автор книги"><br>
            <textarea id="bookDescription" name="bookDescription" placeholder="Описание книги"></textarea><br>
            <input type="text" id="bookPrice" name="bookPrice" placeholder="Цена книги:"><br>
            
            <label for="bookImage" class="input__file-button">
            <input type="file" class="input input__file" name="bookImage" id="bookImage">
            <span class="input__file-button-text">Изображение книги</span>
            </label>

            <label for="exchangePossible">Обмен возможен:</label><br>
            <select id="exchangePossible" name="exchangePossible">
                <option value="1">Да</option>
                <option value="0">Нет</option>
            </select><br><br>
            <input type="submit" class="top__search" value="Создать">
</form>
            </div>

        </div>
    </div>
</div>

<!-- Модальное окно -->
<div id="phoneNumberModal" class="modal">
  <!-- Содержимое модального окна -->
  <div class="modal-content">
      <span class="close" onclick="closePhoneNumberModal()">&times;</span>

      <p id="phoneNumber"></p>
  </div>
</div>

<script>
// Функция для открытия модального окна для добавления информации
function openModal() {
  document.getElementById("myModal").style.display = "block";
}

// Функция для закрытия модального окна для добавления информации
function closeModal() {
  document.getElementById("myModal").style.display = "none";
}

// Функция для открытия модального окна для отображения номера телефона
function openPhoneNumberModal() {
  document.getElementById("phoneNumberModal").style.display = "block";
}

// Функция для закрытия модального окна для отображения номера телефона
function closePhoneNumberModal() {
  document.getElementById("phoneNumberModal").style.display = "none";
}

// Функция для отображения номера телефона продавца в модальном окне
function showPhoneNumber(phoneNumber) {
  document.getElementById("phoneNumber").innerText = "Номер телефона продавца: " + phoneNumber;
  openPhoneNumberModal(); // Открываем модальное окно для отображения номера телефона
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
