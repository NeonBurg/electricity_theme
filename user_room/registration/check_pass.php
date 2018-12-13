<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29.04.2018
 * Time: 17:56
 */

//if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $password = strip_tags(trim($_POST["password_input"]));
    $password = str_replace(array("\r", "\n"), array(" ", " "), $password);

    /* --------------------========= Проверка пароля ==========-------------------- */
    if(empty($password)) {
        http_response_code(400);
        echo "Пустое поле с паролем";
        exit;
    }

    // Проверка правильности пароля
    if(!preg_match("/^[a-zA-Z0-9]+$/",$password))
    {
        http_response_code(400);
        echo "Пароль может состоять только из букв английского алфавита и цифр";
        exit;
    }

    if(strlen($password) == 0) {
        http_response_code(400);
        echo "Поле с паролем не должно быть пустым";
        exit;
    }
    else if(strlen($password) < 6) {
        http_response_code(400);
        echo "Длина пароля должна быть больше 6 символов";
        exit;
    }

    if(strpos($password, 0x20) == true) {
        http_response_code(400);
        echo "Пароль не должен содержать пробелы";
        exit;
    }



    //http_response_code(200);
//}

?>