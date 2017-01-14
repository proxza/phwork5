<?php

session_start();

$formEnable = 0; // Переключатель формы
$hostsCount = 0; // Счетчик хостов юзера
$dt = date("d.m.y G:i");
CONST FILE_NAME = "db.txt"; // Название БД
$news = unserialize(file_get_contents(FILE_NAME));

// Подключаем файлик с авторизацией что бы не писать везде одно и тоже 5 раз
require_once "auth.php";

if (isset($_POST['saveNews'])) {

    // Инициализация переменных для удобства
    $id = 1;
    $title = $_POST['title'];
    $date = $dt;
    $author = $_SESSION['login'];
    $content = $_POST['content'];

    // Проверяем не пустой ли файл (если $news не массив значит пустой)
    // Если не пустой - получаем id предыдущей новости и плюсуем к новой и делаем массив
    if (is_array($news)) {
        foreach ($news as $key) {
            if (isset($key['id'])) {
                $id = $key['id'] + 1;
            }
        }
    } else {
        $news = []; // Делаем массив
    }

    // Вставляем в наш массив данные полученные из формы
    array_push($news, ["id"=>$id, "author"=>$author, "title"=>$title, "date"=>$date, "content"=>$content]);
    file_put_contents(FILE_NAME, serialize($news)); // Сохраняем в файл наш сериализованный массив

    echo "<center>Добавлено</center>";

}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Добавление новости</title>
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

        // Если авторизация успешна - выводим форму добавления новостей
        if ($formEnable != 0) {

            ?>

            <h2 name="top">Добавить новость</h2>
            <form action="addnews.php" method="post">
                <table>
                    <tr>
                        <td>Автор:</td><td><b><?=$_SESSION['login'];?></b></td>
                    </tr>
                    <tr>
                        <td>Название новости:</td><td><input type="text" name="title"></td>
                    </tr>
                    <tr>
                        <td>Дата:</td><td><input type="text" disabled name="date" value="<?=$dt;?>"></td>
                    </tr>
                    <tr>
                        <td valign="top">Текст новости:</td>
                    </tr>
                    <tr>
                        <td colspan="2"><textarea rows="15" cols="100" required name="content" maxlength="1000"></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center"><input type="submit" value="Отправить"><input type="hidden" name="saveNews"></td>
                    </tr>
                </table>

            </form>

            <?php

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
