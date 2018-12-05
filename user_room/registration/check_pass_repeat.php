<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29.04.2018
 * Time: 18:39
 */

//if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $password = strip_tags(trim($_POST["pass_input"]));
    $password = str_replace(array("\r", "\n"), array(" ", " "), $password);

    $password_repeat = strip_tags(trim($_POST["pass_repeat_input"]));
    $password_repeat = str_replace(array("\r", "\n"), array(" ", " "), $password_repeat);

    /* --------------------========= Проверка повтора пароля ==========-------------------- */
    if(empty($password_repeat)) {
        http_response_code(400);
        echo "Пустое поле с повтором пароля";
        exit;
    }

    if($password != $password_repeat) {
        http_response_code(400);
        echo "Пароли не совпадают";
        exit;
    }

    //http_response_code(200);

//}

?>