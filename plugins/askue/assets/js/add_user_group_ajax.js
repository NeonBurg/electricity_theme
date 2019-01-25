jQuery(document).ready(function($) {

    // Get the form
    var form = $('#ajax-add-group');

    // Get the inputs
    var meterNameInput = $('#group_name_input');

    var name_status_icon = $('#name_status_icon');

    var name_error_message = $('#name_error_message');

    var counterKeys = 0;

    var success_fields = [false];
    if(document.getElementById("edit_group_name")) for(var i=0; i<success_fields.length; i++) success_fields[i] = true;

    $(meterNameInput).on('input', function() {
        if(counterKeys === 0) {
            setIconClass(name_status_icon, 'icon_typing');
        }
        counterKeys++;
    });

    $(meterNameInput).donetyping(donetypingName);

    // ------ Проверка имени группы -------
    function donetypingName() {
        //$form_data = {'meter_name_input' : $('#meter_name_input').val(), 'edit_meter_name' : $('#edit_meter_name').val(), 'edit_meter_energy_object_name' : $('#edit_meter_energy_object_name').val()};
        $form_data = {'group_name_input' : $('#group_name_input').val(), 'edit_group_name' : $('#edit_group_name').val()};

        //var edit_meter_name = document.getElementById("edit_meter_name");

        //makeAjaxPost(0, "../../wp-content/plugins/askue/pages/accounts_manage/add_group/check_name.php", $form_data, name_error_message, name_status_icon);
        makeAjaxPost(0, myScript.askue_plugin_url + "/askue/pages/accounts_manage/add_group/check_name.php", $form_data, name_error_message, name_status_icon);
        counterKeys = 0;
    }

    function checkAllFields() {
        if (!success_fields[0]) donetypingName();
    }

    $(form).submit(function(event) {
        // Stop the browser from submitting the form.
        event.preventDefault();

        var send_registration = true;

        jQuery.each(success_fields, function(index, item) {
            if(!item) send_registration = false;
        });

        if(send_registration) {
            submitForm();
        }
        else {
            checkAllFields();
        }
    });

    function submitForm() {
        var formData = $(form).serialize();

        $.ajax({
            type: 'POST',
            //url: "../../wp-content/plugins/askue/pages/accounts_manage/add_group/final_check.php",
            url: myScript.askue_plugin_url + "/askue/pages/accounts_manage/add_group/final_check.php",
            data: formData
        }).done(function(response) {
            //console.log(response);
            if(myScript.is_admin) {
                window.location.replace(myScript.site_url + "/wp-admin/admin.php?page=accounts_manage");
            }
            else {
                window.location.replace(myScript.site_url + "/user-room/accounts-management/");
            }
        }).fail(function(data) {
            console.log('add meter error!');
            if (data.responseText !== '') {
                console.log('response: ' + data.responseText);
            }
        });
    }

    // ------------------------- Остальные функции ---------------------------
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