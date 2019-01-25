<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 07.05.2018
 * Time: 13:21
 */

if($_SERVER['CONTEXT_DOCUMENT_ROOT']) require_once( $_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/wp-load.php');
else require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

global $wpdb;

if($wpdb) {

    $object_address = $_POST["object_address_input"];

    // Проверяем длину названия
    if(strlen($object_address) < 3 or strlen($object_address) > 200)
    {
        http_response_code(400);
        echo "Название должно быть не меньше 3-х символов и не больше 200";
        exit;
    }

    if(!isset($_POST["edit_energy_object_address"]) || strcmp($_POST["edit_energy_object_address"], $object_address) !== 0) {
        $check_object_name = $wpdb->get_var($wpdb->prepare("SELECT address FROM EnergyObjects WHERE address = '%s'", $object_address));
        if (!empty($check_object_name)) {
            http_response_code(400);
            echo "Адрес объекта уже занят";
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