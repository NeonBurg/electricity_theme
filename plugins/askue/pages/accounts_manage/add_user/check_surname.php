<?php

require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

global $wpdb;


$user_surname = $_POST["surname_input"];

if(empty($user_surname)) {
    http_response_code(400);
    echo "Пустое поле с фамилией";
    exit;
}

// Проверяем длину имени
if(strlen($user_surname) > 50)
{
    http_response_code(400);
    echo "Длина фамилии не может превышать 50 символов";
    exit;
}

//TO-DO проверку на наличе знаков припинания

?>