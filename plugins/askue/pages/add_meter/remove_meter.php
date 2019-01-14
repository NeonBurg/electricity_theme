<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 23.05.2018
 * Time: 10:29
 */

require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
require_once(ASKUE_PLUGIN_DIR . "models/DataController.php");

global $wpdb;

$meter_id = $_POST["meter_id"];

if($meter_id) {

    if($wpdb) {

        $dataController = new DataController($wpdb);
        $dataController->deleteMeter($meter_id);

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