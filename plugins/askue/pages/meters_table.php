<?php wp_enqueue_script('delete_ajax'); ?>

<script type="text/javascript">
    var edit_button_clicked = false;
    var expanded_energy_objects = [];
    var object_rows = [];
    var root_objects_ids = [];
    var rows_root = [];
    var rows_expand = [];
    var objects_parents = [];

    function detailsMeterClicked(url_meter_detail) {
        console.log('detailsMeterClicked ' + edit_button_clicked);
        if(!edit_button_clicked) {
            window.location.href=url_meter_detail;
        }

        edit_button_clicked = false;
    }

    function expandEnergyObject(energyObject_id) {

        if(typeof  expanded_energy_objects[energyObject_id] !== 'undefined') {
            expanded_energy_objects[energyObject_id] = !expanded_energy_objects[energyObject_id];
            //console.log('expandObject: ' + energyObject_id + " | " + expanded_energy_objects[energyObject_id]);
        }
        else {
            expanded_energy_objects[energyObject_id] = false;
            //console.log("arr el created: " + expanded_energy_objects[energyObject_id]);
        }

        var energy_object_div = document.getElementById("energy_object_div_" + energyObject_id);
        var expand_icon = document.getElementById("expand_icon_" + energyObject_id);
        if(expanded_energy_objects[energyObject_id]) {
            expand_icon.classList.remove('expand_icon_plus');
            expand_icon.classList.add('expand_icon_minus');
            //$("#expand_icon_"+energyObject_id).addClass("expand_icon_minus");
            energy_object_div.style.visibility = "visible";
            energy_object_div.style.height = "auto";
        }
        else {
            expand_icon.classList.remove('expand_icon_minus');
            expand_icon.classList.add('expand_icon_plus');
            //$("#expand_icon_"+energyObject_id).addClass("expand_icon_plus");
            energy_object_div.style.visibility = "hidden";
            energy_object_div.style.height = "0px";
        }

    }

    function expandEnergyObjectRow(energyObject_id) {

        //console.log('object_id: ' + energyObject_id);
        //console.log(object_rows[energyObject_id]);

        var expand_rows = object_rows[energyObject_id];
        //var root_object_id = Object.keys(object_rows)[0];
        //root_objects_ids
        var root_object_id = root_objects_ids[energyObject_id];
        var object_table = document.getElementById("energy_object_table_" + root_object_id);
        var object_rows_root = rows_root[root_object_id];
        //console.log(object_rows_root);
        //console.log(rows_expand[root_object_id]);
        //console.log('root_objects_ids: ' + root_object_id);
        //console.log('root_object_id: ' + root_object_id);

        if(typeof  expanded_energy_objects[energyObject_id] !== 'undefined') {
            expanded_energy_objects[energyObject_id] = !expanded_energy_objects[energyObject_id];
            //console.log('expandObject: ' + energyObject_id + " | " + expanded_energy_objects[energyObject_id]);
        }
        else {
            expanded_energy_objects[energyObject_id] = false;
            //console.log("arr el created: " + expanded_energy_objects[energyObject_id]);
        }

        //console.log(object_table.rows[1]);
        //object_table.rows[1].style.visibility = "hidden";
        var expand_icon = document.getElementById("expand_icon_" + energyObject_id);

        /*console.log('expand_rows: ' + expand_rows);

        if(expanded_energy_objects[energyObject_id]) {
            expand_icon.classList.remove('expand_icon_plus');
            expand_icon.classList.add('expand_icon_minus');
            for(var i=0; i<expand_rows.length; i++) {

                object_table.rows[expand_rows[i]+1].style.display = "";
                object_table.rows[expand_rows[i]+1].style.height = "auto";
            }
        }
        else {
            expand_icon.classList.remove('expand_icon_minus');
            expand_icon.classList.add('expand_icon_plus');
            for(var i=0; i<expand_rows.length; i++) {

                object_table.rows[expand_rows[i]+1].style.display  = "none";
                object_table.rows[expand_rows[i]+1].style.height = "0px";
                //console.log('expand_rows: ' + expand_rows[i]);
            }
        }*/

        if(expanded_energy_objects[energyObject_id]) {
            expand_icon.classList.remove('expand_icon_plus');
            expand_icon.classList.add('expand_icon_minus');
        }
        else {
            expand_icon.classList.remove('expand_icon_minus');
            expand_icon.classList.add('expand_icon_plus');
        }

        // -------------- right:

        //console.log('expand_rows: ' + expand_rows);

        //console.log('rows_expand: ' + rows_expand);

        for(var i=0; i<expand_rows.length; i++) {

            var row_index = expand_rows[i];

            //console.log('-----------------');
            //console.log('row_index: ' + row_index);
            //console.log('object_rows_root: ' + object_rows_root[row_index]);
            //console.log('energyObject_id: ' + energyObject_id);

            var is_expand_row = expanded_energy_objects[energyObject_id];

            // ------------ new method:

            var row_root_id = object_rows_root[row_index];
            var row_object_id = object_rows_root[row_index];

            //console.log('row_parent_id: ' + row_parent_id);

            if(is_expand_row) {
                if(row_object_id != energyObject_id) {
                    if(typeof  expanded_energy_objects[row_object_id] !== 'undefined') {

                        if(expanded_energy_objects[row_object_id] == is_expand_row) {
                            var row_parent_id =  objects_parents[row_object_id];
                            while(typeof objects_parents[row_parent_id] !== 'undefined' && typeof expanded_energy_objects[row_parent_id] !== 'undefined' && expanded_energy_objects[row_parent_id] == is_expand_row) {
                                is_expand_row = expanded_energy_objects[row_parent_id];
                                row_parent_id =  objects_parents[row_parent_id];
                            }

                            if(typeof expanded_energy_objects[row_parent_id] !== 'undefined') {
                                is_expand_row = expanded_energy_objects[row_parent_id];
                            }
                        }
                        else {
                            is_expand_row = expanded_energy_objects[row_object_id];
                        }
                        //console.log('find1 row_parent_id: ' + row_object_id + ' | is_expand: ' + is_expand_row);
                    }
                    else {
                        /*var row_parent_id =  objects_parents[row_object_id];
                        while(typeof expanded_energy_objects[row_parent_id] === 'undefined' && typeof objects_parents[row_parent_id] !== 'undefined') {
                            row_parent_id =  objects_parents[row_parent_id];
                        }

                        //console.log('find2 row_parent_id: ' + row_parent_id);
                        is_expand_row = expanded_energy_objects[row_parent_id];
                        row_parent_id = objects_parents[row_parent_id];
                        while (expanded_energy_objects[row_parent_id] == is_expand_row && typeof objects_parents[row_parent_id] !== 'undefined' && typeof expanded_energy_objects[row_parent_id] !== 'undefined') {
                            row_parent_id = objects_parents[row_parent_id];
                            is_expand_row = expanded_energy_objects[row_parent_id];
                        }*/

                        var row_parent_id =  objects_parents[row_object_id];
                        //console.log('find2: ' + row_parent_id + ' | objects_parents['+row_parent_id + ']: ' + objects_parents[row_parent_id] + ' | expanded_energy_objects['+row_parent_id+']: ' + expanded_energy_objects[row_parent_id]);
                        while(typeof expanded_energy_objects[row_parent_id] === 'undefined' && typeof objects_parents[row_parent_id] !== 'undefined') {
                            row_parent_id = objects_parents[row_parent_id];
                            console.log('change row parent: ' + row_parent_id);
                        }

                        //console.log('find2: ' + row_parent_id);

                        while(typeof objects_parents[row_parent_id] !== 'undefined' && typeof expanded_energy_objects[row_parent_id] !== 'undefined' && expanded_energy_objects[row_parent_id] == is_expand_row) {
                            is_expand_row = expanded_energy_objects[row_parent_id];
                            row_parent_id =  objects_parents[row_parent_id];
                        }

                        if(typeof expanded_energy_objects[row_parent_id] !== 'undefined') {
                            is_expand_row = expanded_energy_objects[row_parent_id];
                        }
                        //console.log('find2 row_parent_id: ' + row_parent_id + ' | is_expand: ' + is_expand_row);
                    }
                }
            }

            if(is_expand_row) {
                object_table.rows[expand_rows[i]+1].style.display  = "";
                object_table.rows[expand_rows[i]+1].style.height = "auto";
            }
            else {
                object_table.rows[expand_rows[i]+1].style.display  = "none";
                object_table.rows[expand_rows[i]+1].style.height = "0px";
            }

        }

        // ----------- expand all child objects when parent is expanded (костыль) --------
        /*if(!is_expand_row) {
            var cursorObject_id = energyObject_id;
            for (var object_id in objects_parents) {
                if (objects_parents[object_id] == cursorObject_id) {
                    //console.log('child_obj_id: ' + object_id);
                    cursorObject_id = object_id;
                    expanded_energy_objects[object_id] = is_expand_row;

                    var expand_child_icon = document.getElementById("expand_icon_" + object_id);
                    expand_child_icon.classList.remove('expand_icon_minus');
                    expand_child_icon.classList.add('expand_icon_plus');
                }
            }
        }*/

        /*console.log('expanded_energy_objects:')
        for(var key in expanded_energy_objects) {
            console.log('obj_id: ' + key + ' | ' + expanded_energy_objects[key]);
        }*/
        //console.log('expanded_energy_objects: ' + expanded_energy_objects);
    }

