<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29.04.2018
 * Time: 15:52
 */

if($_SERVER['CONTEXT_DOCUMENT_ROOT']) $path = $_SERVER['CONTEXT_DOCUMENT_ROOT'];
else $path = $_SERVER['DOCUMENT_ROOT'];

//http_response_code(400);
//echo $path;
//exit;

require_once( $path . '/wp-load.php');

// Only process POST reqeusts.
//if ($_SERVER["REQUEST_METHOD"] == "POST") {
    global $wpdb;

    if($wpdb) {
        $login = strip_tags(trim($_POST["login_input"]));
        $login = str_replace(array("\r", "\n"), array(" ", " "), $login);

        /* --------------------========= Проверка логина ==========-------------------- */
        if(empty($login)) {
            http_response_code(400);
            echo "Пустое поле с логином";
            exit;
        }

        // Проверка правильности логина
        if(!preg_match("/^[a-zA-Z0-9]+$/",$login))
        {
            http_response_code(400);
            echo "Логин может состоять только из букв английского алфавита и цифр";
            exit;
        }

        // Проверяем длину логина
        if(strlen($login) < 3 or strlen($login) > 30)
        {
            http_response_code(400);
            echo "Логин должен быть не меньше 3-х символов и не больше 30";
            exit;
        }

        //echo "<p>login: ".$login."</p>";

        // Проверяем уникальность логина в БД

        if(!isset($_POST["edit_user_login"]) || strcmp($_POST["edit_user_login"], $login) !== 0) {
            $result = $wpdb->get_results($this->wpdb->prepare("SELECT login FROM user_room_accounts WHERE login = %s", $login));

            if ($result) {
                http_response_code(400);
                echo "Пользователь с таким логином уже существует";
                exit;
            } else {
                //http_response_code(200);
            }
        }
    }
    else {
        http_response_code(400);
        echo "Отсутсвует соединение с базой данных";
        exit;
    }
//}

?>