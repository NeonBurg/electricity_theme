<?php

require_once(ASKUE_PLUGIN_DIR . "models/DataController.php");

global $wpdb;
$dataController = new DataController($wpdb);

?>

<h1>Управление пользователями</h1>

<div class="askue-admin-content">

    <?php include("groups_table.php"); ?>

</div>
