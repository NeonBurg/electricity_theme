<?php wp_enqueue_script('delete_ajax'); ?>

<script type="text/javascript">
    var edit_button_clicked = false;
    var expanded_energy_objects = [];
    //var object_rows = [];
    //var root_objects_ids = [];
    //var rows_root = [];
    //var rows_expand = [];
    //var objects_parents = [];

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

    function expandEnergyObjectUl(energyObject_id) {

        if(typeof  expanded_energy_objects[energyObject_id] !== 'undefined') {
            expanded_energy_objects[energyObject_id] = !expanded_energy_objects[energyObject_id];
            //console.log('expandObject: ' + energyObject_id + " | " + expanded_energy_objects[energyObject_id]);
        }
        else {
            expanded_energy_objects[energyObject_id] = false;
            //console.log("arr el created: " + expanded_energy_objects[energyObject_id]);
        }

        var energy_object_ul = document.getElementById("energy_object_ul_" + energyObject_id);
        var expand_icon = document.getElementById("expand_icon_" + energyObject_id);

        if(expanded_energy_objects[energyObject_id]) {
            expand_icon.classList.remove('expand_icon_plus');
            expand_icon.classList.add('expand_icon_minus');
            //$("#expand_icon_"+energyObject_id).addClass("expand_icon_minus");
            energy_object_ul.style.display = "block";
            //energy_object_ul.style.height = "auto";
        }
        else {
            expand_icon.classList.remove('expand_icon_minus');
            expand_icon.classList.add('expand_icon_plus');
            //$("#expand_icon_"+energyObject_id).addClass("expand_icon_plus");
            energy_object_ul.style.display = "none";
            //energy_object_ul.style.height = "0px";
        }
    }

    function resize_meter_table() {
        var table_width = document.getElementsByClassName("energy-object-table")[0].offsetWidth;
        var edit_button_width = 0;
        var padding_right = 20;

        var edit_buttons_container = document.getElementsByClassName("edit_buttons_container")[0];
        if(edit_buttons_container) {
            edit_button_width = edit_buttons_container.offsetWidth + 40;
            table_width -= edit_button_width;
        }
        console.log('table_width: ' + table_width);
        var all = document.getElementsByClassName('meter_num_column');
        padding_right += edit_button_width;
        for (var i = 0; i < all.length; i++) {
            all[i].style.width = table_width + "px";
            all[i].style.right = padding_right + "px";
        }
    }

    jQuery(document).ready(function($) {
        //document.getElementsByClassName('meter_num_column').style.width = document.getElementsByClassName('energy-object-table').style.width;
        resize_meter_table();
    });

</script>

<?php

$count_rows = 0;
$showed_objects = 0;


function showNestedObjects($nestedLevel, $energyObjectId, $dataController, $user_id=-1, $root_object_id) {

    $nestedEnergyObjects = $dataController->selectNestedEnergyObjects($energyObjectId, $user_id);
    $nestedParentMeters = $dataController->selectMetersList($energyObjectId);


    $parentEnergyObject = $dataController->selectEnergyObject($energyObjectId);
    if(!empty($parentEnergyObject->getMeterId())) {
        for($i = 0; $i <count($nestedParentMeters); $i++) {
            if($nestedParentMeters[$i]->getId() == $parentEnergyObject->getMeterId()) {
                unset($nestedParentMeters[$i]);
            }
        }

        printMeterRow($dataController->selectMeter($parentEnergyObject->getMeterId()), $nestedLevel, (count($nestedEnergyObjects) == 0 && count($nestedParentMeters) == 0), $dataController->selectMeterLastValue($parentEnergyObject->getMeterId()));
    }

    if($nestedEnergyObjects != null && count($nestedEnergyObjects) > 0) {
        $j = 0;

        foreach($nestedEnergyObjects as $nestedEnergyObject) {
            //$nestedMeters = $dataController->selectMetersList($nestedEnergyObject->getId());

            $GLOBALS['objects_parents'][$nestedEnergyObject->getId()] = $energyObjectId;


            printEnergyObjectRow($nestedEnergyObject, $nestedLevel, ($j==count($nestedEnergyObjects)-1) && count($nestedParentMeters) == 0, $dataController->countEnergyObjectChildElements($nestedEnergyObject->getId()), $dataController->selectEnergyObjectValue($nestedEnergyObject));
            showNestedObjects($nestedLevel+1, $nestedEnergyObject->getId(), $dataController, $user_id, $root_object_id);

            $j++;
        }
    }

    //echo 'count(nestedMeters) = '.count($nestedMeters).' | nestedLevel = '.$nestedLevel.'<br>';

    if($nestedParentMeters != null && count($nestedParentMeters) > 0) {
        $i=0;
        foreach($nestedParentMeters as $nestedParentMeter) {
            printMeterRow($nestedParentMeter, $nestedLevel, ($i==count($nestedParentMeters)-1), $dataController->selectMeterLastValue($nestedParentMeter->getId()));
            $i++;
        }
    }
}


