<?php //echo "access_level = ".$access_level; ?>
<?php if(isset($access_level) && ($access_level == 2 || $access_level == 3) || is_admin()):?>

<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 04.05.2018
 * Time: 10:25
 */

require_once(ASKUE_PLUGIN_DIR . "models/DataController.php");
//require_once(ASKUE_PLUGIN_DIR . "models/MeterType.php");
//require_once(ASKUE_PLUGIN_DIR . "models/EnergyObject.php");

global $wpdb;
$dataController = new DataController($wpdb);
$energyObjectsList = $dataController->selectEnergyObjects();
$meterTypesList = $dataController->selectMeterTypes();

$edit_meter = null;
$edit_meter_object_name = null;
$edit_meter_type = null;


if(isset($_GET["edit"])) {
    //require_once(ASKUE_PLUGIN_DIR . "models/Meter.php");
    $meter_id = $_GET["edit"];
    $edit_meter = $dataController->selectMeter($meter_id);
    $edit_meter_object_name = $dataController->selectEnergyObject($edit_meter->getEnergyObjectId());
    $edit_meter_type = $dataController->selectMeterType($edit_meter->getMeterTypeId());
}
?>

<?php wp_enqueue_script('donetype_script'); ?>
<?php wp_enqueue_script('add_meter_ajax'); ?>

<h1>
    <?php if($edit_meter) echo "АСКУЭ » Редактирование счетчика: '".$edit_meter->getName()."'";
            else echo "АСКУЭ » Добавление нового счетчика"?>
</h1>

