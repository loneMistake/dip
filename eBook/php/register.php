<?php
// Проверка, был ли отправлен POST-запрос
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверяем, установлен ли ключ "nickname" и "password" в массиве POST
    if (isset($_POST["nickname"]) && isset($_POST["password"])) {
        // Создаем подключение к базе данных
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "licony";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Проверяем подключение
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Получаем данные из формы
        $nickname = $_POST["nickname"];
        $password = $_POST["password"];

        // Готовим запрос
        $sql = "SELECT * FROM users WHERE nickname = '$nickname' AND password = '$password'";

        // Выполняем запрос
        $result = $conn->query($sql);

        // Проверяем, найден ли пользователь
        if ($result->num_rows > 0) {
            // Аутентификация успешна
            // Получаем данные пользователя
            $user = $result->fetch_assoc();

            // Сохраняем информацию в куки
            setcookie("nickname", $user["nickname"], time() + 3600, "/"); // Куки будут действовать в течение 1 часа
            setcookie("id_user", $user["id_user"], time() + (86400 * 30), "/"); // Кука действительна в течение 30 дней

            // Перенаправляем на main.html
            header("Location: profile.php");
            exit();
        } else {
            // Неверный nickname или пароль
            echo "Invalid nickname or password.";
        }

        // Закрываем соединение с базой данных
        $conn->close();
    } else {
        echo "Nickname or password is not set in POST request.";
    }
}

// Создаем подключение к базе данных
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "licony";
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверяем подключение
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Проверяем, был ли отправлен POST-запрос
// Проверяем, был ли отправлен POST-запрос
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получаем данные из формы
    $nickname = $_POST['nickname'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phoneNumber']; // Новое поле для номера телефона
    $password = $_POST['password'];
    $avatar = "uploads/image1.jpg";
    // Проверяем, что все поля заполнены
    if (empty($nickname) || empty($email) || empty($phoneNumber) || empty($password)) {
        echo "Please fill in all fields.";
    } else {
        // Проверяем, есть ли пользователь с таким же ником
        $checkSql = "SELECT * FROM users WHERE nickname = '$nickname'";
        $checkResult = $conn->query($checkSql);

        if ($checkResult->num_rows > 0) {
            // Пользователь с таким ником уже существует
            echo "A user with this nickname already exists. Please choose another nickname.";
        } else {
            // SQL-запрос для вставки данных в таблицу
            $insertSql = "INSERT INTO users (nickname, email, phoneNumber, password, avatar) VALUES ('$nickname', '$email', '$phoneNumber', '$password','$avatar')";

            // Проверяем успешность выполнения запроса
            if ($conn->query($insertSql) === TRUE) {
                // Сохраняем информацию в сессии и устанавливаем куки
                session_start();
                $_SESSION['nickname'] = $nickname;
                setcookie("nickname", $nickname, time() + 3600, "/"); // Куки будут действовать в течение 1 часа
                setcookie("email", $email, time() + 3600, "/"); // Куки будут действовать в течение 1 часа

                // Перенаправляем на profile.php
                header("Location: profile.php");
                exit();
            } else {
                echo "Error executing query: " . $conn->error;
            }
        }
    }
}


// Закрываем соединение
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration and Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- Custom CSS -->
    <style>
