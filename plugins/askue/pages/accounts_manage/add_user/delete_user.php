<?php

if($_SERVER['CONTEXT_DOCUMENT_ROOT']) require_once( $_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/wp-load.php');
else require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

require_once(ASKUE_PLUGIN_DIR . "models/DataController.php");

global $wpdb;

$user_id = $_POST["user_id"];

if($user_id) {

    if($wpdb) {

        $dataController = new DataController($wpdb);
        $delete_status = $dataController->deleteUser($user_id);

        if(count($delete_status) == 0) {
            http_response_code(200);
            echo "Успешное удаление пользователя: meter_id = ".$user_id;
        }
        else {
            http_response_code(400);
            echo $delete_status[0];
            exit;
        }

    }
    else {
        http_response_code(400);
        echo "Отсутсвует соединение с базой данных";
        exit;
    }

}
else {
    http_response_code(400);
    echo "Пустой параметр user_id";
    exit;
}

?>