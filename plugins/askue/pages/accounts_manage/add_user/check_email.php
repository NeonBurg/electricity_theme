<?php

require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

global $wpdb;

if($wpdb) {

    $user_email = $_POST["email_input"];

    // Проверяем длину названия
    if(!empty($user_email) && (strlen($user_email) < 4 or strlen($user_email) > 200))
    {
        http_response_code(400);
        echo "Email адрес должен быть не меньше 4-х символов и не больше 200";
        exit;
    }

    if(!isset($_POST["edit_user_email"]) || strcmp($_POST["edit_user_email"], $user_email) !== 0) {
        $check_user_email = $wpdb->get_var($wpdb->prepare("SELECT email FROM Customers WHERE email = %s", $user_email));
        if (!empty($check_user_phone) && strcmp($check_user_phone, $user_phone) === 0) {
            http_response_code(400);
            echo "Пользователь с таким email адресом уже зарегистрирован";
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