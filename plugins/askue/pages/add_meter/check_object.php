<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 04.05.2018
 * Time: 15:44
 */

require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

global $wpdb;

if($wpdb) {

    $parent_object_name = $_POST["energy_object_input"];
    $energy_object_id = 'NULL';

    if(!isset($_POST["nullableEnergyObject"]) || (isset($_POST["nullableEnergyObject"]) && !empty($parent_object_name))) {
        $energy_object_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM EnergyObjects WHERE name = %s", $parent_object_name));

        if(empty($energy_object_id)) {
            http_response_code(400);
            echo "Неверное название объекта";
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