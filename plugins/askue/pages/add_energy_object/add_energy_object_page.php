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



<script type="text/javascript">


    jQuery(document).ready(function($) {
        // Get the form
        var form = $('#ajax-add-meter');

        // Get the inputs
        var objectNameInput = $('#object_name_input');
        var objectAddressInput = $('#object_address_input');
        var objectOwnerInput = $('#object_owner_input');

        var name_status_icon = $('#name_status_icon');
        var address_status_icon = $('#address_status_icon');
        var owner_status_icon = $('#owner_status_icon');

        var name_error_message = $('#name_error_message');
        var address_error_message = $('#address_error_message');
        var owner_error_message = $('#owner_error_message');

        var counterKeys = 0;

        var success_fields = [false, false, false];

        $(objectNameInput).on('input', function() {
            if(counterKeys === 0) {
                setIconClass(name_status_icon, 'icon_typing');
            }
            counterKeys++;
        });

        $(objectAddressInput).on('input', function() {
            if(counterKeys === 0) {
                setIconClass(address_status_icon, 'icon_typing');
            }
            counterKeys++;
        });

        $(objectOwnerInput).on('input', function() {
            var val = this.value;

            if(counterKeys === 0) {
                setIconClass(owner_status_icon, 'icon_typing');
            }
            counterKeys++;

            if($('#owners_list').find('option').filter(function(){
                    return this.value.toUpperCase() === val.toUpperCase();
                }).length) {
                donetypingOwner();
            }
        });

        $(objectNameInput).donetyping(donetypingName);
        $(objectAddressInput).donetyping(donetypingAddress);
        $(objectOwnerInput).donetyping(donetypingOwner);

        // ------ Проверка имени объекта -------
        function donetypingName() {
            $form_data = {'object_name_input' : $('#object_name_input').val(), 'edit_energy_object_name' : $('#edit_energy_object_name').val()};

            makeAjaxPost(0, "../../wp-content/plugins/askue/pages/add_energy_object/check_name.php", $form_data, name_error_message, name_status_icon);
            counterKeys = 0;
        }

        // ------ Проверка адреса объекта -------
        function donetypingAddress() {
            $form_data = {'object_address_input' : $('#object_address_input').val(), 'edit_energy_object_address' : $('#edit_energy_object_address').val()};

            makeAjaxPost(1, "../../wp-content/plugins/askue/pages/add_energy_object/check_address.php", $form_data, address_error_message, address_status_icon);
            counterKeys = 0;
        }

        // ------ Проверка адреса объекта -------
        function donetypingOwner() {
            $form_data = {'object_owner_input' : $('#object_owner_input').val()};

            makeAjaxPost(2, "../../wp-content/plugins/askue/pages/add_energy_object/check_owner.php", $form_data, owner_error_message, owner_status_icon);
            counterKeys = 0;
        }

        function checkAllFields() {
            if(!success_fields[0]) donetypingName();
            if(!success_fields[1]) donetypingAddress();
            if(!success_fields[2]) donetypingOwner();
        }

        // Set up an event listener for the contact form.
        $(form).submit(function(event) {
            // Stop the browser from submitting the form.
            event.preventDefault();

            var is_add_object = true;

            jQuery.each(success_fields, function(index, item) {
                if(!item) is_add_object = false;
            });

            if(is_add_object) {
                var formData = $(form).serialize();

                $.ajax({
                    type: 'POST',
                    url: "../../wp-content/plugins/askue/pages/add_energy_object/final_check.php",
                    data: formData
                }).done(function(response) {
                    console.log('registration success');
                    window.location.replace("/wp-admin/admin.php?page=askue_menu");

                }).fail(function(data) {
                    console.log('add meter error!');
                    if (data.responseText !== '') {
                        console.log('response: ' + data.responseText);
                    }
                });
            }
            else {
                checkAllFields();
            }
        });


        function makeAjaxPost(field_index, action, form_data, error_message_box, status_icon_box) {
            $.ajax({
                type: 'POST',
                url: action,
                data: form_data
            }).done(function(response) {
                setIconClass(status_icon_box, 'icon_complete');

                $(error_message_box).text('');
                success_fields[field_index] = true;

            }).fail(function(data) {
                setIconClass(status_icon_box, 'icon_error');
                // Set error message
                if (data.responseText !== '') {
                    $(error_message_box).text(data.responseText);
                }
                success_fields[field_index] = false;
            });
        }

        function setIconClass(icon, icon_class) {
            //console.log('setIcon: ' + icon_class + " | ");
            icon.removeClass(icon.attr('class'));
            icon.addClass(icon_class);
            icon.css('visibility', 'visible');
        }

    });


    (function($){
        $.fn.extend({
            donetyping: function(callback,timeout){
                timeout = timeout || 1e3; // 1 second default timeout
                var timeoutReference,
                    doneTyping = function(el){
                        if (!timeoutReference) return;
                        timeoutReference = null;
                        callback.call(el);
                    };
                return this.each(function(i,el){
                    var $el = $(el);
                    // Chrome Fix (Use keyup over keypress to detect backspace)
                    // thank you @palerdot
                    $el.is(':input') && $el.on('keyup keypress paste',function(e){
                        // This catches the backspace button in chrome, but also prevents
                        // the event from triggering too preemptively. Without this line,
                        // using tab/shift+tab will make the focused element fire the callback.
                        if (e.type=='keyup' && e.keyCode!=8) return;

                        // Check if timeout has been set. If it has, "reset" the clock and
                        // start over again.
                        if (timeoutReference) clearTimeout(timeoutReference);
                        timeoutReference = setTimeout(function(){
                            // if we made it here, our timeout has elapsed. Fire the
                            // callback
                            doneTyping(el);
                        }, timeout);
                    }).on('blur',function(){
                        // If we can, fire the event since we're leaving the field
                        doneTyping(el);
                    });
                });
            }
        });
    })(jQuery);

</script>




<h1>АСКУЭ » Добавление нового энергетического объекта</h1>

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
                            <?php if($edit_energy_object) echo '<input type="hidden" id="edit_energy_object_name" name="edit_energy_object_name" value="'.$edit_energy_object->getName().'">';?>
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
                    <input type="submit" class="askue-submit-button" value="Добавить">
                </td></tr>
        </table>

    </form>
</div>
