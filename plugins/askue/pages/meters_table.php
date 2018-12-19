<?php wp_enqueue_script('delete_ajax'); ?>

<?php foreach ($energyObjectsList as $energyObject): ?>

    <?php
    $customer = $dataController->selectCustomer($energyObject->getCustomerId());
    ?>
    <table class="energy-object-top-table">
        <tr>
            <td width="90%">
                <div class="energy-object-title"><?=$energyObject->getName();?></div>
                <div style="display:inline-block;">
                    [<a href=""><?=$customer->getName();?></a>]
                </div>
            </td>
            <?php if($access_level == 2 || $access_level == 3): ?>
            <td align="center" width="6%">
                <div style="padding-top:6px;">
                    <div style="display: inline-block;">
                        <?php if(is_admin()):?>
                            <div class="edit-object-button" onclick="location.href='/wp-admin/admin.php?page=add_energy_object&edit=<?=$energyObject->getId();?>'"></div>
                        <?php else: ?>
                            <div class="edit-object-button" onclick="location.href='/user-room/meters-management/add-energy-object?edit=<?=$energyObject->getId();?>'"></div>
                        <?php endif; ?>
                    </div>
                    <div style="display: inline-block; padding-left:5px;">
                        <div class="delete-object-button" onclick="delete_energy_object(<?=$energyObject->getId();?>, '<?=$energyObject->getName();?>')"></div>
                    </div>
                </div>
            </td>
            <?php endif; ?>
        </tr>
    </table>


    <!---------------- Таблица со списком счетчиков ----------------->
    <div class="energy-object-content">
        <table class="energy-object-table" cellpadding="0" cellspacing="0">
            <tr>
                <th width="33%" style="text-align:left; padding-left:50px;">Название</th>
                <th>Счетчик №</th>
                <th width="33%" style="text-align:right; padding-right:10px;">Потребление (Кв/ч)</th>
                <?php if($access_level == 2 || $access_level == 3): ?>
                    <th width="6%"></th>
                <?php endif; ?>
            </tr>

            <?php foreach ($energyObject->getMetersList() as $meter): ?>

                <tr>
                    <td style="text-align:left; padding-left:50px;"><?=$meter->getName();?></td>
                    <td><?=$meter->getNum();?></td>
                    <td style="text-align:right; padding-right:10px;">
                        <?php $rand_num = 30000/rand(20, 50); echo number_format ($rand_num, 3); ?>
                    </td>
                    <?php if($access_level == 2 || $access_level == 3): ?>
                    <td>
                        <div style="padding-top:6px;">
                            <div style="display: inline-block;">
                                <?php if(is_admin()):?>
                                    <div class="edit-button" onclick="location.href='/wp-admin/admin.php?page=add_meter&edit=<?=$meter->getId();?>'"></div>
                                <?php else: ?>
                                    <div class="edit-button" onclick="location.href='/user-room/meters-management/add-meter?edit=<?=$meter->getId();?>'"></div>
                                <?php endif; ?>
                            </div>
                            <div style="display: inline-block; padding-left:5px;">
                                <div class="delete-button" onclick="delete_meter(<?=$meter->getId();?>, '<?=$meter->getName();?>')"></div>
                            </div>
                        </div>
                    </td>
                    <?php endif; ?>
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