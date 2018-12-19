jQuery(document).ready(function($) {

    // Get the form
    var form = $('#ajax-add-meter');

    // Get the inputs
    var meterNameInput = $('#meter_name_input');
    var meterNumInput = $('#meter_num_input');
    var energyObjectInput = $('#energy_object_input');
    var meterTypeInput = $('#meter_type_input');
    var networkAddressInput = $('#network_address_input');

    var name_status_icon = $('#name_status_icon');
    var meter_num_status_icon = $('#meter_num_status_icon');
    var energy_object_status_icon = $('#energy_object_status_icon');
    var meter_type_status_icon = $('#meter_type_status_icon');
    var network_address_status_icon = $('#network_address_status_icon');

    var name_error_message = $('#name_error_message');
    var meter_num_error_message = $('#meter_num_error_message');
    var energy_object_error_message = $('#energy_object_error_message');
    var meter_type_error_message = $('#meter_type_error_message');
    var network_address_error_message = $('#network_address_error_message');

    var counterKeys = 0;
    var meterTypeInputOldVal = '';
    var energyObjectInputOldVal = '';

    var success_fields = [false, false, false, false, false];
    if(document.getElementById("edit_meter_name")) for(var i=0; i<success_fields.length; i++) success_fields[i] = true;

    $(meterNameInput).on('input', function() {
        if(counterKeys === 0) {
            setIconClass(name_status_icon, 'icon_typing');
        }
        counterKeys++;
    });

    $(meterNumInput).on('input', function() {
        if(counterKeys === 0) {
            setIconClass(meter_num_status_icon, 'icon_typing');
        }
        counterKeys++;
    });

    $(networkAddressInput).on('input', function() {
        if(counterKeys === 0) {
            setIconClass(network_address_status_icon, 'icon_typing');
        }
        counterKeys++;
    });

    $(energyObjectInput).on('input', function() {
        var val = this.value;

        if(counterKeys === 0) {
            setIconClass(energy_object_status_icon, 'icon_typing');
        }
        counterKeys++;

        if($('#energy_objects_list').find('option').filter(function(){
                return this.value.toUpperCase() === val.toUpperCase();
            }).length) {
            donetypingEnergyObject();
        }
    });

    $(meterTypeInput).on('input', function() {
        var val = this.value;

        if(counterKeys === 0) {
            setIconClass(meter_type_status_icon, 'icon_typing');
        }
        counterKeys++;

        if($('#meter_types_list').find('option').filter(function(){
                return this.value.toUpperCase() === val.toUpperCase();
            }).length) {
            donetypingMeterType();
        }
    });

    $(meterTypeInput).on('click', function() {
        meterTypeInputOldVal = $(meterTypeInput).val();
        //console.log('meterTypeInput.click(): value = ' + $(meterTypeInput).val());
        $(meterTypeInput).val("");
    });

    $(meterTypeInput).focusout(function() {
        //console.log('meterTypeInput.focusout() called;');
        if($(meterTypeInput).val().length === 0 && meterTypeInputOldVal.length !== 0) {
            $(meterTypeInput).val(meterTypeInputOldVal);
        }
    });

    $(energyObjectInput).on('click', function() {
        energyObjectInputOldVal = $(energyObjectInput).val();
        //console.log('energyObjectInput.click(): value = ' + $(energyObjectInput).val());
        $(energyObjectInput).val("");
    });

    $(energyObjectInput).focusout(function() {
        //console.log('energyObjectInput.focusout() called;');
        if($(energyObjectInput).val().length === 0 && energyObjectInputOldVal.length !== 0) {
            $(energyObjectInput).val(energyObjectInputOldVal);
        }
    });

    $(meterNameInput).donetyping(donetypingName);
    $(meterNumInput).donetyping(donetypingMeterNum);
    $(energyObjectInput).donetyping(donetypingEnergyObject);
    $(meterTypeInput).donetyping(donetypingMeterType);
    $(networkAddressInput).donetyping(donetypingNetworkAddress);


    // ------ Проверка имени счетчика -------
    function donetypingName() {
        $form_data = {'meter_name_input' : $('#meter_name_input').val(), 'edit_meter_name' : $('#edit_meter_name').val(), 'edit_meter_energy_object_name' : $('#edit_meter_energy_object_name').val()};

        if(success_fields[2]) $form_data['energy_object_input'] = $('#energy_object_input').val();

        var edit_meter_name = document.getElementById("edit_meter_name");

        /*if(edit_meter_name && edit_meter_name.value === $form_data['meter_name_input']) {
            makeSuccess(0, name_error_message, name_status_icon);
        }
        else {
            makeAjaxPost(0, "../../wp-content/plugins/askue/pages/add_meter/check_name.php", $form_data, name_error_message, name_status_icon);
        }*/

        //makeAjaxPost(0, "../../wp-content/plugins/askue/pages/add_meter/check_name.php", $form_data, name_error_message, name_status_icon);
        makeAjaxPost(0, myScript.askue_plugin_url + "/askue/pages/add_meter/check_name.php", $form_data, name_error_message, name_status_icon);
        counterKeys = 0;
    }

    // ------ Проверка номера счетчика -------
    function donetypingMeterNum() {
        $form_data = {'meter_num_input' : $('#meter_num_input').val(), 'edit_meter_num' : $('#edit_meter_num').val(), 'edit_meter_energy_object_name' : $('#edit_meter_energy_object_name').val()};

        if(success_fields[2]) $form_data['energy_object_input'] = $('#energy_object_input').val();

        var edit_meter_num = document.getElementById("edit_meter_num");

        /*if(edit_meter_num && edit_meter_num.value === $form_data['meter_num_input']) {
            makeSuccess(1, meter_num_error_message, meter_num_status_icon);
        }
        else {
            makeAjaxPost(1, "../../wp-content/plugins/askue/pages/add_meter/check_meter_num.php", $form_data, meter_num_error_message, meter_num_status_icon);
        }*/

        //makeAjaxPost(1, "../../wp-content/plugins/askue/pages/add_meter/check_meter_num.php", $form_data, meter_num_error_message, meter_num_status_icon);
        makeAjaxPost(1, myScript.askue_plugin_url + "/askue/pages/add_meter/check_meter_num.php", $form_data, meter_num_error_message, meter_num_status_icon);
        counterKeys = 0;
    }

    // ------ Проверка объекта -------
    function donetypingEnergyObject() {

        $form_data = {'energy_object_input' : $('#energy_object_input').val()};
        //makeAjaxPost(2, "../../wp-content/plugins/askue/pages/add_meter/check_object.php", $form_data, energy_object_error_message, energy_object_status_icon);
        makeAjaxPost(2, myScript.askue_plugin_url + "/askue/pages/add_meter/check_object.php", $form_data, energy_object_error_message, energy_object_status_icon);
        counterKeys = 0;
    }

    // ------ Проверка объекта -------
    function donetypingMeterType() {

        $form_data = {'meter_type_input' : $('#meter_type_input').val()};
        //makeAjaxPost(3, "../../wp-content/plugins/askue/pages/add_meter/check_meter_type.php", $form_data, meter_type_error_message, meter_type_status_icon);
        makeAjaxPost(3, myScript.askue_plugin_url + "/askue/pages/add_meter/check_meter_type.php", $form_data, meter_type_error_message, meter_type_status_icon);
        counterKeys = 0;
    }

    // ------ Проверка номер счетчика -------
    function donetypingNetworkAddress() {
        $form_data = {'network_address_input' : $('#network_address_input').val(), 'edit_meter_network_address' : $('#edit_meter_network_address').val(), 'edit_meter_energy_object_name' : $('#edit_meter_energy_object_name').val()};

        if(success_fields[2]) $form_data['energy_object_input'] = $('#energy_object_input').val();

        var edit_meter_network_address = document.getElementById("edit_meter_network_address");

        /*if(edit_meter_network_address && edit_meter_network_address.value === $form_data['network_address_input']) {
            makeSuccess(4, network_address_error_message, network_address_status_icon);
        }
        else {
            makeAjaxPost(4, "../../wp-content/plugins/askue/pages/add_meter/check_network_address.php", $form_data, network_address_error_message, network_address_status_icon);
        }*/

        //makeAjaxPost(4, "../../wp-content/plugins/askue/pages/add_meter/check_network_address.php", $form_data, network_address_error_message, network_address_status_icon);
        makeAjaxPost(4, myScript.askue_plugin_url + "/askue/pages/add_meter/check_network_address.php", $form_data, network_address_error_message, network_address_status_icon);
        counterKeys = 0;
    }

    function makeSuccess(field_index, error_message_box, status_icon_box) {
        setIconClass(status_icon_box, 'icon_complete');

        $(error_message_box).text('');
        success_fields[field_index] = true;
    }

    function checkAllFields() {
        if(!success_fields[0]) donetypingName();
        if(!success_fields[1]) donetypingMeterNum();
        if(!success_fields[2]) donetypingEnergyObject();
        if(!success_fields[3]) donetypingMeterType();
        if(!success_fields[3]) donetypingNetworkAddress();
    }

    // Set up an event listener for the contact form.
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
            //url: "../../wp-content/plugins/askue/pages/add_meter/final_check.php",
            url: myScript.askue_plugin_url + "/askue/pages/add_meter/final_check.php",
            data: formData
        }).done(function(response) {
            //console.log(response);
            if(myScript.is_admin) {
                window.location.replace("/wp-admin/admin.php?page=askue_menu");
            }
            else {
                window.location.replace("/user-room/meters-management/");
            }
        }).fail(function(data) {
            console.log('add meter error!');
            if (data.responseText !== '') {
                console.log('response: ' + data.responseText);
            }
        });
    }



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