<?php wp_enqueue_script('delete_ajax'); ?>

<script type="text/javascript">
    var edit_button_clicked = false;

    function detailsMeterClicked(url_meter_detail) {
        console.log('detailsMeterClicked ' + edit_button_clicked);
        if(!edit_button_clicked) {
            window.location.href=url_meter_detail;
        }

        edit_button_clicked = false;
    }
</script>

<?php

function showNestedObjects($nestedLevel, $energyObjectId, $dataController, $nestedParentMetersCounts=[], $user_id=-1) {

    $nestedEnergyObjects = $dataController->selectNestedEnergyObjects($energyObjectId, $user_id);
    $nestedParentMeters = $dataController->selectMetersList($energyObjectId);

    $parentEnergyObject = $dataController->selectEnergyObject($energyObjectId);
    if(!empty($parentEnergyObject->getMeterId())) {
        for($i = 0; $i <count($nestedParentMeters); $i++) {
            if($nestedParentMeters[$i]->getId() == $parentEnergyObject->getMeterId()) {
                unset($nestedParentMeters[$i]);
            }
        }

        printMeterRow($dataController->selectMeter($parentEnergyObject->getMeterId()), $nestedLevel, (count($nestedEnergyObjects) == 0 && count($nestedParentMeters) == 0), $dataController->selectMeterLastValue($parentEnergyObject->getMeterId()), $nestedParentMetersCounts);
    }


    if($nestedEnergyObjects != null && count($nestedEnergyObjects) > 0) {
        $j = 0;

        foreach($nestedEnergyObjects as $nestedEnergyObject) {
            //$nestedMeters = $dataController->selectMetersList($nestedEnergyObject->getId());
            $nestedParentMetersCounts[$nestedLevel] = count($nestedParentMeters);

            printEnergyObjectRow($nestedEnergyObject, $nestedLevel, ($j==count($nestedEnergyObjects)-1) && count($nestedParentMeters) == 0, $nestedParentMetersCounts, $dataController->selectEnergyObjectValue($nestedEnergyObject));

            showNestedObjects($nestedLevel+1, $nestedEnergyObject->getId(), $dataController, $nestedParentMetersCounts);

            /*if($nestedMeters != null && count($nestedMeters) > 0) {
                foreach($nestedMeters as $nestedMeter) {
                    printMeterRow($nestedMeter);
                }
            }*/
            $j++;
        }
    }

    //echo 'count(nestedMeters) = '.count($nestedMeters).' | nestedLevel = '.$nestedLevel.'<br>';

    if($nestedParentMeters != null && count($nestedParentMeters) > 0) {
        $i=0;
        foreach($nestedParentMeters as $nestedParentMeter) {
            printMeterRow($nestedParentMeter, $nestedLevel, ($i==count($nestedParentMeters)-1), $dataController->selectMeterLastValue($nestedParentMeter->getId()), $nestedParentMetersCounts);
            $i++;
        }
    }
}

function printEnergyObjectRow($energyObject, $nestedLevel, $is_last_row, $parent_meters_count, $object_value) {
    //$rand_num = number_format (30000/rand(20, 50), 3);
    $energy_object_value = 0;

    if($object_value != null) $energy_object_value = $object_value;

    $padding_left = 10 + 10*$nestedLevel;

    if($nestedLevel > 1) $padding_left = 20;

    echo '<tr>
                    <td style="text-align:left; padding-left:'.$padding_left.'px;">';
                    echo '<div style="display:table-row;">';
                    if($nestedLevel > 0) {
                        if($nestedLevel > 1) {
                            for($i=1; $i<$nestedLevel; $i++) {
                                if((isset($parent_meters_count[$i]) && $parent_meters_count[$i] > 0)) {
                                    echo '<div class="tree_line"></div>';
                                }

                                echo '<div style="width:20px; display:table-cell;"></div>';
                            }
                        }
                        if($is_last_row)
                            echo '<div class="tree_end"></div>';
                        else
                            echo '<div class="tree_middle"></div>';
                    }
                    echo '<div class="nested_name"><div class="nested_name_text">'.$energyObject->getName().'</div></div></div></td>
                    <td></td>
                    <td style="text-align:right; padding-right:10px;">'.$energy_object_value.'</td>';
    //if($access_level == 2 || $access_level == 3) {
        echo '         <td>
                        <div style="padding-top:6px;">
                            <div style="display: inline-block;">';
                                if(is_admin())
                                    echo '<div class="edit-button" onclick="edit_button_clicked = true; location.href=\'/wp-admin/admin.php?page=add_energy_object&edit='.$energyObject->getId().';\'"></div>';
                                else
                                    echo '<div class="edit-button" onclick="edit_button_clicked = true; location.href=\'/user-room/meters-management/add-energy-object?edit='.$energyObject->getId().';\'"></div>';
                            echo '</div>
                            <div style="display: inline-block; padding-left:5px;">
                                <div class="delete-button" onclick="edit_button_clicked = true; delete_energy_object('.$energyObject->getId().',\''. $energyObject->getName().'\')"></div>
                            </div>
                        </div>
                    </td>
                </tr>';
    //}
}

