<?php

if($_SERVER['CONTEXT_DOCUMENT_ROOT']) require_once( $_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/wp-load.php');
else require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

require_once(ASKUE_PLUGIN_DIR . "models/DataController.php");

global $wpdb;

$group_id = $_POST["group_id"];

if($group_id) {

    if($wpdb) {

        $dataController = new DataController($wpdb);
        $dataController->deleteGroup($group_id);

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
    echo "Пустой параметр meter_id";
    exit;
}

?>