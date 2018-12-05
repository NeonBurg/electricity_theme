<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 15.05.2018
 * Time: 12:01
 */
?>

<script type="text/javascript">

    jQuery(document).ready(function($) {

        // Get the form
        var form = $('#ajax-add-meter');

        // Get the inputs
        var typeNameInput = $('#type_name_input');

        var name_status_icon = $('#name_status_icon');

        var name_error_message = $('#name_error_message');

        var counterKeys = 0;
        var success_fields = [false];

        $(typeNameInput).on('input', function() {
            if(counterKeys === 0) {
                setIconClass(name_status_icon, 'icon_typing');
            }
            counterKeys++;
        });

        $(typeNameInput).donetyping(donetypingName);

        // ------ Проверка имени счетчика -------
        function donetypingName() {
            $form_data = {'type_name_input' : $('#type_name_input').val()};

            makeAjaxPost(0, "../../wp-content/plugins/askue/pages/add_meter_type/check_type_name.php", $form_data, name_error_message, name_status_icon);
            counterKeys = 0;
        }


        $(form).submit(function(event) {
            // Stop the browser from submitting the form.
            event.preventDefault();

            var send_registration = true;

            jQuery.each(success_fields, function(index, item) {
                if(!item) send_registration = false;
            });

            if(send_registration) {
                var formData = $(form).serialize();

                $.ajax({
                    type: 'POST',
                    url: "../../wp-content/plugins/askue/pages/add_meter_type/final_check.php",
                    data: formData
                }).done(function(response) {
                    window.location.replace("/wp-admin/admin.php?page=askue_menu");

                }).fail(function(data) {
                    console.log('add meter_type error!');
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

                if(field_index === 2) {
                    if($(meterNameInput).val()) donetypingName();
                    if($(meterNumInput).val()) donetypingMeterNum();
                    if($(networkAddressInput).val()) donetypingNetworkAddress();
                }

            }).fail(function(data) {
                setIconClass(status_icon_box, 'icon_error');
                // Set error message
                if (data.responseText !== '') {
                    $(error_message_box).text(data.responseText);
                }
                success_fields[field_index] = false;

                if(field_index === 2) {
                    if($(meterNameInput).val()) donetypingName();
                    if($(meterNumInput).val()) donetypingMeterNum();
                    if($(networkAddressInput).val()) donetypingNetworkAddress();
                }
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

<h1>АСКУЭ » Добавление нового типа счетчиков</h1>

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
                            <input type="text" name="type_name_input" id="type_name_input" class="askue_input" autocomplete="off" placeholder="Название для типа счетчика">
                        </div>

                    </td>
                    <!-- Name icon: -->
                    <td width="35" align="center"><div class="icon-status-container">
                            <div class="icon_typing" id="name_status_icon"></div>
                        </div></td>
                </tr>

                <!---------------- Meter type input ---------------->
                <tr><th>
                        Тип счетчика:
                    </th></tr>

                <tr><td>
                        <!-- Error message box -->
                        <div class="inputs_error_container_askue">
                            <div class="inputs_error_text" id="name_error_message"></div>
                        </div>

                        <!-- Name input: -->
                        <div style="display: inline-block; width:100%; padding-right:50px;">
                            <!--<input type="text" name="type_name_input" id="type_name_input" class="askue_input" autocomplete="off" placeholder="Название счетчика">-->
                            <select id="meter_type" name="meter_type">
                                <option value="singlephase">Однофазный</option>
                                <option value="threephase">Трёхфазный</option>
                            </select>
                        </div>

                    </td>
                    <!-- Name icon: -->
                    <td width="35" align="center"><div class="icon-status-container">
                            <div class="icon_typing" id="name_status_icon"></div>
                        </div></td>
                </tr>
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
