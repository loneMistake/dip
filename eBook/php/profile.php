<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль пользователя</title>
    <link rel="stylesheet" href="../css/profile.css">
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
<div class="containerProfile">
    <div class="profile_border">  
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
        $id_user = $_SESSION['user_id']; 
        
        $sql = "SELECT * FROM users WHERE id_user = '$id_user'";
        $result = $conn->query($sql);
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
        <div class="button-container">
            <a class="top__search" href="update.php" id="editDataBtn" data-bs-toggle="modal" data-bs-target="#editDataModal">Редактировать данные</a>
            <a class="top__search" href="logout.php">Выйти</a>
        </div>
    </div>
</div>







<div class="modal fade" id="editDataModal" tabindex="-1" aria-labelledby="editDataModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDataModalLabel">Редактировать данные</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form action="upload.php" class="formUpload"method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <input type="email" name="email" id="email" class="inputRedact" placeholder="Email" ><br><br>
            <input type="text" name="phoneNumber" id="phoneNumber" placeholder="Номер телефона"  pattern="[0-9]{12}" title="Номер телефона должен состоять из 12 цифр"><br><br>
            <label for="avatar" class="input__file-button">
            <input type="file" class="input input__file" name="avatar" id="avatar">
            <span class="input__file-button-text">Выберите аватар</span>
            </label>
            <input type="password" name="password" id="password" placeholder="Пароль" pattern="^[a-zA-Z0-9]{8,}$" title="Пароль должен содержать минимум 8 символов, состоящих из букв и цифр" minlength="8"><br><br>
            <button type="submit" class="top__search">Обновить</button>
        </form>
            </div>

        </div>
    </div>
</div>

<script>
    // Получаем ссылку на кнопку "Создать объявление"
    var createAdButton = document.querySelector('.top__search');

    // Получаем ссылку на модальное окно
    var modal = document.querySelector('#editDataModal');

    // Добавляем обработчик события клика на кнопку
    createAdButton.addEventListener('click', function() {
        // Открываем модальное окно
        modal.classList.add('show');
        modal.style.display = 'block';
    });
</script>


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
$id_user = $_SESSION['user_id']; 
$sql = "SELECT * FROM users WHERE id_user = '$id_user'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nickname = $row['nickname'];
    $email = $row['email'];
    $password = $row['password'];
} else {
    echo "Пользователь не найден.";
}
$sql_user_books = "SELECT bookTitle,bookAuthor,bookDescription,bookPrice,nickname,exchangePossible,bookImage, id_book FROM book WHERE nickname = '$nickname'";
$result_user_books = $conn->query($sql_user_books);
if ($result_user_books->num_rows > 0) {
    echo '<strong><h1 class="down2">Ваши объявления</h1></strong>';
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
        echo '<img class="card-img-top" width="300px" src="' . $bookImage . '">';
        echo '</div>';
        echo '<h2 class="top12">' . $bookTitle . ' - ' . $bookAuthor . '</h2>';
        echo '<p class="top12"><strong>Описание:</strong> ' . $bookDescription . '</p>';
        echo '<p class="top12"><strong> ' . $bookPrice . 'BYN</strong></p>';
        echo '<p class="top12"><strong>Обмен:</strong> ' . ($exchangePossible ? "Да" : "Нет") . '</p>';
        echo '<div class="button-container">';
        echo '<a class="top__search delete-button" href="#" data-card-id="' . $row_book['id_book'] . '">Удалить</a>';
        echo '</div>';
        echo '</div>';
    }
    echo '</div>'; 
}
$conn->close();
?>


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
if (!isset($_SESSION['user_id'])) {
    die("Пользователь не авторизован.");
}
$sender_id = $_SESSION['user_id']; 
$sql = "SELECT o.otziv, o.date, u.nickname, u.avatar 
        FROM otziv o 
        JOIN users u ON o.id_user = u.id_user 
        WHERE o.recipient_id = '$sender_id' 
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
        echo" <div class='review'>
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

</body>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var deleteButtons = document.querySelectorAll('.delete-button');
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault(); 
            var cardId = this.getAttribute('data-card-id');
            var confirmDelete = confirm('Вы уверены, что хотите удалить эту карточку?');
            if (confirmDelete) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_card.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            var response = xhr.responseText;
                            if (response === 'success') {
                                var card = button.closest('.card');
                                if (card) {
                                    card.remove(); // Удаление карточки из DOM
                                    $('#myModal').modal('hide'); // Закрытие модального окна
                                }
                            } else {
                                alert('Ошибка удаления карточки.');
                            }
                        } else {
                            alert('Ошибка удаления карточки.');
                        }
                    }
                };
                xhr.send('id_book=' + cardId);
            }
        });
    });

    // Добавляем обработчик события для кнопки подтверждения удаления в модальном окне
    var confirmDeleteButton = document.getElementById('confirmDelete');
    confirmDeleteButton.addEventListener('click', function() {
        var cardId = confirmDeleteButton.getAttribute('data-card-id');
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'delete_card.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var response = xhr.responseText;
                    if (response === 'success') {
                        var card = document.querySelector('.card[data-card-id="' + cardId + '"]');
                        if (card) {
                            card.remove(); // Удаление карточки из DOM
                        }
                        $('#myModal').modal('hide'); // Закрытие модального окна
                    } else {
                        alert('Ошибка удаления карточки.');
                    }
                } else {
                    alert('Ошибка удаления карточки.');
                }
            }
        };
        xhr.send('id_book=' + cardId);
    });
});
document.getElementById("editDataBtn").addEventListener("click", function(event) {
    // Очистить поля формы перед открытием модального окна
    document.getElementById("editDataForm").reset();
});
</script>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</html>
