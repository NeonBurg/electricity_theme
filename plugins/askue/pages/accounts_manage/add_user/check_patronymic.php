<?php

if($_SERVER['CONTEXT_DOCUMENT_ROOT']) require_once( $_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/wp-load.php');
else require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

global $wpdb;


$user_patronymic = $_POST["patronymic_input"];

if(empty($user_patronymic)) {
    http_response_code(400);
    echo "Пустое поле с отчеством";
    exit;
}

// Проверяем длину имени
if(strlen($user_patronymic) > 50)
{
    http_response_code(400);
    echo "Длина отчества не может превышать 50 символов";
    exit;
}

//TO-DO проверку на наличе знаков припинания

?>