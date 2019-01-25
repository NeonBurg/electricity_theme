<?php
include( ABSPATH . '/user_room/header_settings.php' ); // Проверка авторизации

//echo "server host = " . substr(dirname(__FILE__), 0, -strlen($_SERVER['SCRIPT_NAME']));
//echo "server host = " . PATH;

get_header();

include( ABSPATH . '/user_room/parts/user_room_content.php' );
get_footer();
?>