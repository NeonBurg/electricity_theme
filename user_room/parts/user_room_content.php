<?php include ($_SERVER['DOCUMENT_ROOT'] . "/user_room/parts/content_head.php"); ?>

<?php
    require_once(ASKUE_PLUGIN_DIR . "models/DataController.php");
    require_once(ASKUE_PLUGIN_DIR . "models/Meter.php");
    require_once(ASKUE_PLUGIN_DIR . "models/EnergyObject.php");

    $account_id = $_COOKIE["id"];

    global $wpdb;
    $dataController = new DataController($wpdb);
    $energyObjectsList = $dataController->selectEnergyObjectsForAccount($account_id);
?>


    <!--<div class="energy-object-top">
        <div class="energy-object-title">ТП 76</div>
    </div>-->

<?php foreach ($energyObjectsList as $energyObject): ?>

    <div class="energy-object-top">
        <div class="energy-object-title"><?=$energyObject->getName();?></div>
    </div>

    <div class="energy-object-content">
        <table class="energy-object-table" cellpadding="0" cellspacing="0">
            <tr>
                <th width="33%" style="text-align:left; padding-left:50px;">Название</th>
                <th>Счетчик №</th>
                <th width="33%" style="text-align:right; padding-right:50px;">Потребление (Кв/ч)</th>
            </tr>

            <?php foreach ($energyObject->getMetersList() as $meter): ?>

                <tr>
                    <td style="text-align:left; padding-left:50px;"><?=$meter->getName();?></td>
                    <td><?=$meter->getNum();?></td>
                    <td style="text-align:right; padding-right:50px;"><?php $rand_num = 30000/rand(20, 50); echo number_format ($rand_num, 3); ?></td>
                </tr>

            <?php endforeach; ?>
        </table>

        <?php if(count($energyObject->getMetersList()) == 0): ?>
            <table class="empty-list-notif-table" cellspacing="0" cellpadding="0">
                <tr>
                    <td>Пустой список счетчиков</td>
                </tr>
            </table>
        <?php endif; ?>
    </div>

    <div class="energy-object-separator">&nbsp;</div>

<?php endforeach; ?>

<?php include ($_SERVER['DOCUMENT_ROOT'] . "/user_room/parts/content_footer.php");?>