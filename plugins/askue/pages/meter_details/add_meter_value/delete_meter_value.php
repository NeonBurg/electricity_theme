<?php

if($_SERVER['CONTEXT_DOCUMENT_ROOT']) require_once( $_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/wp-load.php');
else require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

require_once(ASKUE_PLUGIN_DIR . "models/DataController.php");

global $wpdb;

$meter_id = $_POST["meter_id"];
$value_id = $_POST["value_id"];

if($meter_id && $value_id) {

    if($wpdb) {

        $dataController = new DataController($wpdb);
        $dataController->deleteMeterValue($meter_id, $value_id);

        http_response_code(200);
        echo "Успешное удаление группы: meter_id = ".$group_id;

    }
    else {
        http_response_code(400);
        echo "Отсутсвует соединение с базой данных";
        exit;
    }

}
else {
    http_response_code(400);
    echo "Пустой параметр meter_id или value_id";
    exit;
}

?>