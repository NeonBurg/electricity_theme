<?php
include( $_SERVER['DOCUMENT_ROOT'] . '/user_room/header_settings.php' ); // Проверка авторизации

get_header();

include ($_SERVER['DOCUMENT_ROOT'] . "/user_room/parts/content_head.php");

include(ASKUE_PLUGIN_DIR."pages/add_energy_object/add_energy_object_page.php");

include ($_SERVER['DOCUMENT_ROOT'] . "/user_room/parts/content_footer.php");

get_footer();
?>