<div class="askue-admin-content">

    <!-- Форма добавления нового счетчика -->
    <form method="post" action="" id="ajax-add-meter">
        <div class="input-form-container">
            <table class="inputs-vertical-table" cellspacing="0" cellpadding="0">

                <!---------------- Name input ---------------->
                <tr><th>
                        Название:
                    </th></tr>

                <tr><td>
                        <!-- Error message box -->
                        <div class="inputs_error_container_askue">
                            <div class="inputs_error_text" id="name_error_message"></div>
                        </div>

                        <!-- Name input: -->
                        <div style="display: inline-block; width:100%; padding-right:50px;">
                            <input type="text" name="meter_name_input" id="meter_name_input" class="askue_input" autocomplete="off" placeholder="Название счетчика" <?php if($edit_meter) echo 'value="'.$edit_meter->getName().'"';?>>
                            <?php if($edit_meter) {
                                echo '<input type="hidden" id="edit_meter_id" name="edit_meter_id" value="' . $edit_meter->getId() . '">';
                                echo '<input type="hidden" id="edit_meter_name" name="edit_meter_name" value="' . $edit_meter->getName() . '">';
                            } ?>
                        </div>

                    </td>
                    <!-- Name icon: -->
                    <td width="35" align="center"><div class="icon-status-container">
                            <div class="icon_typing" id="name_status_icon"></div>
                    </div></td>
                </tr>

                <!---------------- Meter num input ---------------->
                <tr><th>
                        Счетчик №:
                    </th></tr>

                <tr><td>
                        <!-- Error message box -->
                        <div class="inputs_error_container_askue">
                            <div class="inputs_error_text" id="meter_num_error_message"></div>
                        </div>

                        <!-- Meter num input: -->
                        <input type="text" name="meter_num_input" id="meter_num_input" class="askue_input" autocomplete="off" placeholder="Номер счетчика" <?php if($edit_meter) echo 'value="'.$edit_meter->getNum().'"';?>>
                        <?php if($edit_meter) echo '<input type="hidden" id="edit_meter_num" name="edit_meter_num" value="'.$edit_meter->getNum().'">';?>
                </td>
                    <!-- Meter num icon: -->
                    <td><div class="icon-status-container">
                            <div class="icon_typing" id="meter_num_status_icon"></div>
                    </div></td>
                </tr>

                <!---------------- Energy Object input ---------------->
                <tr><th>
                        Объект:
                    </th></tr>

                <tr><td>
                        <!-- Energy Object input: -->
                        <input list="energy_objects_list" name="energy_object_input" id="energy_object_input" class="askue-list-input" placeholder="Выберите объект" autocomplete="off" <?php if($edit_meter_object_name) echo 'value="'.$edit_meter_object_name->getName().'"';?>>
                        <?php if($edit_meter_object_name) echo '<input type="hidden" id="edit_meter_energy_object_name" name="edit_meter_energy_object_name" value="'.$edit_meter_object_name->getName().'">';?>
                        <datalist id="energy_objects_list" >
                            <?php foreach ($energyObjectsList as $energyObject): ?>
                                <option value="<?=$energyObject->getName();?>"></option>
                            <?php endforeach; ?>
                        </datalist>

                        <!-- Energy Object icon: -->
                        <div class="icon-status-container">
                            <div class="icon_typing" id="energy_object_status_icon"></div>
                        </div>

                        <!-- Error message box -->
                        <div class="inputs_error_container_horizontal">
                            <div class="inputs_error_text" id="energy_object_error_message"></div>
                        </div>
                </td></tr>

                <!---------------- Meter type input ---------------->
                <tr><th>
                        Тип счетчика:
                    </th></tr>

                <tr><td>
                        <!-- Meter type input: -->
                        <input list="meter_types_list" name="meter_type_input" id="meter_type_input" class="askue-list-input" placeholder="Выберите тип счетчика" autocomplete="off" <?php if($edit_meter_type) echo 'value="'.$edit_meter_type->getName().'"';?>>
                        <datalist id="meter_types_list">
                            <?php foreach ($meterTypesList as $meterType): ?>
                                <option value="<?=$meterType->getName();?>"></option>
                            <?php endforeach; ?>
                        </datalist>

                        <!-- Meter type icon: -->
                        <div class="icon-status-container">
                            <div class="icon_typing" id="meter_type_status_icon"></div>
                        </div>

                        <!-- Error message box -->
                        <div class="inputs_error_container_horizontal">
                            <div class="inputs_error_text" id="meter_type_error_message"></div>
                        </div>
                </td></tr>

                <!---------------- Concentrator input ---------------->
                <!--<tr><th>
                        Концентратор:
                    </th></tr>

                <tr><td>-->
                        <!-- Error message box -->
                        <!--<div class="inputs_error_container_askue">
                            <div class="inputs_error_text" id="concentrator_error_message"></div>
                        </div>-->

                        <!-- Concentrator input: -->
                        <!--<input list="concentrators" name="concentrator_input" id="concentrator_input" class="askue-list-input" placeholder="Выберите концентратор">
                        <datalist id="concentrators">
                            <!--<option value="ТП 73У">-->
                        <!--</datalist>

                        <!-- Concentrator icon: -->
                        <!--<div class="icon-status-container">
                            <div class="icon_typing" id=concentrator_status_icon"></div>
                        </div>
                </td></tr>-->

                <!---------------- Network address input ---------------->
                <tr><th>
                        Сетевой адрес:
                    </th></tr>

                <tr><td>
                        <!-- Error message box -->
                        <div class="inputs_error_container_askue">
                            <div class="inputs_error_text" id="network_address_error_message"></div>
                        </div>

                        <!--  Network address input: -->
                        <input type="text" name="network_address_input" id="network_address_input" class="askue_input" autocomplete="off" placeholder="Сетевой адрес счетчика" <?php if($edit_meter) echo 'value="'.$edit_meter->getNetworkAddress().'"';?>>
                        <?php if($edit_meter) echo '<input type="hidden" id="edit_meter_network_address" name="edit_meter_network_address" value="'.$edit_meter->getNetworkAddress().'">';?>
                </td>
                    <!--  Network address icon: -->
                    <td><div class="icon-status-container">
                            <div class="icon_typing" id="network_address_status_icon"></div>
                    </div></td>
                </tr>
            </table>
        </div>

        <!---------------- Submit button ----------------->
        <table class="askue-submit-button-table" cellpadding="0" cellspacing="0">
            <tr><td align="right">
                    <input type="submit" class="askue-submit-button" <?php if($edit_meter) echo 'value="Сохранить"'; else echo 'value="Добавить"'; ?>>
                </td></tr>
        </table>

    </form>

</div>
    <?php else: ?>
    <div class="edit-title">Нет доступа к данной странице</div>
<?php endif; ?>