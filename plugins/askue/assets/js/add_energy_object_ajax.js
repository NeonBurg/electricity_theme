jQuery(document).ready(function($) {
    // Get the form
    var form = $('#ajax-add-meter');

    // Get the inputs
    var objectNameInput = $('#object_name_input');
    var objectAddressInput = $('#object_address_input');
    var objectOwnerInput = $('#object_owner_input');
    var energyObjectInput = $('#energy_object_input');
    var meterInput = $('#meter_name_input');

    var name_status_icon = $('#name_status_icon');
    var address_status_icon = $('#address_status_icon');
    var owner_status_icon = $('#owner_status_icon');
    var energy_object_status_icon = $('#energy_object_status_icon');
    var meter_status_icon = $('#meter_status_icon');

    var name_error_message = $('#name_error_message');
    var address_error_message = $('#address_error_message');
    var owner_error_message = $('#owner_error_message');
    var energy_object_error_message = $('#energy_object_error_message');
    var meter_error_message = $('#meter_error_message');

    var add_energy_object_error = $('#add_energy_object_error');

    var counterKeys = 0;
    var ownerInputOldVal = '';
    var energyObjectInputOldVal = '';
    var meterInputOldVal = '';

    var success_fields = [false, false, false, true, true];
    if(document.getElementById("edit_energy_object_name")) for(var i=0; i<success_fields.length; i++) success_fields[i] = true;

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

    $(objectOwnerInput).on('click', function() {
        ownerInputOldVal = $(objectOwnerInput).val();
        //console.log('energyObjectInput.click(): value = ' + $(energyObjectInput).val());
        $(objectOwnerInput).val("");
    });

    $(objectOwnerInput).focusout(function() {
        //console.log('energyObjectInput.focusout() called;');
        if($(objectOwnerInput).val().length === 0 && ownerInputOldVal.length !== 0) {
            $(objectOwnerInput).val(ownerInputOldVal);
        }
    });

    $(energyObjectInput).on('input', function() {
        var val = this.value;

        energyObjectInputOldVal = val;

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

    //$(energyObjectInput).on('click', function() {
    $(energyObjectInput).on('focusin', function() {
        energyObjectInputOldVal = $(energyObjectInput).val();
        console.log('energyObjectInput.select(): value = ' + $(energyObjectInput).val());
        $(energyObjectInput).val("");
    });

    $(energyObjectInput).focusout(function() {
        //console.log('energyObjectInput.focusout() called;');
        if($(energyObjectInput).val().length === 0 && energyObjectInputOldVal.length !== 0) {
            $(energyObjectInput).val(energyObjectInputOldVal);
        }
    });

    $(energyObjectInput).on('keydown', function() {
        var key = event.keyCode || event.charCode;

        if((key == 8 || key == 46) && energyObjectInput.val().length === 0) {
            console.log('input del pressed');
            setIconClass(energy_object_status_icon, 'icon_complete');
            energyObjectInputOldVal = '';
        }
    });

    $(meterInput).on('input', function() {
        var val = this.value;

        if(counterKeys === 0) {
            setIconClass(meter_status_icon, 'icon_typing');
        }
        counterKeys++;

        if($('#meters_list').find('option').filter(function(){
                return this.value.toUpperCase() === val.toUpperCase();
            }).length) {
            donetypingMeter();
        }
    });

    $(meterInput).on('click', function() {
        meterInputOldVal = $(meterInput).val();
        //console.log('energyObjectInput.click(): value = ' + $(energyObjectInput).val());
        $(meterInput).val("");
    });

    $(meterInput).focusout(function() {
        //console.log('energyObjectInput.focusout() called;');
        if($(meterInput).val().length === 0 && meterInputOldVal.length !== 0) {
            $(meterInput).val(meterInputOldVal);
        }
    });

    $(meterInput).on('keydown', function() {
        var key = event.keyCode || event.charCode;

        if((key == 8 || key == 46) && meterInput.val().length === 0) {
            console.log('input del pressed');
            setIconClass(meter_status_icon, 'icon_complete');
            meterInputOldVal = '';
        }
    });

    $(objectNameInput).donetyping(donetypingName);
    $(objectAddressInput).donetyping(donetypingAddress);
    $(objectOwnerInput).donetyping(donetypingOwner);
    $(energyObjectInput).donetyping(donetypingEnergyObject);
    $(meterInput).donetyping(donetypingMeter);

    // ------ Проверка имени объекта -------
    function donetypingName() {
        $form_data = {'object_name_input' : objectNameInput.val(), 'edit_energy_object_name' : $('#edit_energy_object_name').val()};

        makeAjaxPost(0, myScript.askue_plugin_url + "/askue/pages/add_energy_object/check_name.php", $form_data, name_error_message, name_status_icon);
        counterKeys = 0;
    }

    // ------ Проверка адреса объекта -------
    function donetypingAddress() {
        $form_data = {'object_address_input' : objectAddressInput.val(), 'edit_energy_object_address' : $('#edit_energy_object_address').val()};

        makeAjaxPost(1, myScript.askue_plugin_url + "/askue/pages/add_energy_object/check_address.php", $form_data, address_error_message, address_status_icon);
        counterKeys = 0;
    }

    // ------ Проверка адреса объекта -------
    function donetypingOwner() {
        $form_data = {'object_owner_input' : objectOwnerInput.val()};

        makeAjaxPost(2, myScript.askue_plugin_url + "/askue/pages/add_energy_object/check_owner.php", $form_data, owner_error_message, owner_status_icon);
        counterKeys = 0;
    }

    // ------ Проверка объекта -------
    function donetypingEnergyObject() {

        $form_data = {'energy_object_input' : energyObjectInput.val(), 'nullableEnergyObject' : true};
        //energyObjectInputOldVal = energyObjectInput.val();
        //makeAjaxPost(2, "../../wp-content/plugins/askue/pages/add_meter/check_object.php", $form_data, energy_object_error_message, energy_object_status_icon);
        makeAjaxPost(3, myScript.askue_plugin_url + "/askue/pages/add_meter/check_object.php", $form_data, energy_object_error_message, energy_object_status_icon);
        counterKeys = 0;
    }

    // ------ Проверка названия счетчика -------
    function donetypingMeter() {

        $form_data = {'meter_name_input' : meterInput.val()};
        meterInputOldVal = meterInput.val();
        //makeAjaxPost(2, "../../wp-content/plugins/askue/pages/add_meter/check_object.php", $form_data, energy_object_error_message, energy_object_status_icon);
        makeAjaxPost(4, myScript.askue_plugin_url + "/askue/pages/add_energy_object/check_meter.php", $form_data, meter_error_message, meter_status_icon);
        counterKeys = 0;
    }

    function checkAllFields() {
        if(!success_fields[0]) donetypingName();
        if(!success_fields[1]) donetypingAddress();
        if(!success_fields[2]) donetypingOwner();
        if(!success_fields[3]) donetypingEnergyObject();
        if(!success_fields[4]) donetypingMeter();
    }

    // Set up an event listener for the contact form.
    $(form).submit(function(event) {
        //console.log('submit');
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
                url: myScript.askue_plugin_url + "/askue/pages/add_energy_object/final_check.php",
                data: formData
            }).done(function(response) {
                if(myScript.is_admin) {
                    window.location.replace(myScript.site_url + "/wp-admin/admin.php?page=askue_menu");
                }
                else {
                    window.location.replace(myScript.site_url + "/user-room/meters-management/");
                }
            }).fail(function(data) {
                console.log('add meter error!');
                if (data.responseText !== '') {
                    console.log('response: ' + data.responseText);
                    add_energy_object_error.text('Ошибка: '+ data.responseText);
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
            console.log(response);

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