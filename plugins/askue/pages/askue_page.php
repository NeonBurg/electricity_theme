<?php
    require_once(ASKUE_PLUGIN_DIR . "models/DataController.php");
    //require_once(ASKUE_PLUGIN_DIR . "models/Meter.php");
    //require_once(ASKUE_PLUGIN_DIR . "models/EnergyObject.php");
?>

<?php

global $wpdb;
$dataController = new DataController($wpdb);
$energyObjectsList = $dataController->selectEnergyObjects();
?>

<script type="text/javascript">

    function select_add_click() {
        //console.log('select_add_click');
        var e = document.getElementById("select_add");
        var select_val = e.options[e.selectedIndex].value;

        //console.log('select_val = ' + select_val);

        switch (select_val) {
            case "meter_type":
                location.href = '/wp-admin/admin.php?page=add_meter_type';
                break;
            case "meter":
                location.href = '/wp-admin/admin.php?page=add_meter';
                break;
            case "energy_object":
                location.href = '/wp-admin/admin.php?page=add_energy_object';
                break;
            default:
                break;
        }
    }

    function delete_meter(meter_id, meter_name) {
        if (confirm("Удалить счетчик: '" + meter_name + "' ?")) {
            console.log('delete meter: id = ' + meter_id);
            $form_data = {'meter_id': meter_id};
            $form_action = '<?=plugins_url("/remove_meter.php", __FILE__);?>';
            makeDeleteAjax($form_action, $form_data);
        }
        else {
            console.log('cancel delete meter');
        }
    }

    function delete_energy_object(energy_object_id, energy_object_name) {
        if (confirm("Удалить энергетический объект: '" + energy_object_name + "' ?")) {
            console.log('delete meter: id = ' + energy_object_id);
            $form_data = {'energy_object_id': energy_object_id};
            $form_action = '<?=plugins_url("/remove_energy_object.php", __FILE__);?>';
            makeDeleteAjax($form_action, $form_data);
        }
        else {
            console.log('cancel delete meter');
        }
    }

    function makeDeleteAjax(action, form_data) {
        jQuery.ajax({
            type: 'POST',
            url: action,
            data: form_data
        }).done(function (response) {
            //console.log(response);
            location.reload(); // Обновляем страницу после удаления
        }).fail(function (data) {
            console.log('delete error!');
            if (data.responseText !== '') {
                console.log('response: ' + data.responseText);
            }
        });
    }
</script>

<style>
    .energy-object-top-table {
        width:100%;
        background-color: #b5b5b5;
    }
    .energy-object-top-table td {
        height: 38px;
    }

</style>

<h1>Автоматизированная система контроля и учета электроэнергии (АСКУЭ)</h1>

<div class="askue-admin-content">


    <!-- buttons -->
    <div class="askue-horizontal-buttons-containter">
        <div class="askue-horizontal-buttons-inner-left">

                <select id="select_add" name="select_add" class="askue-select-add">
                    <option value="meter_type">Тип счетчика</option>
                    <option value="meter">Счетчик</option>
                    <option value="energy_object">Объект</option>
                </select>

            <div class="askue-button-wrap"><div class="askue-button" onclick="select_add_click()">Добавить</div></div>
        </div>

        <div class="askue-horizontal-buttons-inner-right">
            <div class="askue-button-wrap"><div class="askue-button" onclick="location.href='/wp-admin/admin.php?page=add_energy_object';">Добавить объект</div></div>
            <div class="askue-button-wrap"><div class="askue-button" onclick="location.href='/wp-admin/admin.php?page=add_meter';">Добавить счетчик</div></div>
        </div>
    </div>


    <?php foreach ($energyObjectsList as $energyObject): ?>

        <?php
            $customer = $dataController->selectCustomer($energyObject->getCustomerId());
        ?>
    <!--<div class="energy-object-top">
            <!--<div class="energy-object-title"><//?=$energyObject->getName();?></div>
            <div style="height: 100%; display: inline-block; float:right; border:1px black solid;">
                <div style="display: inline-block; vertical-align: middle;"><div style="width: 15px; height:15px; background-color: #00A8EF;"></div></div>
            </div>
        </div>-->
        <table class="energy-object-top-table">
            <tr>
                <td width="90%">
                    <div class="energy-object-title"><?=$energyObject->getName();?></div>
                    <div style="display:inline-block;">
                        <!--[<a href="">Николай</a>]-->
                        [<a href=""><?=$customer->getName();?></a>]
                    </div>
                </td>
                <td align="center" width="6%">
                    <!--<div style="width: 15px; height:15px; background-color: #00A8EF;"></div>-->
                    <div style="padding-top:6px;">
                        <div style="display: inline-block;">
                            <div class="edit-object-button" onclick="location.href='/wp-admin/admin.php?page=add_energy_object&edit=<?=$energyObject->getId();?>'"></div>
                        </div>
                        <div style="display: inline-block; padding-left:5px;">
                            <div class="delete-object-button" onclick="delete_energy_object(<?=$energyObject->getId();?>, '<?=$energyObject->getName();?>')"></div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>


        <!---------------- Таблица со списком счетчиков ----------------->
        <div class="energy-object-content">
            <table class="energy-object-table" cellpadding="0" cellspacing="0">
                <tr>
                    <th width="33%" style="text-align:left; padding-left:50px;">Название</th>
                    <th>Счетчик №</th>
                    <th width="33%" style="text-align:right;">Потребление (Кв/ч)</th>
                    <th width="6%"></th>
                </tr>

                <?php foreach ($energyObject->getMetersList() as $meter): ?>

                    <tr>
                        <td style="text-align:left; padding-left:50px;"><?=$meter->getName();?></td>
                        <td><?=$meter->getNum();?></td>
                        <td style="text-align:right;">
                            <?php $rand_num = 30000/rand(20, 50); echo number_format ($rand_num, 3); ?>
                        </td>
                        <td>
                            <div style="padding-top:6px;">
                                <div style="display: inline-block;">
                                    <div class="edit-button" onclick="location.href='/wp-admin/admin.php?page=add_meter&edit=<?=$meter->getId();?>'"></div>
                                </div>
                                <div style="display: inline-block; padding-left:5px;">
                                    <div class="delete-button" onclick="delete_meter(<?=$meter->getId();?>, '<?=$meter->getName();?>')"></div>
                                </div>
                            </div>
                        </td>
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

</div>