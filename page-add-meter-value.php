<?php
include( $_SERVER['DOCUMENT_ROOT'] . '/user_room/header_settings.php' ); // Проверка авторизации

get_header();

?>

    <script type="text/javascript">
        jQuery("li.current-page-ancestor").addClass('current-menu-item');
    </script>

<?php

include ($_SERVER['DOCUMENT_ROOT'] . "/user_room/parts/content_head.php");

include(ASKUE_PLUGIN_DIR."pages/meter_details/add_meter_value/add_meter_value_page.php");

include ($_SERVER['DOCUMENT_ROOT'] . "/user_room/parts/content_footer.php");

get_footer();
?>