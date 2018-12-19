<script type="text/javascript">
    jQuery("li.current-page-ancestor").addClass('current-menu-item');
</script>

<?php include ($_SERVER['DOCUMENT_ROOT'] . "/user_room/parts/content_head.php");?>

    <?php
        include(ASKUE_PLUGIN_DIR."pages/accounts_manage/accounts_manage_page.php");
    ?>

<?php include ($_SERVER['DOCUMENT_ROOT'] . "/user_room/parts/content_footer.php");?>
