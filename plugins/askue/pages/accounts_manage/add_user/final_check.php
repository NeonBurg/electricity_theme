<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 30.04.2018
 * Time: 12:12
 */

if($_SERVER['CONTEXT_DOCUMENT_ROOT']) $path = $_SERVER['CONTEXT_DOCUMENT_ROOT'];
else $path = $_SERVER['DOCUMENT_ROOT'];

include($path . "/user_room/registration/check_login.php");
include(ASKUE_PLUGIN_DIR . "pages/accounts_manage/add_user/check_password.php");
include(ASKUE_PLUGIN_DIR . "pages/accounts_manage/add_user/check_password_repeat.php");

include(ASKUE_PLUGIN_DIR . "pages/accounts_manage/add_user/check_name.php");
include(ASKUE_PLUGIN_DIR . "pages/accounts_manage/add_user/check_surname.php");
include(ASKUE_PLUGIN_DIR . "pages/accounts_manage/add_user/check_patronymic.php");
include(ASKUE_PLUGIN_DIR . "pages/accounts_manage/add_user/check_phone.php");
include(ASKUE_PLUGIN_DIR . "pages/accounts_manage/add_user/check_email.php");
include(ASKUE_PLUGIN_DIR . "pages/accounts_manage/add_user/check_group.php");

require_once( $path . '/user_room/utils/encrypt.php' );

global $wpdb;

if($wpdb) {

    if(isset($_POST["edit_user_id"])) {

        $edit_user_id = $_POST["edit_user_id"];
        $edit_user_account_id = $_POST["edit_user_account_id"];

        $sql_update_meter = $wpdb->prepare("UPDATE Customers SET name=%s, surname = %s, patronymic = %s, phone = %s, email = %s, group_id = %d WHERE id = %d", $user_name, $user_surname, $user_patronymic, $user_phone, $user_email, $user_group_id, $edit_user_id);
        $wpdb->query($sql_update_meter);

        if(isset($_POST["edit_user_login"]) && isset($edit_user_account_id) && strcmp($_POST["edit_user_login"], $login) !== 0) {
            $sql_update_meter = $wpdb->prepare("UPDATE user_room_accounts SET login = %s WHERE id = %d", $login, $edit_user_account_id);
            $wpdb->query($sql_update_meter);
        }

        if(!empty($password) && !empty($password_repeat) && strcmp($password, $password_repeat) === 0) {
            $password_hash = encryptIt($password, $login); // Создаем хэш пароля

            $sql_update_meter = $wpdb->prepare("UPDATE user_room_accounts SET password_hash = %s WHERE id = %d", $password_hash, $edit_user_account_id);
            $wpdb->query($sql_update_meter);
        }

        http_response_code(200);
        //echo "user_id = ".$edit_user_id." | account_id = ".$edit_user_account_id;

    }
    else {
        $password_hash = encryptIt($password, $login); // Создаем хэш пароля

        $sql = $wpdb->prepare("INSERT INTO user_room_accounts(login, password_hash, session_hash) VALUES(%s, %s, %s)", $login, $password_hash, null);
        $wpdb->query($sql);

        $account_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM user_room_accounts WHERE login = %s", $login));

        $group_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM UserGroups WHERE name = %s", $user_group_name)); // TO-DO выбор группы по уровню доступа

        if ($account_id) {
            //header('location: /user_room_page/auth/?login='.$login.'&registration_success=Успешная регистрация');
            $sql = $wpdb->prepare("INSERT INTO Customers(name, surname, patronymic, account_id, phone, email, group_id) VALUES(%s, %s, %s, %d, %s, %s, %d)", $user_name, $user_surname, $user_patronymic, $account_id, $user_phone, $user_email, $group_id);
            $wpdb->query($sql);

            http_response_code(200);
        } else {
            http_response_code(400);
            echo "Ошибка mysql";
            exit;
        }
    }
}
else {
    http_response_code(400);
    echo "Отсутсвует соединение с базой данных";
    exit;
}

?>