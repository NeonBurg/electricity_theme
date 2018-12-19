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

        //makeAjaxPost(0, "../../wp-content/plugins/askue/pages/add_meter_type/check_name.php", $form_data, name_error_message, name_status_icon);
        makeAjaxPost(0, myScript.askue_plugin_url + "/askue/pages/add_meter_type/check_name.php", $form_data, name_error_message, name_status_icon);
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
                url: myScript.askue_plugin_url + "/askue/pages/add_meter_type/final_check.php",
                data: formData
            }).done(function(response) {
                if(myScript.is_admin) {
                    window.location.replace("/wp-admin/admin.php?page=askue_menu");
                }
                else {
                    window.location.replace("/user-room/meters-management/");
                }

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