</script>

<?php

$count_rows = 0;
$object_rows = array();
$root_objects_ids = array();
$rows_root = array();
$objects_parents = array();

function showNestedObjects($nestedLevel, $energyObjectId, $dataController, $nestedParentMetersCounts=[], $user_id=-1, $objects_past_ids=array(), $root_object_id) {

    $nestedEnergyObjects = $dataController->selectNestedEnergyObjects($energyObjectId, $user_id);
    $nestedParentMeters = $dataController->selectMetersList($energyObjectId);

    //echo "user_id: ".$user_id." | count(nested_objects)=".count($nestedEnergyObjects);
    $objects_past_ids[] = $energyObjectId;
    $GLOBALS["root_objects_ids"][$energyObjectId] = $root_object_id;
    //$root_objects_ids[] = $root_object_id;

    $parentEnergyObject = $dataController->selectEnergyObject($energyObjectId);
    if(!empty($parentEnergyObject->getMeterId())) {
        for($i = 0; $i <count($nestedParentMeters); $i++) {
            if($nestedParentMeters[$i]->getId() == $parentEnergyObject->getMeterId()) {
                unset($nestedParentMeters[$i]);
            }
        }

        addObjectsRows($objects_past_ids);
        addRowsRoots($root_object_id, $energyObjectId);
        printMeterRow($dataController->selectMeter($parentEnergyObject->getMeterId()), $nestedLevel, (count($nestedEnergyObjects) == 0 && count($nestedParentMeters) == 0), $dataController->selectMeterLastValue($parentEnergyObject->getMeterId()), $nestedParentMetersCounts);
        //addObjectsRows($objects_past_ids);
        //$GLOBALS['object_rows'][$energyObjectId][] = $GLOBALS['count_rows'];
    }


    if($nestedEnergyObjects != null && count($nestedEnergyObjects) > 0) {
        $j = 0;

        foreach($nestedEnergyObjects as $nestedEnergyObject) {
            //$nestedMeters = $dataController->selectMetersList($nestedEnergyObject->getId());
            $nestedParentMetersCounts[$nestedLevel] = count($nestedParentMeters);

            $GLOBALS['objects_parents'][$nestedEnergyObject->getId()] = $energyObjectId;
            addObjectsRows($objects_past_ids);
            addRowsRoots($root_object_id, $energyObjectId);
            //$GLOBALS['object_rows'][$energyObjectId][] = $GLOBALS['count_rows'];
            printEnergyObjectRow($nestedEnergyObject, $nestedLevel, ($j==count($nestedEnergyObjects)-1) && count($nestedParentMeters) == 0, $nestedParentMetersCounts, $dataController->selectEnergyObjectValue($nestedEnergyObject), $dataController->countEnergyObjectChildElements($nestedEnergyObject->getId()));

            showNestedObjects($nestedLevel+1, $nestedEnergyObject->getId(), $dataController, $nestedParentMetersCounts, $user_id, $objects_past_ids, $root_object_id);

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
            addObjectsRows($objects_past_ids);
            addRowsRoots($root_object_id, $energyObjectId);
            //$GLOBALS['object_rows'][$energyObjectId][] = $GLOBALS['count_rows'];
            printMeterRow($nestedParentMeter, $nestedLevel, ($i==count($nestedParentMeters)-1), $dataController->selectMeterLastValue($nestedParentMeter->getId()), $nestedParentMetersCounts);
            $i++;
        }
    }
}

