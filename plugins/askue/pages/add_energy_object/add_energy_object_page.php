<?php if($access_level == 2 || $access_level == 3 || is_admin()):?>
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
$customersList = $dataController->selectCustomersList();

$edit_energy_object = null;
$edit_energy_object_owner = null;

if(isset($_GET["edit"])) {
    require_once(ASKUE_PLUGIN_DIR . "models/EnergyObject.php");
    $energy_object_id = $_GET["edit"];
    $edit_energy_object = $dataController->selectEnergyObject($energy_object_id);
    $edit_energy_object_owner = $dataController->selectCustomer($edit_energy_object->getCustomerId());
}

?>

<?php wp_enqueue_script('donetype_script'); ?>
<?php wp_enqueue_script('add_energy_object_ajax'); ?>

<div class="edit-title">
    <?php if($edit_energy_object) echo "АСКУЭ » Объект: '".$edit_energy_object->getName()."'";
    else echo "АСКУЭ » Добавление нового энергетического объекта"?>
</div>

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

            </table>
        </div>

        <!---------------- Submit button ----------------->
        <table class="askue-submit-button-table" cellpadding="0" cellspacing="0">
            <tr><td align="right">
                    <input type="submit" class="askue-submit-button" <?php if($edit_energy_object) echo 'value="Сохранить"'; else echo 'value="Добавить"'; ?>>
                </td></tr>
        </table>

    </form>
</div>
<?php else: ?>
    <div class="edit-title">Нет доступа к данной странице</div>
<?php endif; ?>