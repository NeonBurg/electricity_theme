<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 07.05.2018
 * Time: 13:21
 */

require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

global $wpdb;

if($wpdb) {

    $object_name = $_POST["object_name_input"];

    // Проверяем длину названия
    if(strlen($object_name) < 3 or strlen($object_name) > 200)
    {
        http_response_code(400);
        echo "Название должно быть не меньше 3-х символов и не больше 200";
        exit;
    }

    /*$test = "";
    if(isset($_POST["edit_energy_object_name"]))  {
        //$test = "variable set";
    }
    else {
        //$test = "variable empty";
    }*/



    if(!isset($_POST["edit_energy_object_name"]) || strcmp($_POST["edit_energy_object_name"], $object_name) !== 0) {
        $check_object_name = $wpdb->get_var($wpdb->prepare("SELECT name FROM EnergyObjects WHERE name = '%s'", $object_name));
        if (!empty($check_object_name)) {
            http_response_code(400);
            echo "Объект с таким названием уже существует: ";
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