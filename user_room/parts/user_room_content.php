<?php include ($_SERVER['DOCUMENT_ROOT'] . "/user_room/parts/content_head.php"); ?>

<?php
    require_once(ASKUE_PLUGIN_DIR . "models/DataController.php");

    $account_id = $_COOKIE["id"];

    global $wpdb;
    $dataController = new DataController($wpdb);

    $currentValue = 0;

    if($access_level == 3) {
        //$energyObjectsList = $dataController->selectEnergyObjects();
        $energyObjectsList = $dataController->selectRootEnergyObjects();
        $currentValue = $dataController->selectCurrentElectricityValue();
    }
    else if($access_level == 1 || $access_level == 2) {
        $energyObjectsList = $dataController->selectEnergyObjectsForAccount($account_id);
        $currentValue = $dataController->selectCurrentElectricityValue($account_id);
    }

    $customer = $dataController->selectCustomerByAccountId($_COOKIE["id"]);
    $customer_group = $dataController->selectUserGroup($customer->getGroupId());
?>

<h1>Личный кабинет</h1>

<?php include(ASKUE_PLUGIN_DIR."pages/user_info_table.php"); ?>

<div class="meters_table_container">
    <?php include(ASKUE_PLUGIN_DIR."pages/manage_buttons_table.php"); ?>

    <?php include(ASKUE_PLUGIN_DIR."pages/meters_table.php") ?>
</div>

<?php include ($_SERVER['DOCUMENT_ROOT'] . "/user_room/parts/content_footer.php");?>