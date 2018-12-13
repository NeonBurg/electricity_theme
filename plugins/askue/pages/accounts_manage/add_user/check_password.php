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

$edit_user_id = $_POST["edit_user_id"];

/* --------------------========= Проверка пароля ==========-------------------- */


        if (!$edit_user_id && empty($password)) {
            http_response_code(400);
            echo "Пустое поле с паролем";
            exit;
        }

        if(!empty($password)) {
            if (strlen($password) < 6) {
                http_response_code(400);
                echo "Длина пароля должна быть больше 6 символов";
                exit;
            }

            // Проверка правильности пароля
            if (!preg_match("/^[a-zA-Z0-9]+$/", $password)) {
                http_response_code(400);
                echo "Пароль может состоять только из букв английского алфавита и цифр";
                exit;
            }

            if (strpos($password, 0x20) == true) {
                http_response_code(400);
                echo "Пароль не должен содержать пробелы";
                exit;
            }
        }


//http_response_code(200);
//}

?>