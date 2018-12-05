<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 05.05.2018
 * Time: 14:18
 */

require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

global $wpdb;

if($wpdb) {

    $meter_num = strip_tags(trim($_POST["meter_num_input"]));
    $meter_num = str_replace(array("\r", "\n"), array(" ", " "), $meter_num);

    // Проверяем длину номера
    if(strlen($meter_num)<=0) {
        http_response_code(400);
        echo "Пустое поле с номером счетчика";
        exit;
    }

    if(strpos($meter_num, "-") !== false) {
        http_response_code(400);
        echo "Номер счетчика не может быть отрицательным";
        exit;
    }

    // Проверяем, что номер состоит только из чисел
    if(!preg_match("/^[0-9][0-9]*$/", $meter_num)) {
        http_response_code(400);
        echo "Номер счетчика может состоять только из цифр [0-9]";
        exit;
    }


    if(isset($_POST["energy_object_input"])) {
        $energy_object_name = $_POST["energy_object_input"];

        if(!isset($_POST["edit_meter_num"]) || !isset($_POST["edit_meter_energy_object_name"]) || strcmp($_POST["edit_meter_energy_object_name"], $energy_object_name) !== 0 || strcmp($_POST["edit_meter_num"], $meter_num) !== 0) {

            $energy_object_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM EnergyObjects WHERE name = %s", $energy_object_name));
            if (!empty($energy_object_id)) {

                $meters_results = $wpdb->get_results($wpdb->prepare("SELECT num FROM Meters WHERE energyObject_id = %d", $energy_object_id));

                $meter_num_unique = true;

                if ($meters_results) {
                    foreach ($meters_results as $meter) {
                        if (strcmp($meter->num, $meter_num) === 0) {
                            $meter_num_unique = false;
                        }
                    }
                }

                if (!$meter_num_unique) {
                    http_response_code(400);
                    echo "Счетчик с таким номером уже существует";
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
