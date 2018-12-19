<?php
include( $_SERVER['DOCUMENT_ROOT'] . '/user_room/header_settings.php' ); // Проверка авторизации

get_header();
include( $_SERVER['DOCUMENT_ROOT'] . '/user_room/parts/accounts_content.php' );
get_footer();
?>