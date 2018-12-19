<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 07.05.2018
 * Time: 13:21
 */

require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

global $wpdb;

if($wpdb) {

    $object_owner = $_POST["object_owner_input"];

    if(empty($object_owner)) {
        http_response_code(400);
        echo "Пустое поле с владельцем";
        exit;
    }

    $sep_pos = strpos($object_owner, "-");
    if($sep_pos) {
        $owner_login = substr($object_owner, 0, $sep_pos-1);
    }
    else {
        $owner_login = $object_owner;
    }

    $owner_login = strip_tags(trim($owner_login));
    $owner_login = str_replace(array("\r", "\n"), array(" ", " "), $owner_login);


    $account_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM user_room_accounts WHERE login = '%s'", $owner_login));

    if(empty($account_id)) {
        http_response_code(400);
        echo "Неверный аккаунт пользователя";
        exit;
    }
    else {
        $customer_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM Customers WHERE account_id = '%d'", $account_id));

        if(!empty($customer_id)) {
            http_response_code(200);
            echo "account_id = ".$account_id;
        }
        else {
            http_response_code(400);
            echo "Неверный аккаунт пользователя";
            exit;
        }
    }

    //http_response_code(400);
    //echo "login = '" . $owner_login . "'";
    //exit;

    /*$check_object_name = $wpdb->get_var($wpdb->prepare("SELECT address FROM EnergyObjects WHERE address = '%s'", $object_address));

    if(!empty($check_object_name)) {
        http_response_code(400);
        echo "Адрес объекта уже занят";
        exit;
    }*/
}
else {
    http_response_code(400);
    echo "Отсутсвует соединение с базой данных";
    exit;
}

?>

?>
