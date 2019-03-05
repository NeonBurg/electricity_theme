<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 23.05.2018
 * Time: 10:29
 */

if($_SERVER['CONTEXT_DOCUMENT_ROOT']) require_once( $_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/wp-load.php');
else require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

require_once(ASKUE_PLUGIN_DIR . "models/DataController.php");

global $wpdb;

$meter_id = $_POST["meter_id"];

if($meter_id) {

    if($wpdb) {

        $dataController = new DataController($wpdb);
        $meter = $dataController->selectMeter($meter_id);
        $dataController->deleteMeter($meter->getId(), $meter->getNum());

        http_response_code(200);
        echo "Успешное удаление счетчика: meter_id = ".$meter_id;

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