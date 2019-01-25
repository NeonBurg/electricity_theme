<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 06.05.2018
 * Time: 12:26
 */

if($_SERVER['CONTEXT_DOCUMENT_ROOT']) require_once( $_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/wp-load.php');
else require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

global $wpdb;

if($wpdb) {

    $meter_type_name = $_POST["meter_type_input"];
    $meter_type_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM MeterTypes WHERE name = %s", $meter_type_name));

    if(empty($meter_type_id)) {
        http_response_code(400);
        echo "Неверный тип счетчика";
        exit;
    }

}
else {
    http_response_code(400);
    echo "Отсутсвует соединение с базой данных";
    exit;
}

?>