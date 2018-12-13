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

include(ASKUE_PLUGIN_DIR . "pages/accounts_manage/add_user/check_name.php");
include(ASKUE_PLUGIN_DIR . "pages/accounts_manage/add_user/check_surname.php");
include(ASKUE_PLUGIN_DIR . "pages/accounts_manage/add_user/check_patronymic.php");
include(ASKUE_PLUGIN_DIR . "pages/accounts_manage/add_user/check_phone.php");
include(ASKUE_PLUGIN_DIR . "pages/accounts_manage/add_user/check_email.php");

require_once( $_SERVER['DOCUMENT_ROOT'] . '/user_room/utils/encrypt.php' );

if($wpdb) {

    $password_hash = encryptIt($password, $login); // Создаем хэш пароля

    $sql = $wpdb->prepare("INSERT INTO user_room_accounts(login, password_hash, session_hash) VALUES(%s, %s, %s)", $login, $password_hash, null);
    $wpdb->query($sql);

    $account_id = $wpdb->get_var("SELECT id FROM user_room_accounts WHERE login = '".$login."'");

    $group_id = $wpdb->get_var("SELECT id FROM UserGroups WHERE name = 'Клиенты'"); // TO-DO выбор группы по уровню доступа

    if($account_id) {
        //header('location: /user_room_page/auth/?login='.$login.'&registration_success=Успешная регистрация');
        $sql = $wpdb->prepare("INSERT INTO Customers(name, surname, patronymic, account_id, phone, email, group_id) VALUES(%s, %s, %s, %d, %s, %s, %d)", $user_name, $user_surname, $user_patronymic, $account_id, $user_phone, $user_email, $group_id);
        $wpdb->query($sql);

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