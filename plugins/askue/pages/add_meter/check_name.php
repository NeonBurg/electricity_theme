<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 04.05.2018
 * Time: 11:55
 */

require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

global $wpdb;

if($wpdb) {

    $meter_name = $_POST["meter_name_input"];

    // Проверяем длину названия
    if(strlen($meter_name) < 3 or strlen($meter_name) > 200)
    {
        http_response_code(400);
        echo "Название должно быть не меньше 3-х символов и не больше 200";
        exit;
    }

    if(isset($_POST["energy_object_input"])) {
        $energy_object_name = $_POST["energy_object_input"];

        if(!isset($_POST["edit_meter_name"]) || !isset($_POST["edit_meter_energy_object_name"]) || strcmp($_POST["edit_meter_energy_object_name"], $energy_object_name) !== 0 || strcmp($_POST["edit_meter_name"], $meter_name) !== 0) {

            $energy_object_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM EnergyObjects WHERE name = %s", $energy_object_name));
            if (!empty($energy_object_id)) {

                $meters_results = $wpdb->get_results($wpdb->prepare("SELECT name FROM Meters WHERE energyObject_id = %d", $energy_object_id));

                $meter_name_unique = true;

                if ($meters_results) {
                    foreach ($meters_results as $meter) {
                        if (strcmp($meter->name, $meter_name) === 0) {
                            $meter_name_unique = false;
                        }
                    }
                }

                if (!$meter_name_unique) {
                    http_response_code(400);
                    echo "Счетчик с таким названием уже существует";
                    exit;
                }

            }
        }
    }

}
else {
    http_response_code(400);
    echo "Отсутсвует соединение с базой данных";
    exit;
}


?>