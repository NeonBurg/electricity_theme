<?php

require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

global $wpdb;

if($wpdb) {

    $meter_name = $_POST["meter_name_input"];
    $meter_id = 'NULL';

    if($meter_name != '') {
        $meter_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM Meters WHERE name = %s", $meter_name));

        if (empty($meter_id)) {
            http_response_code(400);
            echo "Неверное название счетчика";
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