<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 05.05.2018
 * Time: 14:18
 */

if($_SERVER['CONTEXT_DOCUMENT_ROOT']) require_once( $_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/wp-load.php');
else require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

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

        if(!isset($_POST["edit_meter_num"]) || strcmp($_POST["edit_meter_num"], $meter_num) !== 0) {

            $meters_results = $wpdb->get_var($wpdb->prepare("SELECT num FROM Meters WHERE num = %d", $meter_num));

            if (!empty($meters_results)) {
                http_response_code(400);
                echo "Счетчик с таким номером уже существует";
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
