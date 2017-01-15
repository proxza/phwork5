<?php

session_start(); // Стартуем сессию

$formEnable = 0; // Переключатель формы
$cookieForm = 0; // Переключатель для вывода строки "последние просмотренные новости"
$cookieUrl = array(); // Массив в котором будут два последних URL из кукисов
$hostsCount = 0; // Счетчик хостов юзера
CONST FILE_NAME = "db.txt"; // Название БД
$news = unserialize(file_get_contents(FILE_NAME)); // Считываем наш файлик с новостями

// Проверяем есть ли куки с просмотренными новостями
if (@isset($_COOKIE['lastUrl'])) {
    $data = explode("|", $_COOKIE['lastUrl']); // Разбиваем переменную в кукисах на массив
    $cookieUrl = array_splice($data, -2); // Достаем из массива два последних элемента
    $cookieForm = 1;
}

// Подключаем файлик с авторизацией
require_once "auth.php";
require_once "vk-auth.php";

?>
<!DOCTYPE html>
<html>
<head>
    <title>Главная страница</title>
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

            echo "<h2>Новости:</h2>";
            // Цикл вывода новостей
            foreach ($news as $key) {
                echo "<ul>";
                echo "<li><a href='view.php?newsId=" . $key['id'] . "'>" . $key['title'] . "</a></li>";
                echo "</ul>";
            }

            // Выводим последние две страницы
            if ($cookieForm != 0) {
                echo "<br /><br /><br /><br />";
                echo "<strong>Последние просмотренные новости</strong>: <br />" . $cookieUrl[0] . "<br />" . $cookieUrl[1];
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
                    Вы авторизованы как <strong><?= $_SESSION['login']; ?></strong>! <br/><br/>
                    <a href="addnews.php">Добавить новость</a> <br/>
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
