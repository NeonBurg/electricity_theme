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
include(ASKUE_PLUGIN_DIR . "pages/add_energy_object/check_meter.php");
include(ASKUE_PLUGIN_DIR . "pages/add_meter/check_object.php");

global $wpdb;

if($wpdb) {

    if(isset($_POST["edit_energy_object_name"])) {

        $edit_energy_object_id = $_POST["edit_energy_object_id"];

        if($meter_id != 'NULL') {
            $wpdb->query($wpdb->prepare("UPDATE Meters SET energyObject_id = %d WHERE id = %d", $edit_energy_object_id, $meter_id));
        }

        $sql_update_meter = $wpdb->prepare("UPDATE EnergyObjects SET name=%s, address=%s, customer_id=%s, energyObject_id=".$energy_object_id.", meter_id=".$meter_id." WHERE id = %d", $object_name, $object_address, $customer_id, $edit_energy_object_id);

        $wpdb->query($sql_update_meter);

        http_response_code(200);
    }
    else {

        $sql_insert_object = $wpdb->prepare("INSERT INTO EnergyObjects(name, address, customer_id, energyObject_id, meter_id) VALUES('%s', '%s', %d, ".$energy_object_id.", ".$meter_id.")", $object_name, $object_address, $customer_id);
        $wpdb->query($sql_insert_object);

        $result = $wpdb->get_row($wpdb->prepare("SELECT * FROM EnergyObjects WHERE name = '%s'", $object_name));

        if ($result) {
            http_response_code(200);
        } else {
            http_response_code(400);
            echo "Ошибка mysql";
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