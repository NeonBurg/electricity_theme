<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 06.05.2018
 * Time: 12:26
 */

if($_SERVER['CONTEXT_DOCUMENT_ROOT']) require_once( $_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/wp-load.php');
else require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

global $wpdb;

if($wpdb) {

    $user_group_name = $_POST["group_input"];
    $user_group_id = null;

    if(empty($user_group_name)) {
        http_response_code(400);
        echo "Пустое поле с названием группы";
        exit;
    }
    else {
        $user_group_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM UserGroups WHERE name = %s", $user_group_name));

        if (empty($user_group_id)) {
            http_response_code(400);
            echo "Группы с таким названием не существует";
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