// -------------------============== Show ENERGY OBJECT row ================----------------------
function printEnergyObjectRow($energyObject, $nestedLevel, $is_last_row, $child_elements_count, $object_value) {
    //$rand_num = number_format (30000/rand(20, 50), 3);
    $energy_object_value = 0;
    global $showed_objects;

    $energy_object_edit_url_admin = site_url('/wp-admin/admin.php?page=add_energy_object&edit='.$energyObject->getId());
    $energy_object_edit_url_user_room = site_url('/user-room/meters-management/add-energy-object?edit='.$energyObject->getId());

    if($object_value != null) $energy_object_value = $object_value;

    if($nestedLevel < $showed_objects) {
        while($nestedLevel < $showed_objects) {
            echo '</ul></li>';
            $showed_objects -= 1;
        }
    }

    echo '<li ';
    if($is_last_row) echo 'class="last"';
    echo '>';
    echo '<div class="energy_object_row"><div class="energy_object_column_name">'.$energyObject->getName().'</div>';
    if($child_elements_count > 0) {
        echo '<div class="energy_object_column_expand">'.
                '<div class="expand_icon_minus" id="expand_icon_'.$energyObject->getId().'" onclick="expandEnergyObjectUl('.$energyObject->getId().')"></div>'.
            '</div>';
    }
    echo '<div class="energy_object_column_value">';
    echo '<div class="meter_value_container">'.$energy_object_value.'</div>';
    if(ACCESS_LEVEL == 2 || ACCESS_LEVEL == 3) {
        echo '<div class="edit_buttons_container">
                           <div style="padding-top:6px;">
                                <div style="display:inline-block">';
        if(is_admin())
            echo '<div class="edit-button" onclick="edit_button_clicked = true; location.href=\''.$energy_object_edit_url_admin.';\'"></div>';
        else
            echo '<div class="edit-button" onclick="edit_button_clicked = true; location.href=\''.$energy_object_edit_url_user_room.';\'"></div>';

        echo        '</div>';

        echo   '<div style="display: inline-block; padding-left:5px;">
                                   <div class="delete-button" onclick="edit_button_clicked = true; delete_energy_object('.$energyObject->getId().',\''. $energyObject->getName().'\');"></div>
                               </div>
                            </div>
                </div>';
    }
    echo '</div>';
    echo '</div><ul id="energy_object_ul_'.$energyObject->getId().'">';

    if($is_last_row && $child_elements_count == 0) {
        echo '</ul></li>';
        $showed_objects--;
    }
    else if($child_elements_count == 0) {
        echo '</ul></li>';
        $showed_objects--;
    }
    //echo "<br>";
    //print_r($parent_meters_count);
    //echo "<br>";
    $GLOBALS['object_row'][$energyObject->getId()] = $GLOBALS['count_rows'];
    $GLOBALS['count_rows']++;
    $showed_objects++; //$showed_objects
}


// -------------------============== Show METER row ================----------------------
function printMeterRow($meter, $nestedLevel, $is_last_row, $last_value) {
    //$rand_num = number_format (30000/rand(20, 50), 3);
    global $showed_objects;
    $last_value_val = 0;
    if($last_value != null) $last_value_val = $last_value->getValue();

    $meter_edit_url_admin = site_url('/wp-admin/admin.php?page=add_meter&edit='.$meter->getId());
    $meter_edit_url_user_room = site_url('/user-room/meters-management/add-meter?edit='.$meter->getId());

    if(is_admin()) {
        $url_meter_detail = site_url('/wp-admin/admin.php?page=meter_details&meter='.$meter->getId());
    }
    else {
        $url_meter_detail = site_url('/user-room/meter-management/meter-details?meter='.$meter->getId());
    }

    if($nestedLevel < $showed_objects) {
        while($nestedLevel < $showed_objects) {
            echo '</ul></li>';
            $showed_objects -= 1;
        }
    }

    //echo "<li>".$meter->getName()."| showed_objects: " . $showed_objects. " | n_lvl: ".$nestedLevel."</li>";

    echo "<li ";
        if($is_last_row) echo 'class="last"';
    echo">";
        //echo $meter->getName();
        echo '<div class="meter_row" onclick="detailsMeterClicked(\''.$url_meter_detail.'\');">'.
                '<div class="meter_column_name">'.$meter->getName().'</div>';

                echo '<div class="meter_num_column">'.$meter->getNum().'</div>';

                echo '<div class="meter_column_value">';
                echo '<div class="meter_value_container">'.$last_value_val.'</div>';
                if(ACCESS_LEVEL == 2 || ACCESS_LEVEL == 3) {
                echo '<div class="edit_buttons_container">
                           <div style="padding-top:6px;">
                                <div style="display:inline-block">';
                    if(is_admin())
                        echo '<div class="edit-button" onclick="edit_button_clicked = true; location.href=\''.$meter_edit_url_admin.';\'"></div>';
                    else
                        echo '<div class="edit-button" onclick="edit_button_clicked = true; location.href=\''.$meter_edit_url_user_room.';\'"></div>';

                    echo        '</div>';

                        echo   '<div style="display: inline-block; padding-left:5px;">
                                   <div class="delete-button" onclick="edit_button_clicked = true; delete_meter('.$meter->getId().',\''. $meter->getName().'\');"></div>
                               </div>
                            </div>
                </div';
                }
                echo '</div>'.
            '</div>';
    echo "</li>";



    if($is_last_row) {
        echo '</ul></li>';
        $showed_objects--;
    }


    //echo "------------<br>".$meter->getName()." | object_id: ".$energy_object->getId()."<br>";
    //print_r($is_elements_down);
    //echo "<br>";

    $GLOBALS['count_rows']++;
}

?>

<?php //echo "access_level = ".ACCESS_LEVEL; ?>

<?php foreach ($energyObjectsList as $energyObject): ?>
    <?php
    $showed_objects = 0;
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
        </table>

        <div class="objects_list_cointainer">
            <ul class="tree_objects_list">
            <?php
            if(ACCESS_LEVEL==3) {
                //echo "show nested obj 1";
                showNestedObjects(0, $energyObject->getId(), $dataController, -1, $energyObject->getId());
            }
            else {
                //echo "show nested obj 2<br>".print_r($customer);
                showNestedObjects(0, $energyObject->getId(), $dataController, $customer->getId(), $energyObject->getId());
            }
            ?>
            </ul>
        </div>

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
