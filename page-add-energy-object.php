<?php
include( ABSPATH . '/user_room/header_settings.php' ); // Проверка авторизации

get_header();

?>

    <script type="text/javascript">
        jQuery("li.current-page-ancestor").addClass('current-menu-item');
    </script>

<?php

include (ABSPATH . "/user_room/parts/content_head.php");

include(ASKUE_PLUGIN_DIR."pages/add_energy_object/add_energy_object_page.php");

include (ABSPATH . "/user_room/parts/content_footer.php");

get_footer();
?>