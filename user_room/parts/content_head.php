<body>
<div class="user-room-content">

<div style="position:relative; float:right; margin-top:-15px; padding-bottom:10px;">
    <?php echo "Вы вошли как: <b>" . $_COOKIE['login'] . "</b>"; ?>
</div>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td style="vertical-align: top; padding-right:20px;">
            <?php
            if($access_level == 2 || $access_level == 3) {
                wp_nav_menu( array('theme_location' => 'custom-left-admin-menu',
                    'container_class' => 'left_menu_container',
                    'menu_class' => 'custom_left_menu'));
            }
            else {
                wp_nav_menu( array('theme_location' => 'custom-left-user-menu',
                    'container_class' => 'left_menu_container',
                    'menu_class' => 'custom_left_menu'));
            }
            ?>
        </td>

        <td style="vertical-align: top;" width="100%">