function addObjectsRows($objects_past_ids) {
    foreach($objects_past_ids as $object_past_id) {
        $GLOBALS['object_rows'][$object_past_id][] = $GLOBALS['count_rows'];
    }
}

function addRowsRoots($energyObject_id, $rootObject_id) {
    $GLOBALS['rows_root'][$energyObject_id][$GLOBALS['count_rows']] = $rootObject_id;
}

function printEnergyObjectRow($energyObject, $nestedLevel, $is_last_row, $parent_meters_count, $object_value, $child_elements_count) {
    //$rand_num = number_format (30000/rand(20, 50), 3);
    $energy_object_value = 0;

    $energy_object_edit_url_admin = site_url('/wp-admin/admin.php?page=add_energy_object&edit='.$energyObject->getId());
    $energy_object_edit_url_user_room = site_url('/user-room/meters-management/add-energy-object?edit='.$energyObject->getId());

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
                    echo '<div class="nested_name"><div class="nested_name_text">'.$energyObject->getName(). '</div>';
                    if($child_elements_count > 0) {
                        echo '<div class="expand_icon_minus" id="expand_icon_'.$energyObject->getId().'" onclick="expandEnergyObjectRow('.$energyObject->getId().')"></div>';
                    }
                    echo '</div></div></td>
                    <td></td>
                    <td style="text-align:right; padding-right:10px;">'.$energy_object_value.'</td>';
    if(ACCESS_LEVEL == 2 || ACCESS_LEVEL == 3) {
        echo '         <td>
                        <div style="padding-top:6px;">
                            <div style="display: inline-block;">';
                                if(is_admin())
                                    echo '<div class="edit-button" onclick="edit_button_clicked = true; location.href=\''.$energy_object_edit_url_admin.';\'"></div>';
                                else
                                    echo '<div class="edit-button" onclick="edit_button_clicked = true; location.href=\''.$energy_object_edit_url_user_room.';\'"></div>';
                            echo '</div>
                            <div style="display: inline-block; padding-left:5px;">
                                <div class="delete-button" onclick="edit_button_clicked = true; delete_energy_object('.$energyObject->getId().',\''. $energyObject->getName().'\')"></div>
                            </div>
                        </div>
                    </td>
                </tr>';
    }
    $GLOBALS['count_rows']++;
}

