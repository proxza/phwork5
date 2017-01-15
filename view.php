<?php

session_start(); // Стартуем сессию

$formEnable = 0; // Переключатель формы
$hostsCount = 0; // Счетчик хостов юзера
CONST FILE_NAME = "db.txt"; // Название БД
$news = unserialize(file_get_contents(FILE_NAME)); // Считываем наш файлик с новостями

// Запоминаем и кидаем в кукисы посещенную новость
$link = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; // Ссылка на страницу на которой мы находимся

// Проверяем есть ли кука и устанавливаем
if (!isset($_COOKIE['lastUrl'])) {
    setcookie("lastUrl", $link);
} else {
    $link = $_COOKIE['lastUrl']."|http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    setcookie("lastUrl", $link);
}

// Подключаем файлик с авторизацией
require_once "auth.php";
require_once "vk-auth.php";

?>
<!DOCTYPE html>
<html>
<head>
    <title>Просмотр новости</title>
    <link rel="stylesheet" href="css/styles.css" type="text/css">
    <meta http-equiv="content-type" content="text/html" charset="utf-8">
</head>
<body>

<div id="container">
    <div id="header">
        logo
    </div>

    <div id="navigation">
        <a href="index.php">Главная</a> | <a href="#">Страница 1</a> | <a href="#">Страница 2</a> | <a href="#">Страница 3</a> | <a href="#">Контакты</a> | <a href="#">О нас</a>
    </div>

    <div id="content">

        <?php

        // Если авторизация успешна - выводим новости
        if ($formEnable != 0) {

            // Получение id новости из GET-запроса
            if (isset($_GET['newsId'])) {
                // Цикл вывода новости
                foreach ($news as $key) {
                    // Если есть новость с таким id - выводим её
                    if ($key['id'] == $_GET['newsId']) {
                        echo "<h2>" . $key['title'] . "</h2>";
                        echo "Дата: <strong>" . $key['date'] . "</strong><br />";
                        echo "Автор: <strong>" . $key['author'] . "</strong><br /><br />";
                        echo nl2br($key['content']);
                    }
                }
            }

        } else {

            ?>

            content

            <?php

        }

        ?>
    </div>

    <div id="right-menu">

        <?php

        // Если авторизация не пройдена, выводим форму
        if ($formEnable == 0) {

            // Ссылка для oAuth через ВК
            echo $link = '<p><a href="' . $url . '?' . urldecode(http_build_query($params)) . '">Вход через ВКонтакте</a></p>';

            ?>
            <div id="auth">
                <b>Форма авторизации</b>
                <form action="" method="post">
                    <table>
                        <tr>
                            <td>Ваш логин</td>
                        </tr>
                        <tr>
                            <td><input type="text" name="login"></td>
                        </tr>
                        <tr>
                            <td>Ваш пароль</td>
                        </tr>
                        <tr>
                            <td><input type="password" name="password"></td>
                        </tr>
                        <tr>
                            <td><input type="hidden" name="auth"><input type="submit" value="Войти"><br/><br/></td>
                        </tr>
                    </table>
                </form>
            </div>

            <?php

        } elseif (isset($_SESSION['user'])) {

            echo "<div>";
            echo "Вы авторизованы как <a href='https://vk.com/id" . $_SESSION['user']['uid'] . "' target='_blank'><strong>" . $_SESSION['user']['last_name'] . " " . $_SESSION['user']['first_name'] . "</strong></a><br /><br />";
            echo "<a href='addnews.php'>Добавить новость</a> <br />";
            echo "<a href='index.php?exit'>Выйти</a>";
            echo "</div>";

        } else {

            ?>

            <div>
                Вы авторизованы как <strong><?=$_SESSION['login'];?></strong>! <br /><br />
                <a href="addnews.php">Добавить новость</a> <br />
                <a href="index.php?exit">Выйти</a>
            </div>

            <?php

        }

        ?>

    </div>

    <div id="clear">
    </div>

    <div id="footer">
        Hosts: <?=$hostsCount;?>
    </div>
</div>

</body>
</html>
