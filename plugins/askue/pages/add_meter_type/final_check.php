<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 15.05.2018
 * Time: 12:26
 */

require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
include(ASKUE_PLUGIN_DIR . "pages/add_meter_type/check_type_name.php");

if($wpdb) {

    $sql_insert_type = $wpdb->prepare("INSERT INTO MeterTypes(name, type) VALUES(%s, %s)", $type_name, $_POST["meter_type"]);
    $wpdb->query($sql_insert_type);

    $result = $wpdb->get_row($wpdb->prepare("SELECT * FROM MeterTypes WHERE name = '%s'", $type_name));
    if ($result) {
        http_response_code(200);
    }
    else {
        http_response_code(400);
        echo "Ошибка: тип счетчика не добавлен";
        exit;
    }
    
}
else {
    http_response_code(400);
    echo "Отсутсвует соединение с базой данных";
    exit;
}


?>