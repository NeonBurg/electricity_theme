<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 05.05.2018
 * Time: 15:16
 */


require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

global $wpdb;

if($wpdb) {

    $network_address = strip_tags(trim($_POST["network_address_input"]));
    $network_address = str_replace(array("\r", "\n"), array(" ", " "), $network_address);

    // Проверяем длину номера
    if(strlen($network_address)<=0) {
        http_response_code(400);
        echo "Пустое поле с сетевым адресом";
        exit;
    }

    if(strpos($network_address, "-") !== false) {
        http_response_code(400);
        echo "Сетевой адрес не может быть отрицательным";
        exit;
    }

    // Проверяем, что номер состоит только из чисел
    if(!preg_match("/^[0-9][0-9]*$/", $network_address)) {
        http_response_code(400);
        echo "Сетевой адрес может состоять только из цифр [0-9]";
        exit;
    }


    if(isset($_POST["energy_object_input"])) {
        $energy_object_name = $_POST["energy_object_input"];

        if(!isset($_POST["edit_meter_network_address"]) || !isset($_POST["edit_meter_energy_object_name"]) || strcmp($_POST["edit_meter_energy_object_name"], $energy_object_name) !== 0 || strcmp($_POST["edit_meter_network_address"], $network_address) !== 0) {

            $energy_object_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM EnergyObjects WHERE name = %s", $energy_object_name));
            if (!empty($energy_object_id)) {

                $meters_results = $wpdb->get_results($wpdb->prepare("SELECT network_address FROM Meters WHERE energyObject_id = %d", $energy_object_id));

                $meter_num_unique = true;

                if ($meters_results) {
                    foreach ($meters_results as $meter) {
                        if (strcmp($meter->network_address, $network_address) === 0) {
                            $meter_num_unique = false;
                        }
                    }
                }

                if (!$meter_num_unique) {
                    http_response_code(400);
                    echo "Счетчик с таким сетевым адресом уже существует";
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