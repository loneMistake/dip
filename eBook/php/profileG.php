<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль пользователя</title>
    <link rel="stylesheet" href="../css/profileG.css">
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

<h1 class="top">Профиль пользователя</h1>
<div class="containerProfile">
    <div class="profile_border">  
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

$nickname = $_GET['nickname']; // Получение значения параметра nickname из URL
$id_user = $_GET['id_user']; // Получение значения параметра id_user из URL

$query = "SELECT * FROM users WHERE nickname = '$nickname'";
$result = mysqli_query($conn, $query);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nickname = $row['nickname'];
    $email = $row['email'];
    $phoneNumber = $row['phoneNumber'];
    $avatar = $row['avatar'];
    echo '<div class="profile_container">';
    echo '<img src="' . $avatar . '" alt="Аватар" class="avatar1">';
    echo '<p class="nickname"><strong> ' . $nickname . '</strong></p>';
    echo '<p class="down"><strong>Email:</strong> ' . $email . '</p>';
    echo '<p class="down"><strong>Номер телефона:</strong> ' . $phoneNumber . '</p>';
    echo '</div>';
} else {
    echo "Пользователь не найден.";
}
$conn->close();
?>
</div>
</div>
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
$sql_user_books = "SELECT bookTitle, bookAuthor, bookDescription, bookPrice, nickname, exchangePossible, bookImage FROM book WHERE nickname = '$nickname'";
$result_user_books = $conn->query($sql_user_books);

if ($result_user_books->num_rows > 0) {
    echo '<strong><h1 class="down2">Объявления пользователя</h1></strong>';
    echo '<div class="card-container down">';
    while ($row_book = $result_user_books->fetch_assoc()) {
        $bookTitle = $row_book['bookTitle'];
        $bookAuthor = $row_book['bookAuthor'];
        $bookDescription = $row_book['bookDescription'];
        $bookPrice = $row_book['bookPrice'];
        $bookImage = $row_book['bookImage'];
        $exchangePossible = $row_book['exchangePossible'];
        echo '<div class="card1">';
        echo '<div class="image-container">';
        echo '<img class="card-img-top" width="300px" class="top12" src="' . $bookImage . '">';
        echo '</div>';
        echo '<h2 class="top12">' . $bookTitle . ' - ' . $bookAuthor . '</h2>';
        echo '<p class="top12"><strong>Описание:</strong> ' . $bookDescription . '</p>';
        echo '<p class="top12"><strong> ' . $bookPrice . 'BYN</strong></p>';
        echo '<p class="top12"><strong>Обмен:</strong> ' . ($exchangePossible ? "Да" : "Нет") . '</p>';
        echo '</div>';
    }
    echo '</div>'; 
}

$id_user = $_GET['id_user']; // Идентификатор пользователя профиля
$sender_id = $_SESSION['user_id']; // Идентификатор текущего пользователя

// Извлекаем отзывы для пользователя
$sql = "SELECT o.otziv, o.date, u.nickname, u.avatar 
        FROM otziv o 
        JOIN users u ON o.id_user = u.id_user 
        WHERE o.recipient_id = '$id_user' 
        ORDER BY o.date DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<strong><h1 class="down2">Отзывы пользователя</h1></strong>';
    echo "<div class='review_container'>";
    while ($row = $result->fetch_assoc()) {
        $otziv = $row['otziv'];
        $date = date("d, F, Y", strtotime($row['date']));
        $nickname = $row['nickname'];
        $avatar = $row['avatar'];
        echo "<div class='review'>
                <div class='review-header'>
                    <img src='$avatar' alt='Аватар' class='avatar'>
                    <div class='user-info'>
                        <span class='nickname'>$nickname</span>
                        <span class='date'>$date</span>
                    </div>
                </div>
                <div class='review-content'>
                    <p>$otziv</p>
                </div>
              </div>";
    }
    echo "</div>";

    // Проверка, если id_user и sender_id одинаковы, не показывать форму
    if ($id_user != $sender_id) {
        echo "<form action='process_review.php' method='POST'>
                <input type='hidden' name='sender_id' value='$sender_id'>
                <input type='hidden' name='recipient_id' value='$id_user'>
                <textarea name='otziv' placeholder='Введите ваш отзыв' required></textarea>
                <input type='submit' class='top__search' value='Отправить отзыв'>
              </form>";
    }
}

$conn->close();
?>


<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p>Вы уверены, что хотите удалить эту карточку?</p>
        <button id="confirmDelete">Да, удалить</button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
