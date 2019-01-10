<?php
wp_enqueue_script('delete_ajax');
require_once(ASKUE_PLUGIN_DIR . "models/DataController.php");

global $wpdb;
$dataController = new DataController($wpdb);
if(is_admin()) $access_level = 3;

?>

<h1>Управление пользователями</h1>

<div class="askue-admin-content">

    <?php include("groups_table.php"); ?>

    <?php include("accounts_table.php"); ?>

</div>
