<?php

// Проверка, существует ли сессия с юзерайди (можно любую сессию проверить, не суть важно)
if (!isset($_SESSION['userId'])) {
    // Если такой переменной в сессии нет, проверяем отправлена ли форма авторизации
    if (isset($_POST['auth'])) {
        // Если форма отправлена, проверяем на пустые поля логина и пароля
        if (!empty($_POST['login']) OR !empty($_POST['password'])) {
            // Если поля не пустые, создаем в сессии переменные с логином, паролем и айди юзера
            $_SESSION['login'] = $_POST['login']; // Логин
            $_SESSION['password'] = $_POST['password']; // Пароль
            $_SESSION['userId'] = 1; // Айди
            $_SESSION['host'] = 1; // Счетчик
            $formEnable = 1; // Переключатель формы ставим в 1 (on)
        } else {
            echo "Вы не ввели логин либо пароль"; // Выводим ошибку если одно из полей пустое
        }
    }
} else {
    $_SESSION['host']++; // Добавляем +1 к счетчику каждый раз когда юзер переходит на страницу
    $hostsCount = $_SESSION['host'];
    $formEnable = 1; // Переключатель формы ставим в 1 (on) так как сессия уже существуют
}

// Если нажата кнопка "Выход" - уничтожаем сессию
if (isset($_GET['exit'])) {
    unset($_SESSION['userId']);
    unset($_SESSION['login']);
    unset($_SESSION['password']);
    unset($_SESSION['host']);
    setcookie("lastUrl");
    session_destroy();
    $formEnable = 0;
}

/**
 * @param $fileName
 * @return array
 */
function readMe ($fileName) {
    $data = array();
    $file = fopen($fileName, "r");

    while (!feof($file)) {
        $line = fgets($file, 9999);
        $data[] = $line;
    }

    return $data;
}

?>