a:hover,a:focus{
 outline: none;
 text-decoration: none;
}
.tab{

 padding: 40px 50px;
 position: relative;
 margin: 0 auto;
 float: none;
}
.tab:before{
 content: "";
 width: 100%;
 height: 100%;
 display: block;
 position: absolute;
 top: 0;
 left: 0;
 background: #079911;
 opacity: 0.85;
 border-radius: 30px;
}
.tab .nav-tabs{
 border-bottom: none;
 padding: 0 20px;
 position: relative;
}
.tab .nav-tabs li{ margin: 0 30px 0 0; }
.tab .nav-tabs li a{
 font-size: 18px;
 color: #fff;
 border-radius: 0;
 text-transform: uppercase;
 background: transparent;
 padding: 0;
 margin-right: 0;
 border: none;
 border-bottom: 2px solid transparent;
 opacity: 0.5;
 position: relative;
 transition: all 0.5s ease 0s;
}
.tab .nav-tabs li a:hover{ background: transparent; }
.tab .nav-tabs li.active a,
.tab .nav-tabs li.active a:focus,
.tab .nav-tabs li.active a:hover{
 border: none;
 background: transparent;
 opacity: 1;
 border-bottom: 2px solid #eec111;
 color: #fff;
}
.tab .tab-content{
 padding: 20px 0 0 0;
 margin-top: 40px;
 background: transparent;
 z-index: 1;
 position: relative;
}
.form-bg{ background: #ddd; }
.form-horizontal .form-group{
 margin: 0 0 15px 0;
 position: relative;
}
.form-control input[type="file"]{
 height: 40px;
 background: rgba(255,255,255,0.2);
 border: none;
 border-radius: 20px;
 box-shadow: none;
 padding: 0 20px;
 font-size: 14px;
 font-weight: bold;
 color: #fff;
 transition: all 0.3s ease 0s;
}
.form-horizontal .form-control{
 height: 40px;
 background: rgba(255,255,255,0.2);
 border: none;
 border-radius: 20px;
 box-shadow: none;
 padding: 0 20px;
 font-size: 14px;
 font-weight: bold;
 color: #fff;
 transition: all 0.3s ease 0s;
}
.form-horizontal .form-control:focus{
 box-shadow: none;
 outline: 0 none;
}
.form-horizontal .form-group label{
 padding: 0 20px;
 color: white;
 text-transform: capitalize;
 margin-bottom: 10px;
}
.form-horizontal .main-checkbox{
 width: 20px;
 height: 20px;
 background: #eec111;
 float: left;
 margin: 5px 0 0 20px;
 border: 1px solid #eec111;
 position: relative;
}
.form-horizontal .main-checkbox label{
 width: 20px;
 height: 20px;
 position: absolute;
 top: 0;
 left: 0;
 cursor: pointer;
}
.form-horizontal .main-checkbox label:after{
 content: "";
 width: 10px;
 height: 5px;
 position: absolute;
 top: 5px;
 left: 4px;
 border: 3px solid #fff;
 border-top: none;
 border-right: none;
 background: transparent;
 opacity: 0;
 -webkit-transform: rotate(-45deg);
 transform: rotate(-45deg);
}
.form-horizontal .main-checkbox input[type=checkbox]{ visibility: hidden; }
.form-horizontal .main-checkbox input[type=checkbox]:checked + label:after{ opacity: 1; }
.form-horizontal .text{
 float: left;
 font-size: 14px;
 font-weight: bold;
 color: #fff;
 margin-left: 7px;
 line-height: 20px;
 padding-top: 5px;
 text-transform: capitalize;
}
.form-horizontal .btn{
 width: 100%;
 background: white;
 padding: 10px 20px;
 border: none;
 font-size: 14px;
 font-weight: bold;
 color: #079911;
 border-radius: 20px;
 text-transform: uppercase;
 margin: 20px 0 30px 0;
}
.form-horizontal .btn:focus{
 background: #eec111;
 color: #fff;
 outline: none;
 box-shadow: none;
}
.form-horizontal .forgot-pass{
 border-top: 1px solid #615f6c;
 margin: 0;
 text-align: center;
}
.form-horizontal .forgot-pass .btn{
 width: auto;
 background: transparent;
 margin: 30px 0 0 0;
 color: #615f6c;
 text-transform: capitalize;
 transition: all 0.3s ease 0s;
}
.form-horizontal .forgot-pass .btn:hover{ color: #eec111; }
@media only screen and (max-width: 479px){
 .tab{ padding: 40px 20px; }
}
body {
    background-color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }
</style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-offset-3 col-md-6">
            <div class="tab" role="tabpanel">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#Section1" aria-controls="home" role="tab" data-toggle="tab">Вход</a></li>
                    <li role="presentation"><a href="#Section2" aria-controls="profile" role="tab" data-toggle="tab">Регистрация</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content tabs">
                    <!-- Sign In Section -->
                    <div role="tabpanel" class="tab-pane fade in active" id="Section1">
                        <form class="form-horizontal" method="post" action="login.php">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Имя</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" name="nickname">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Пароль</label>
                                <input type="password" class="form-control" id="exampleInputPassword1" name="password">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-default">Войти</button>
                            </div>
                        </form>
                    </div>
                    <!-- Sign Up Section -->
                    <div role="tabpanel" class="tab-pane fade" id="Section2">
    <form class="form-horizontal" id="registrationForm" action="register.php" method="post">
        <div class="form-group">
            <label for="firstName">Имя</label>
            <input type="text" class="form-control" id="nickname" name="nickname">
        </div>
        <div class="form-group">
            <label for="email">Почта</label>
            <input type="email" class="form-control" id="email" name="email">
        </div>
        <div class="form-group">
            <label for="phoneNumber">Номер телефона</label>
            <input type="text" class="form-control" id="phoneNumber" name="phoneNumber">
        </div>
        <div class="form-group">
            <label for="password">Пароль</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-default">Зарегистрироваться</button>
        </div>
    </form>
</div>

                </div>
            </div>
        </div><!-- /.col-md-offset-3 col-md-6 -->
    </div><!-- /.row -->
</div><!-- /.container -->
</body>
</html>
