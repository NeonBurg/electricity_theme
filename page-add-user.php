<?php
include( ABSPATH . '/user_room/header_settings.php' ); // Проверка авторизации

get_header();

?>

    <script type="text/javascript">
        jQuery("li.current-page-ancestor").addClass('current-menu-item');
    </script>

<?php

include (ABSPATH . "/user_room/parts/content_head.php");

include(ASKUE_PLUGIN_DIR."pages/accounts_manage/add_user/add_user_page.php");

include (ABSPATH . "/user_room/parts/content_footer.php");

get_footer();
?>