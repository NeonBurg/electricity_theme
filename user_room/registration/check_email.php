<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29.04.2018
 * Time: 18:56
 */

require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

// Only process POST reqeusts.
//if ($_SERVER["REQUEST_METHOD"] == "POST") {
    global $wpdb;

    if ($wpdb) {
        $email = strip_tags(trim($_POST["email_input"]));
        $email = str_replace(array("\r", "\n"), array(" ", " "), $email);

        /* --------------------========= Проверка email адреса ==========-------------------- */
        if(!empty($email)) {

            $s_email_result= $wpdb->get_results("SELECT email FROM user_room_accounts WHERE email = '".$email."'");

            if($s_email_result) {
                http_response_code(400);
                echo "Пользователь с таким e-mail адресом уже зарегистрирован";
                exit;
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