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
        $user_password = $_POST["password"];

        // Готовим запрос (рекомендуется использовать подготовленные запросы для безопасности)
        $sql = "SELECT * FROM users WHERE nickname = '$nickname'";

        // Выполняем запрос
        $result = $conn->query($sql);

        // Проверяем, найден ли пользователь
        if ($result->num_rows > 0) {
            // Получаем данные пользователя
            $user = $result->fetch_assoc();
        
            // Проверяем соответствие пароля
            if ($user["password"] === $user_password) {
                // Аутентификация успешна
                // Сохраняем информацию в сессию
                session_start();
                $_SESSION["user_id"] = $user["id_user"];
                $_SESSION["nickname"] = $user["nickname"]; // Устанавливаем значение nickname в сессию
        
                // Перенаправляем на страницу профиля пользователя
                header("Location: profile.php");
                exit();
            } else {
                // Неверный пароль
                echo "Invalid password.";
            }
        } else {
            // Пользователь не найден
            echo "User not found.";
        }
        

        // Закрываем соединение с базой данных
        $conn->close();
    } else {
        echo "Nickname or password is not set in POST request.";
    }
}
?>
