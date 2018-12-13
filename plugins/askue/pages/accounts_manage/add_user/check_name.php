<?php

require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

global $wpdb;


$user_name = $_POST["name_input"];

if(empty($user_name)) {
    http_response_code(400);
    echo "Пустое поле с именем";
    exit;
}

// Проверяем длину имени
if(strlen($user_name) > 50)
{
    http_response_code(400);
    echo "Длина имени не может превышать 50 символов";
    exit;
}

//TO-DO проверку на наличе знаков припинания

?>