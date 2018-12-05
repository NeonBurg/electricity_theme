<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 30.04.2018
 * Time: 12:12
 */

include($_SERVER['DOCUMENT_ROOT'] . "/user_room/registration/check_login.php");
include($_SERVER['DOCUMENT_ROOT'] . "/user_room/registration/check_pass.php");
include($_SERVER['DOCUMENT_ROOT'] . "/user_room/registration/check_pass_repeat.php");
include($_SERVER['DOCUMENT_ROOT'] . "/user_room/registration/check_email.php");
require_once( $_SERVER['DOCUMENT_ROOT'] . '/user_room/utils/encrypt.php' );

if($wpdb) {

    $password_hash = encryptIt($password, $login); // Создаем хэш пароля

    $sql = $wpdb->prepare("INSERT INTO user_room_accounts(login, password_hash, session_hash) VALUES(%s, %s, %s)", $login, $password_hash, null);
    $wpdb->query($sql);

    $result = $wpdb->get_results("SELECT login FROM user_room_accounts WHERE login = '".$login."'");

    if($result) {
        //header('location: /user_room_page/auth/?login='.$login.'&registration_success=Успешная регистрация');
        http_response_code(200);
    }
    else {
        http_response_code(400);
        echo "Ошибка mysql";
        exit;
    }
}
else {
    http_response_code(400);
    echo "Отсутсвует соединение с базой данных";
    exit;
}

?>