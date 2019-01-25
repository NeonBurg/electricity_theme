<?php
    require_once(ASKUE_PLUGIN_DIR . "models/DataController.php");
    //require_once(ASKUE_PLUGIN_DIR . "models/Meter.php");
    //require_once(ASKUE_PLUGIN_DIR . "models/EnergyObject.php");
?>

<?php

global $wpdb;
$dataController = new DataController($wpdb);
//$energyObjectsList = $dataController->selectEnergyObjects();
$energyObjectsList = $dataController->selectRootEnergyObjects();

if(is_admin()) define('ACCESS_LEVEL', 3);
?>

<h1>Автоматизированная система контроля и учета электроэнергии (АСКУЭ)</h1>

<div class="askue-admin-content">

    <!-- buttons -->
    <?php include(ASKUE_PLUGIN_DIR."pages/manage_buttons_table.php"); ?>


    <?php include(ASKUE_PLUGIN_DIR."pages/meters_table.php"); ?>

</div>