<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 06.05.2018
 * Time: 12:56
 */

require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
include(ASKUE_PLUGIN_DIR . "pages/add_meter/check_name.php");
include(ASKUE_PLUGIN_DIR . "pages/add_meter/check_meter_num.php");
include(ASKUE_PLUGIN_DIR . "pages/add_meter/check_object.php");
include(ASKUE_PLUGIN_DIR . "pages/add_meter/check_meter_type.php");
include(ASKUE_PLUGIN_DIR . "pages/add_meter/check_network_address.php");

global $wpdb;

if($wpdb) {

    if(isset($_POST["edit_meter_name"])) {

        $edit_meter_id = $_POST["edit_meter_id"];

        $sql_update_meter = $wpdb->prepare("UPDATE Meters SET name=%s, num=%d, energyObject_id=%d, meterType_id=%d, network_address=%d WHERE id = %d", $meter_name, $meter_num, $energy_object_id, $meter_type_id, $network_address, $edit_meter_id);
        $wpdb->query($sql_update_meter);

        $result = $wpdb->get_row($wpdb->prepare("SELECT * FROM Meters WHERE id = %d", $edit_meter_id));
        if(strcmp($result->name, $meter_name) === 0
            && strcmp($result->num, $meter_num) === 0
            && strcmp($result->energyObject_id, $energy_object_id) === 0
            && strcmp($result->meterType_id, $meter_type_id) === 0
            && strcmp($result->network_address, $network_address) === 0) {
            http_response_code(200);
        }
        else {
            http_response_code(400);
            echo "Ошибка сохранения данных о счетчике";
            exit;
        }
    }
    else {

        $sql_insert_meter = $wpdb->prepare("INSERT INTO Meters(name, num, energyObject_id, meterType_id, network_address) VALUES('%s', %d, %d, %d, %d)", $meter_name, $meter_num, $energy_object_id, $meter_type_id, $network_address);
        $wpdb->query($sql_insert_meter);

        $result = $wpdb->get_row("SELECT id FROM Meters ORDER BY id DESC LIMIT 1");

        if ($result) {

            $meter_id = $result->id;

            $table_name = "meter_" . $meter_id;

            $wpdb->query("CREATE TABLE  " . $table_name . " (id int(10) NOT NULL AUTO_INCREMENT, KC int(11) NOT NULL, type tinyint(4) NOT NULL, base int(11) NOT NULL, decim tinyint(4) NOT NULL, date datetime NOT NULL UNIQUE, PRIMARY KEY (id))");

            $check_table = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name));
            if (!empty($check_table)) {
                $wpdb->query($wpdb->prepare("UPDATE Meters SET table_name = %s WHERE id = %d", $table_name, $meter_id));
            } else {
                http_response_code(400);
                echo "Ошибка создания таблицы";
                exit;
            }

            http_response_code(200);

        } else {
            http_response_code(400);
            echo "Ошибка mysql";
            exit;
        }
        //}
        //else {
        //    http_response_code(400);
        //    echo "Ошибка создания таблицы '".$table_name."' для счетчика";
        //    exit;
        //}
    }

}
else {
    http_response_code(400);
    echo "Отсутсвует соединение с базой данных";
    exit;
}

?>