function printMeterRow($meter, $nestedLevel, $is_last_row, $last_value, $parent_meters_count) {
    //$rand_num = number_format (30000/rand(20, 50), 3);
    $last_value_val = 0;
    if($last_value != null) $last_value_val = $last_value->getValue();

    $padding_left = 10+10*$nestedLevel;

    if($nestedLevel > 1) $padding_left = 20;

    if(is_admin()) {
        $url_meter_detail = '/wp-admin/admin.php?page=meter_details&meter='.$meter->getId();
    }
    else {
        $url_meter_detail = '/user-room/meter-management/meter-details?meter='.$meter->getId();
    }

    //echo '<tr onclick="window.location.href=\''.$url_meter_detail.'\';">
    echo '<tr onclick="detailsMeterClicked(\''.$url_meter_detail.'\');">
                    <td style="text-align:left; padding-left:'.$padding_left.'px;">';
                    echo '<div style="display:table-row;">';
                    if($nestedLevel > 0) {
                        if($nestedLevel > 1) {
                            for($i=1; $i<$nestedLevel; $i++) {
                                if((isset($parent_meters_count[$i]) && $parent_meters_count[$i] > 0)) {
                                    echo '<div class="tree_line"></div>';
                                }

                                echo '<div style="width:20px; display:table-cell;"></div>';
                                //echo '<div class="tree_line"></div><div style="width:20px; display:table-cell;"></div>';
                            }
                        }
                        if($is_last_row)
                            echo '<div class="tree_end"></div>';
                        else
                            echo '<div class="tree_middle"></div>';
                    }
                    echo '<div class="nested_name"><div class="nested_name_text">'.$meter->getName().'</div></div></div></td>
                    <td>'.$meter->getNum().'</td>
                    <td style="text-align:right; padding-right:10px;">'.$last_value_val.'</td>';
    //if($access_level == 2 || $access_level == 3) {
    echo '         <td>
                        <div style="padding-top:6px;">
                            <div style="display: inline-block;">';
                                if(is_admin())
                                    echo '<div class="edit-button" onclick="edit_button_clicked = true; location.href=\'/wp-admin/admin.php?page=add_meter&edit='.$meter->getId().';\'"></div>';
                                else
                                    echo '<div class="edit-button" onclick="edit_button_clicked = true; location.href=\'/user-room/meters-management/add-meter?edit='.$meter->getId().';\'"></div>';

                            echo '</div>
                            <div style="display: inline-block; padding-left:5px;">
                                <div class="delete-button" onclick="edit_button_clicked = true; delete_meter('.$meter->getId().',\''. $meter->getName().'\');"></div>
                            </div>
                        </div>
                    </td>
                </tr>';
    //}
}

?>

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
                    <th width="33%" style="text-align:left; padding-left:10px;">Название</th>
                    <th>Счетчик №</th>
                    <th width="33%" style="text-align:right; padding-right:10px;">Потребление (Кв/ч)</th>
                    <?php if($access_level == 2 || $access_level == 3): ?>
                        <th width="6%"></th>
                    <?php endif; ?>
            </tr>

            <?php
            if($access_level==3)
                showNestedObjects(0, $energyObject->getId(), $dataController);
            else
                showNestedObjects(0, $energyObject->getId(), $dataController, $customer->getId());
            ?>
        </table>

        <?php if(count($energyObject->getMetersList()) == 0 && count($dataController->selectNestedEnergyObjects($energyObject->getId())) == 0): ?>
            <table class="empty-list-notif-table" cellspacing="0" cellpadding="0">
                <tr>
                    <td>Пустой список счетчиков</td>
                </tr>
            </table>
        <?php endif; ?>
    </div>

    <div class="energy-object-separator">&nbsp;</div>

<?php endforeach; ?>