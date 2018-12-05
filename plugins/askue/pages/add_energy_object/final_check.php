<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 07.05.2018
 * Time: 13:21
 */

require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
include(ASKUE_PLUGIN_DIR . "pages/add_energy_object/check_name.php");
include(ASKUE_PLUGIN_DIR . "pages/add_energy_object/check_address.php");
include(ASKUE_PLUGIN_DIR . "pages/add_energy_object/check_owner.php");

global $wpdb;

if($wpdb) {

    $sql_insert_object = $wpdb->prepare("INSERT INTO EnergyObjects(name, address, customer_id) VALUES('%s', '%s', %d)", $object_name, $object_address, $account_id);
    $wpdb->query($sql_insert_object);

    $result = $wpdb->get_row($wpdb->prepare("SELECT * FROM EnergyObjects WHERE name = '%s'", $object_name));

    if ($result) {
        http_response_code(200);
    }
    else {
        http_response_code(400);
        echo "Ошибка mysql";
        exit;
    }

}
else {
    http_response_code(400);
    echo "Отсутсвует соединение с базой данных";
    exit;
}

?>