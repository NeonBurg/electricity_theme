<?php

require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

global $wpdb;

if($wpdb) {

    $user_phone = $_POST["phone_input"];

    // Проверяем длину названия
    if(!empty($user_phone) && (strlen($user_phone) < 7 or strlen($user_phone) > 11))
    {
        http_response_code(400);
        echo "Длина номера должна быть не меньше 7 символов и не больше 11";
        exit;
    }

    if(!isset($_POST["edit_user_phone"]) || strcmp($_POST["edit_user_phone"], $user_phone) !== 0) {
        $check_user_phone = $wpdb->get_var($wpdb->prepare("SELECT phone FROM Customers WHERE phone = %s", $user_phone));
        if (!empty($check_user_phone) && strcmp($check_user_phone, $user_phone) === 0) {
            http_response_code(400);
            echo "Пользователь с таким номером уже зарегистрирован";
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