function printMeterRow($meter, $nestedLevel, $is_last_row, $last_value, $parent_meters_count) {
    //$rand_num = number_format (30000/rand(20, 50), 3);
    $last_value_val = 0;
    if($last_value != null) $last_value_val = $last_value->getValue();

    $meter_edit_url_admin = site_url('/wp-admin/admin.php?page=add_meter&edit='.$meter->getId());
    $meter_edit_url_user_room = site_url('/user-room/meters-management/add-meter?edit='.$meter->getId());

    $padding_left = 10+10*$nestedLevel;

    if($nestedLevel > 1) $padding_left = 20;

    if(is_admin()) {
        $url_meter_detail = site_url('/wp-admin/admin.php?page=meter_details&meter='.$meter->getId());
    }
    else {
        $url_meter_detail = site_url('/user-room/meter-management/meter-details?meter='.$meter->getId());
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
                    echo '<div class="nested_name"><div class="nested_name_text">'.$meter->getName(). '</div></div></div></td>
                    <td>'.$meter->getNum().'</td>
                    <td style="text-align:right; padding-right:10px;">'.$last_value_val.'</td>';
    if(ACCESS_LEVEL == 2 || ACCESS_LEVEL == 3) {
    echo '         <td>
                        <div style="padding-top:6px;">
                            <div style="display: inline-block;">';
                                if(is_admin())
                                    echo '<div class="edit-button" onclick="edit_button_clicked = true; location.href=\''.$meter_edit_url_admin.';\'"></div>';
                                else
                                    echo '<div class="edit-button" onclick="edit_button_clicked = true; location.href=\''.$meter_edit_url_user_room.';\'"></div>';

                            echo '</div>
                            <div style="display: inline-block; padding-left:5px;">
                                <div class="delete-button" onclick="edit_button_clicked = true; delete_meter('.$meter->getId().',\''. $meter->getName().'\');"></div>
                            </div>
                        </div>
                    </td>
                </tr>';
    }
    $GLOBALS['count_rows']++;
}

?>

<?php //echo "access_level = ".ACCESS_LEVEL; ?>

<?php foreach ($energyObjectsList as $energyObject): ?>
    <?php
    $customer = $dataController->selectCustomer($energyObject->getCustomerId());

    $energy_object_edit_url_admin = site_url('/wp-admin/admin.php?page=add_energy_object&edit='.$energyObject->getId());
    $energy_object_edit_url_user_room = site_url('/user-room/meters-management/add-energy-object?edit='.$energyObject->getId());
    $energy_object_value = $dataController->selectEnergyObjectValue($energyObject);

    $child_elements_count = $dataController->countEnergyObjectChildElements($energyObject->getId());
    $GLOBALS['count_rows'] = 0;

    ?>
    <table class="energy-object-top-table" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <div class="energy-object-title"><?=$energyObject->getName();?></div>
                <?php if($child_elements_count > 0 ): ?>
                    <div class="expand_icon_container"><div class="expand_icon_minus" id="expand_icon_<?=$energyObject->getId()?>" onclick="expandEnergyObject(<?=$energyObject->getId()?>)"></div></div>
                <?php endif; ?>
                <div style="display:inline-block;">
                    [<a href=""><?=$customer->getName();?></a>]
                </div>
            </td>
            <td align="right" style="padding-right:10px;">Сумма <?=$energy_object_value;?> Кв/ч</td>
            <?php if(ACCESS_LEVEL == 2 || ACCESS_LEVEL == 3): ?>
            <td align="center" width="6%">
                <div style="padding-top:6px;">
                    <div style="display: inline-block;">
                        <?php if(is_admin()):?>
                            <div class="edit-object-button" onclick="location.href='<?=$energy_object_edit_url_admin?>'"></div>
                        <?php else: ?>
                            <div class="edit-object-button" onclick="location.href='<?=$energy_object_edit_url_user_room?>'"></div>
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
    <div class="energy-object-content" id="energy_object_div_<?=$energyObject->getId()?>">
        <table class="energy-object-table" id="energy_object_table_<?=$energyObject->getId()?>" cellpadding="0" cellspacing="0">
            <tr>
                    <th width="33%" style="text-align:left; padding-left:10px;">Название</th>
                    <th>Счетчик №</th>
                    <th width="33%" style="text-align:right; padding-right:10px;">Потребление (Кв/ч)</th>
                    <?php if(ACCESS_LEVEL == 2 || ACCESS_LEVEL == 3): ?>
                        <th width="6%"></th>
                    <?php endif; ?>
            </tr>

            <?php
            if(ACCESS_LEVEL==3) {
                //echo "show nested obj 1";
                showNestedObjects(0, $energyObject->getId(), $dataController,  array(), -1, array(), $energyObject->getId());
            }
            else {
                //echo "show nested obj 2<br>".print_r($customer);
                showNestedObjects(0, $energyObject->getId(), $dataController, array(), $customer->getId(), array(), $energyObject->getId());
            }
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

    <?php
    //print_r($GLOBALS['object_rows'][20]);
    /*foreach($GLOBALS['object_rows'] as $object_row_key => $object_row_array) {
        echo "object_id: " . $object_row_key . "<br>";
        foreach($object_row_array as $row_num) {
            echo "------- row_num: " .$row_num . "<br>";
        }
    }*/

    /*foreach($GLOBALS['root_objects_ids'] as $object_id => $root_object_id) {
        echo "obj_id: ".$object_id ." | root_object_id: " . $root_object_id ."<br>";
    }*/
    //print_r($GLOBALS['root_objects_ids']);
    /*foreach($GLOBALS['rows_root'] as $root_object_id => $rows_array) {
        echo "root object_id: " . $root_object_id . "<br>";
        foreach($rows_array as $row_num => $object_id) {
            echo "------- row_num: " .$row_num . " | object_id: ".$object_id."<br>";
        }
    }*/

    /*foreach($GLOBALS['objects_parents'] as $object_id => $parent_id) {
        echo "obj_id: " . $object_id . " | parent_id: " . $parent_id . "<br>";
    }*/
    ?>
<?php endforeach; ?>

<script>
    jQuery(document).ready(function($) {
        object_rows = <?=json_encode($GLOBALS["object_rows"])?>;
        root_objects_ids = <?=json_encode($GLOBALS["root_objects_ids"])?>;
        rows_root = <?=json_encode($GLOBALS["rows_root"])?>;
        objects_parents = <?=json_encode($GLOBALS["objects_parents"])?>;
        //console.log(object_rows[20]);
        //console.log(root_objects_ids);
        //console.log('elem: ' + root_objects_ids[38]);
        //console.log(objects_parents);
    });
</script>
