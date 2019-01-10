<?php include ($_SERVER['DOCUMENT_ROOT'] . "/user_room/parts/content_head.php"); ?>

<?php
    require_once(ASKUE_PLUGIN_DIR . "models/DataController.php");
    require_once(ASKUE_PLUGIN_DIR . "models/Meter.php");
    require_once(ASKUE_PLUGIN_DIR . "models/EnergyObject.php");

    $account_id = $_COOKIE["id"];

    global $wpdb;
    $dataController = new DataController($wpdb);
    if($access_level == 2 || $access_level == 3) {
        //$energyObjectsList = $dataController->selectEnergyObjects();
        $energyObjectsList = $dataController->selectRootEnergyObjects();
    }
    else if($access_level == 1) {
        $energyObjectsList = $dataController->selectEnergyObjectsForAccount($account_id);
    }
?>

<h1>Личный кабинет</h1>

<?php include(ASKUE_PLUGIN_DIR."pages/manage_buttons_table.php"); ?>

<?php include(ASKUE_PLUGIN_DIR."pages/meters_table.php") ?>

<?php include ($_SERVER['DOCUMENT_ROOT'] . "/user_room/parts/content_footer.php");?>