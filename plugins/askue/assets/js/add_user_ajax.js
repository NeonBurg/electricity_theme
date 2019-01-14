jQuery(document).ready(function($) {

    // Get the form
    var form = $('#ajax-add-user');

    // Get the inputs
    var loginInput = $('#login_input');
    var passwordInput = $('#password_input');
    var repeatPasswordInput = $('#repeat_password_input');
    var nameInput = $('#name_input');
    var surnameInput = $('#surname_input');
    var patronymicInput = $('#patronymic_input');
    var phoneInput = $('#phone_input');
    var emailInput = $('#email_input');
    var groupInput = $('#group_input');

    var login_status_icon = $('#login_status_icon');
    var password_status_icon = $('#password_status_icon');
    var repeat_password_status_icon = $('#repeat_password_status_icon');
    var name_status_icon = $('#name_status_icon');
    var surname_status_icon = $('#surname_status_icon');
    var patronymic_status_icon = $('#patronymic_status_icon');
    var phone_status_icon = $('#phone_status_icon');
    var email_status_icon = $('#email_status_icon');
    var group_status_icon = $('#group_status_icon');

    var login_error_message = $('#login_error_message');
    var password_error_message = $('#password_error_message');
    var repeat_password_error_message = $('#repeat_password_error_message');
    var name_error_message = $('#name_error_message');
    var surname_error_message = $('#surname_error_message');
    var patronymic_error_message = $('#patronymic_error_message');
    var phone_error_message = $('#phone_error_message');
    var email_error_message = $('#email_error_message');
    var group_error_message = $('#group_error_message');

    var counterKeys = 0;

    var success_fields = [false, false, false, false, false, false, false, true, false];

    var edit_user_id = null;
    var groupInputOldVal = '';

    if(document.getElementById("edit_user_id")) {
        for(var i=0; i<success_fields.length; i++) success_fields[i] = true;
        edit_user_id = document.getElementById("edit_user_id").value;
    }

    $(loginInput).on('input', function() {
        if(counterKeys === 0) {
            setIconClass(login_status_icon, 'icon_typing');
        }
        counterKeys++;
    });

    $(passwordInput).on('input', function() {
        if(counterKeys === 0) {
            setIconClass(password_status_icon, 'icon_typing');
        }
        counterKeys++;
    });

    $(repeatPasswordInput).on('input', function() {
        if(counterKeys === 0) {
            setIconClass(repeat_password_status_icon, 'icon_typing');
        }
        counterKeys++;
    });

    $(nameInput).on('input', function() {
        if(counterKeys === 0) {
            setIconClass(name_status_icon, 'icon_typing');
        }
        counterKeys++;
    });

    $(surnameInput).on('input', function() {
        if(counterKeys === 0) {
            setIconClass(surname_status_icon, 'icon_typing');
        }
        counterKeys++;
    });

    $(patronymicInput).on('input', function() {
        if(counterKeys === 0) {
            setIconClass(patronymic_status_icon, 'icon_typing');
        }
        counterKeys++;
    });

    $(phoneInput).on('input', function() {
        if(counterKeys === 0) {
            setIconClass(phone_status_icon, 'icon_typing');
        }
        counterKeys++;
    });

    $(emailInput).on('input', function() {
        if(counterKeys === 0) {
            setIconClass(email_status_icon, 'icon_typing');
        }
        counterKeys++;
    });

    $(groupInput).on('input', function() {
        var val = this.value;

        if(counterKeys === 0) {
            setIconClass(group_status_icon, 'icon_typing');
        }
        counterKeys++;

        if($('#user_groups_list').find('option').filter(function(){
                return this.value.toUpperCase() === val.toUpperCase();
            }).length) {
            donetypingGroup();
        }
    });

    $(groupInput).on('click', function() {
        groupInputOldVal = $(groupInput).val();
        //console.log('energyObjectInput.click(): value = ' + $(energyObjectInput).val());
        $(groupInput).val("");
    });

    $(groupInput).focusout(function() {
        //console.log('energyObjectInput.focusout() called;');
        if($(groupInput).val().length === 0 && groupInputOldVal.length !== 0) {
            $(groupInput).val(groupInputOldVal);
        }
    });

    $(loginInput).donetyping(donetypingLogin);
    $(passwordInput).donetyping(donetypingPassword);
    $(repeatPasswordInput).donetyping(donetypingRepeatPassword);
    $(nameInput).donetyping(donetypingName);
    $(surnameInput).donetyping(donetypingSurname);
    $(patronymicInput).donetyping(donetypingPatronymic);
    $(phoneInput).donetyping(donetypingPhone);
    $(emailInput).donetyping(donetypingEmail);
    $(groupInput).donetyping(donetypingGroup);


    // ------ Проверка логина -------
    function donetypingLogin() {
        //$form_data = {'meter_name_input' : $('#meter_name_input').val(), 'edit_meter_name' : $('#edit_meter_name').val(), 'edit_meter_energy_object_name' : $('#edit_meter_energy_object_name').val()};
        $form_data = {'login_input' : $('#login_input').val(), 'edit_user_login' : $('#edit_user_login').val()};

        //var edit_meter_name = document.getElementById("edit_meter_name");

        //makeAjaxPost(0, "../../user_room/registration/check_login.php", $form_data, login_error_message, login_status_icon);
        makeAjaxPost(0, "/user_room/registration/check_login.php", $form_data, login_error_message, login_status_icon);
        counterKeys = 0;
    }

    // ------ Проверка пароля -------
    function donetypingPassword() {
        //$form_data = {'meter_name_input' : $('#meter_name_input').val(), 'edit_meter_name' : $('#edit_meter_name').val(), 'edit_meter_energy_object_name' : $('#edit_meter_energy_object_name').val()};
        $form_data = {'password_input' : passwordInput.val(), 'edit_user_id' : edit_user_id};

        //var edit_meter_name = document.getElementById("edit_meter_name");

        //console.log('donetypingPassword: edit_user = ' + edit_user + " | passwordInput.val().length = " + passwordInput.val().length);

        if(edit_user_id !== null && passwordInput.val().length === 0) {
            password_error_message.text('');
            setIconInvisible(password_status_icon);
            success_fields[1] = true;
        }
        else {
            //makeAjaxPost(1, "../../wp-content/plugins/askue/pages/accounts_manage/add_user/check_password.php", $form_data, password_error_message, password_status_icon);
            makeAjaxPost(1, myScript.askue_plugin_url + "/askue/pages/accounts_manage/add_user/check_password.php", $form_data, password_error_message, password_status_icon);
            success_fields[2] = false;
        }
        counterKeys = 0;
    }

    // ------ Проверка повтора пароля -------
    function donetypingRepeatPassword() {
        //$form_data = {'meter_name_input' : $('#meter_name_input').val(), 'edit_meter_name' : $('#edit_meter_name').val(), 'edit_meter_energy_object_name' : $('#edit_meter_energy_object_name').val()};
        $form_data = {'password_input' : $('#password_input').val(), 'repeat_password_input' : $('#repeat_password_input').val(), 'edit_user_id' : edit_user_id};

        //var edit_meter_name = document.getElementById("edit_meter_name");

        if(edit_user_id !== null && passwordInput.val().length === 0 && repeatPasswordInput.val().length === 0) {
            repeat_password_error_message.text('');
            setIconInvisible(repeat_password_status_icon);
            success_fields[2] = true;
        }
        else {
            //makeAjaxPost(2, "../../wp-content/plugins/askue/pages/accounts_manage/add_user/check_password_repeat.php", $form_data, repeat_password_error_message, repeat_password_status_icon);
            makeAjaxPost(2, myScript.askue_plugin_url + "/askue/pages/accounts_manage/add_user/check_password_repeat.php", $form_data, repeat_password_error_message, repeat_password_status_icon);
        }
        counterKeys = 0;
    }

    // ------ Проверка фамилии -------
    function donetypingSurname() {
        //$form_data = {'meter_name_input' : $('#meter_name_input').val(), 'edit_meter_name' : $('#edit_meter_name').val(), 'edit_meter_energy_object_name' : $('#edit_meter_energy_object_name').val()};
        $form_data = {'surname_input' : $('#surname_input').val()};

        //var edit_meter_name = document.getElementById("edit_meter_name");

        //makeAjaxPost(3, "../../wp-content/plugins/askue/pages/accounts_manage/add_user/check_surname.php", $form_data, surname_error_message, surname_status_icon);
        makeAjaxPost(3, myScript.askue_plugin_url + "/askue/pages/accounts_manage/add_user/check_surname.php", $form_data, surname_error_message, surname_status_icon);
        counterKeys = 0;
    }

    // ------ Проверка имени -------
    function donetypingName() {
        //$form_data = {'meter_name_input' : $('#meter_name_input').val(), 'edit_meter_name' : $('#edit_meter_name').val(), 'edit_meter_energy_object_name' : $('#edit_meter_energy_object_name').val()};
        $form_data = {'name_input' : $('#name_input').val()};

        //var edit_meter_name = document.getElementById("edit_meter_name");

        //makeAjaxPost(4, "../../wp-content/plugins/askue/pages/accounts_manage/add_user/check_name.php", $form_data, name_error_message, name_status_icon);
        makeAjaxPost(4, myScript.askue_plugin_url + "/askue/pages/accounts_manage/add_user/check_name.php", $form_data, name_error_message, name_status_icon);
        counterKeys = 0;
    }

    // ------ Проверка отчества -------
    function donetypingPatronymic() {
        //$form_data = {'meter_name_input' : $('#meter_name_input').val(), 'edit_meter_name' : $('#edit_meter_name').val(), 'edit_meter_energy_object_name' : $('#edit_meter_energy_object_name').val()};
        $form_data = {'patronymic_input' : $('#patronymic_input').val()};

        //var edit_meter_name = document.getElementById("edit_meter_name");

        //makeAjaxPost(5, "../../wp-content/plugins/askue/pages/accounts_manage/add_user/check_patronymic.php", $form_data, patronymic_error_message, patronymic_status_icon);
        makeAjaxPost(5, myScript.askue_plugin_url + "/askue/pages/accounts_manage/add_user/check_patronymic.php", $form_data, patronymic_error_message, patronymic_status_icon);
        counterKeys = 0;
    }

    // ------ Проверка группы -------
    function donetypingGroup() {
        //$form_data = {'meter_name_input' : $('#meter_name_input').val(), 'edit_meter_name' : $('#edit_meter_name').val(), 'edit_meter_energy_object_name' : $('#edit_meter_energy_object_name').val()};
        $form_data = {'group_input' : $('#group_input').val()};

        //var edit_meter_name = document.getElementById("edit_meter_name");

        //makeAjaxPost(6, "../../wp-content/plugins/askue/pages/accounts_manage/add_user/check_group.php", $form_data, group_error_message, group_status_icon);
        makeAjaxPost(6, myScript.askue_plugin_url + "/askue/pages/accounts_manage/add_user/check_group.php", $form_data, group_error_message, group_status_icon);
        counterKeys = 0;
    }

    // ------ Проверка телефона -------
    function donetypingPhone() {
        //$form_data = {'meter_name_input' : $('#meter_name_input').val(), 'edit_meter_name' : $('#edit_meter_name').val(), 'edit_meter_energy_object_name' : $('#edit_meter_energy_object_name').val()};
        $form_data = {'phone_input' : $('#phone_input').val(), 'edit_user_phone' : $('#edit_user_phone').val()};

        //var edit_meter_name = document.getElementById("edit_meter_name");

        //makeAjaxPost(7, "../../wp-content/plugins/askue/pages/accounts_manage/add_user/check_phone.php", $form_data, phone_error_message, phone_status_icon);
        makeAjaxPost(7, myScript.askue_plugin_url + "/askue/pages/accounts_manage/add_user/check_phone.php", $form_data, phone_error_message, phone_status_icon);
        counterKeys = 0;
    }

    // ------ Проверка email адреса -------
    function donetypingEmail() {
        //$form_data = {'meter_name_input' : $('#meter_name_input').val(), 'edit_meter_name' : $('#edit_meter_name').val(), 'edit_meter_energy_object_name' : $('#edit_meter_energy_object_name').val()};
        $form_data = {'email_input' : $('#email_input').val(), 'edit_user_email' : $('#edit_user_email').val()};

        //var edit_meter_name = document.getElementById("edit_meter_name");

        //makeAjaxPost(8, "../../wp-content/plugins/askue/pages/accounts_manage/add_user/check_email.php", $form_data, email_error_message, email_status_icon);
        makeAjaxPost(8, myScript.askue_plugin_url + "/askue/pages/accounts_manage/add_user/check_email.php", $form_data, email_error_message, email_status_icon);
        counterKeys = 0;
    }

    function checkAllFields() {
        if(!success_fields[0]) donetypingLogin();
        if(!success_fields[1]) donetypingPassword();
        if(!success_fields[2]) donetypingRepeatPassword();
        if(!success_fields[3]) donetypingSurname();
        if(!success_fields[4]) donetypingName();
        if(!success_fields[5]) donetypingPatronymic();
        if(!success_fields[6]) donetypingGroup();
        if(!success_fields[7]) donetypingPhone();
        if(!success_fields[8]) donetypingEmail();
    }

    $(form).submit(function(event) {
        // Stop the browser from submitting the form.
        event.preventDefault();

        var send_registration = true;
        checkAllFields();

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
            //url: '../../wp-content/plugins/askue/pages/accounts_manage/add_user/final_check.php',
            url: myScript.askue_plugin_url + "/askue/pages/accounts_manage/add_user/final_check.php",
            data: formData
        }).done(function(response) {
            //console.log('registration success');
            //console.log(response);
            if(myScript.is_admin) {
                window.location.replace("/wp-admin/admin.php?page=accounts_manage");
            }
            else {
                window.location.replace("/user-room/accounts-management/");
            }

        }).fail(function(data) {
            console.log('registration error!');
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

            if(field_index === 1 && repeatPasswordInput.val().length > 0) {
                donetypingRepeatPassword()
            }

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

    function setIconInvisible(icon) {
        icon.css('visibility', 'hidden');
    }

});