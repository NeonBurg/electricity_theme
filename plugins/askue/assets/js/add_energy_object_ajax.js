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
    var ownerInputOldVal = '';

    var success_fields = [false, false, false];
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

    $(objectNameInput).donetyping(donetypingName);
    $(objectAddressInput).donetyping(donetypingAddress);
    $(objectOwnerInput).donetyping(donetypingOwner);

    // ------ Проверка имени объекта -------
    function donetypingName() {
        $form_data = {'object_name_input' : $('#object_name_input').val(), 'edit_energy_object_name' : $('#edit_energy_object_name').val()};

        makeAjaxPost(0, myScript.askue_plugin_url + "/askue/pages/add_energy_object/check_name.php", $form_data, name_error_message, name_status_icon);
        counterKeys = 0;
    }

    // ------ Проверка адреса объекта -------
    function donetypingAddress() {
        $form_data = {'object_address_input' : $('#object_address_input').val(), 'edit_energy_object_address' : $('#edit_energy_object_address').val()};

        makeAjaxPost(1, myScript.askue_plugin_url + "/askue/pages/add_energy_object/check_address.php", $form_data, address_error_message, address_status_icon);
        counterKeys = 0;
    }

    // ------ Проверка адреса объекта -------
    function donetypingOwner() {
        $form_data = {'object_owner_input' : $('#object_owner_input').val()};

        makeAjaxPost(2, myScript.askue_plugin_url + "/askue/pages/add_energy_object/check_owner.php", $form_data, owner_error_message, owner_status_icon);
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
                url: myScript.askue_plugin_url + "/askue/pages/add_energy_object/final_check.php",
                data: formData
            }).done(function(response) {
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
            if(field_index === 2) console.log(response);

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