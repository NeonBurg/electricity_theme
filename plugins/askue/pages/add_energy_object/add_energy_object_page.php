<?php if(isset($access_level) && ($access_level == 2 || $access_level == 3) || is_admin()):?>
<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 07.05.2018
 * Time: 11:02
 */

require_once(ASKUE_PLUGIN_DIR . "models/DataController.php");
require_once(ASKUE_PLUGIN_DIR . "models/Customer.php");

global $wpdb;
$dataController = new DataController($wpdb);
$energyObjectsList = $dataController->selectEnergyObjects();
$customersList = $dataController->selectCustomersList();
$metersList = $dataController->selectMetersList();

$edit_energy_object = null;
$edit_energy_object_owner = null;
$edit_energy_object_parent = null;
$edit_meter = null;

if(isset($_GET["edit"])) {
    require_once(ASKUE_PLUGIN_DIR . "models/EnergyObject.php");
    $energy_object_id = $_GET["edit"];
    $edit_energy_object = $dataController->selectEnergyObject($energy_object_id);
    $edit_energy_object_owner = $dataController->selectCustomer($edit_energy_object->getCustomerId());
    $edit_energy_object_parent = $dataController->selectEnergyObject($edit_energy_object->getEnergyObjectId());
    $edit_meter = $dataController->selectMeter($edit_energy_object->getMeterId());
}

?>

<?php wp_enqueue_script('donetype_script'); ?>
<?php wp_enqueue_script('add_energy_object_ajax'); ?>

<h1>
    <?php if($edit_energy_object) echo "АСКУЭ » Редактирование объекта: '".$edit_energy_object->getName()."'";
    else echo "АСКУЭ » Добавление нового энергетического объекта"?>
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
                            <input type="text" name="object_name_input" id="object_name_input" class="askue_input" autocomplete="off" placeholder="Название объекта" <?php if($edit_energy_object) echo 'value="'.$edit_energy_object->getName().'"';?>>
                            <?php
                                if($edit_energy_object) {
                                    echo '<input type="hidden" id="edit_energy_object_id" name="edit_energy_object_id" value="' . $edit_energy_object->getId() . '">';
                                    echo '<input type="hidden" id="edit_energy_object_name" name="edit_energy_object_name" value="' . $edit_energy_object->getName() . '">';
                                }
                                ?>
                        </div>

                    </td>
                    <!-- Name icon: -->
                    <td width="35" align="center"><div class="icon-status-container">
                            <div class="icon_typing" id="name_status_icon"></div>
                        </div></td>
                </tr>

                <!---------------- Address input ---------------->
                <tr><th>
                        Адрес:
                    </th></tr>

                <tr><td>
                        <!-- Error message box -->
                        <div class="inputs_error_container_askue">
                            <div class="inputs_error_text" id="address_error_message"></div>
                        </div>

                        <!-- Name input: -->
                        <div style="display: inline-block; width:100%; padding-right:50px;">
                            <input type="text" name="object_address_input" id="object_address_input" class="askue_input" autocomplete="off" placeholder="Адрес объекта" <?php if($edit_energy_object) echo 'value="'.$edit_energy_object->getAddress().'"';?>>
                            <?php if($edit_energy_object) echo '<input type="hidden" id="edit_energy_object_address" name="edit_energy_object_address" value="'.$edit_energy_object->getAddress().'">';?>
                        </div>

                    </td>
                    <!-- Name icon: -->
                    <td width="35" align="center"><div class="icon-status-container">
                            <div class="icon_typing" id="address_status_icon"></div>
                        </div></td>
                </tr>

                <!---------------- Owner input ---------------->
                <tr><th>
                        Владелец:
                    </th></tr>

                <tr><td>
                        <!-- Meter type input: -->
                        <input list="owners_list" name="object_owner_input" id="object_owner_input" class="askue-list-input" placeholder="Выберите владельца счетчика" autocomplete="off" <?php if($edit_energy_object_owner) echo 'value="'.$edit_energy_object_owner->getLogin().' - '.$edit_energy_object_owner->getName().'"';?>>
                        <datalist id="owners_list">
                            <?php foreach ($customersList as $customer): ?>
                                <option value="<?=$customer->getLogin() . ' - ' . $customer->getName();?>"></option>
                            <?php endforeach; ?>
                        </datalist>

                        <!-- Meter type icon: -->
                        <div class="icon-status-container">
                            <div class="icon_typing" id="owner_status_icon"></div>
                        </div>

                        <!-- Error message box -->
                        <div class="inputs_error_container_horizontal">
                            <div class="inputs_error_text" id="owner_error_message"></div>
                        </div>
                    </td></tr>

                <!---------------- Energy Object input ---------------->
                <tr><th>
                        Объект:
                    </th></tr>

                <tr><td>
                        <!-- Energy Object input: -->
                        <input list="energy_objects_list" name="energy_object_input" id="energy_object_input" class="askue-list-input" placeholder="Выберите объект" autocomplete="off" <?php if($edit_energy_object_parent) echo 'value="'.$edit_energy_object_parent->getName().'"';?>>
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

                <!---------------- Energy Object Meter input ---------------->
                <tr><th>
                        Счетчик энергетического объекта:
                    </th></tr>

                <tr><td>
                        <!-- Energy Object Meter input: -->
                        <input list="meters_list" name="meter_name_input" id="meter_name_input" class="askue-list-input" placeholder="Выберите счетчик" autocomplete="off" <?php if($edit_meter) echo 'value="'.$edit_meter->getName().'"';?>>

                        <datalist id="meters_list" >
                            <?php foreach ($metersList as $meter): ?>
                                <option value="<?=$meter->getName();?>"></option>
                            <?php endforeach; ?>
                        </datalist>

                        <!-- Energy Object icon: -->
                        <div class="icon-status-container">
                            <div class="icon_typing" id="meter_status_icon"></div>
                        </div>

                        <!-- Error message box -->
                        <div class="inputs_error_container_horizontal">
                            <div class="inputs_error_text" id="meter_error_message"></div>
                        </div>
                </td></tr>

            </table>
        </div>

        <!---------------- Submit button ----------------->
        <table class="askue-submit-button-table" cellpadding="0" cellspacing="0">
            <tr>
                <td align="left" style="vertical-align: top;">
                    <div id="add_energy_object_error" style="color:red;"></div>
                </td>
                <td align="right">
                    <input type="submit" class="askue-submit-button" <?php if($edit_energy_object) echo 'value="Сохранить"'; else echo 'value="Добавить"'; ?>>
                </td>
            </tr>
        </table>

        <input type="hidden" id="nullableEnergyObject" name="nullableEnergyObject" value="true">
    </form>
</div>
<?php else: ?>
    <div class="edit-title">Нет доступа к данной странице</div>
<?php endif; ?>