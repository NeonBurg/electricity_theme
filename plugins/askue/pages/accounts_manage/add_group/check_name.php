<?php

if($_SERVER['CONTEXT_DOCUMENT_ROOT']) require_once( $_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/wp-load.php');
else require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

global $wpdb;

if($wpdb) {

    $group_name = $_POST["group_name_input"];

    // Проверяем длину названия
    if(strlen($group_name) < 3 or strlen($group_name) > 200)
    {
        http_response_code(400);
        echo "Название должно быть не меньше 3-х символов и не больше 200";
        exit;
    }

    if(!isset($_POST["edit_group_name"]) || strcmp($_POST["edit_group_name"], $group_name) !== 0) {
        $check_group_name = $wpdb->get_var($wpdb->prepare("SELECT name FROM UserGroups WHERE name = %s", $group_name));
        if (!empty($check_group_name) && strcmp($check_group_name, $group_name) === 0) {
            http_response_code(400);
            echo "Группа с таким названием уже существует";
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