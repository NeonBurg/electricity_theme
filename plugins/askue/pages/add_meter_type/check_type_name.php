<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 15.05.2018
 * Time: 12:21
 */

require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

global $wpdb;

if($wpdb) {

    $type_name = $_POST["type_name_input"];

    // Проверяем длину названия
    if(strlen($type_name) < 3 or strlen($type_name) > 200)
    {
        http_response_code(400);
        echo "Название должно быть не меньше 3-х символов и не больше 200";
        exit;
    }

    $types_results = $wpdb->get_results($wpdb->prepare("SELECT name FROM MeterTypes WHERE name = %s", $type_name));

    $type_name_unique = true;

    if($types_results) {
        http_response_code(400);
        echo "Тип счетчика с таким названием уже существует";
        exit;
    }

}
else {
    http_response_code(400);
    echo "Отсутсвует соединение с базой данных";
    exit;
}

?>