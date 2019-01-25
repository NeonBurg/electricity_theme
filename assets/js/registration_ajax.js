jQuery(document).ready(function($) {

    // Get the form
    var form = $('#ajax-registration');

    // Get the inputs
    var loginInput = $('#login_input');
    var passwordInput = $('#password_input');
    var repeatPasswordInput = $('#repeat_password_input');
    var nameInput = $('#name_input');
    var surnameInput = $('#surname_input');
    var patronymicInput = $('#patronymic_input');
    var phoneInput = $('#phone_input');
    var emailInput = $('#email_input');

    var login_status_icon = $('#login_status_icon');
    var password_status_icon = $('#password_status_icon');
    var repeat_password_status_icon = $('#repeat_password_status_icon');
    var name_status_icon = $('#name_status_icon');
    var surname_status_icon = $('#surname_status_icon');
    var patronymic_status_icon = $('#patronymic_status_icon');
    var phone_status_icon = $('#phone_status_icon');
    var email_status_icon = $('#email_status_icon');

    var login_error_message = $('#login_error_message');
    var password_error_message = $('#password_error_message');
    var repeat_password_error_message = $('#repeat_password_error_message');
    var name_error_message = $('#name_error_message');
    var surname_error_message = $('#surname_error_message');
    var patronymic_error_message = $('#patronymic_error_message');
    var phone_error_message = $('#phone_error_message');
    var email_error_message = $('#email_error_message');

    var agreement_checkbox = $('#agreementBox');

    var counterKeys = 0;

    var success_fields = [false, false, false, false, false, false, true, true];
    //if(document.getElementById("edit_group_name")) for(var i=0; i<success_fields.length; i++) success_fields[i] = true;

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


    $(loginInput).donetyping(donetypingLogin);
    $(passwordInput).donetyping(donetypingPassword);
    $(repeatPasswordInput).donetyping(donetypingRepeatPassword);
    $(nameInput).donetyping(donetypingName);
    $(surnameInput).donetyping(donetypingSurname);
    $(patronymicInput).donetyping(donetypingPatronymic);
    $(phoneInput).donetyping(donetypingPhone);
    $(emailInput).donetyping(donetypingEmail);


    // ------ Проверка логина -------
    function donetypingLogin() {
        //$form_data = {'meter_name_input' : $('#meter_name_input').val(), 'edit_meter_name' : $('#edit_meter_name').val(), 'edit_meter_energy_object_name' : $('#edit_meter_energy_object_name').val()};
        $form_data = {'login_input' : $('#login_input').val()};

        //var edit_meter_name = document.getElementById("edit_meter_name");

        makeAjaxPost(0, myScript.site_url + "/user_room/registration/check_login.php", $form_data, login_error_message, login_status_icon);
        counterKeys = 0;
    }

    // ------ Проверка пароля -------
    function donetypingPassword() {
        //$form_data = {'meter_name_input' : $('#meter_name_input').val(), 'edit_meter_name' : $('#edit_meter_name').val(), 'edit_meter_energy_object_name' : $('#edit_meter_energy_object_name').val()};
        $form_data = {'password_input' : $('#password_input').val()};

        //var edit_meter_name = document.getElementById("edit_meter_name");

        makeAjaxPost(1, myScript.site_url + "/user_room/registration/check_pass.php", $form_data, password_error_message, password_status_icon);
        counterKeys = 0;
    }

    // ------ Проверка повтора пароля -------
    function donetypingRepeatPassword() {
        //$form_data = {'meter_name_input' : $('#meter_name_input').val(), 'edit_meter_name' : $('#edit_meter_name').val(), 'edit_meter_energy_object_name' : $('#edit_meter_energy_object_name').val()};
        $form_data = {'password_input' : $('#password_input').val(), 'repeat_password_input' : $('#repeat_password_input').val()};

        //var edit_meter_name = document.getElementById("edit_meter_name");

        makeAjaxPost(2, myScript.site_url + "/user_room/registration/check_pass_repeat.php", $form_data, repeat_password_error_message, repeat_password_status_icon);
        counterKeys = 0;
    }

    // ------ Проверка фамилии -------
    function donetypingSurname() {
        //$form_data = {'meter_name_input' : $('#meter_name_input').val(), 'edit_meter_name' : $('#edit_meter_name').val(), 'edit_meter_energy_object_name' : $('#edit_meter_energy_object_name').val()};
        $form_data = {'surname_input' : $('#surname_input').val()};

        //var edit_meter_name = document.getElementById("edit_meter_name");

        makeAjaxPost(3, myScript.site_url + "/wp-content/plugins/askue/pages/accounts_manage/add_user/check_surname.php", $form_data, surname_error_message, surname_status_icon);
        counterKeys = 0;
    }

    // ------ Проверка имени -------
    function donetypingName() {
        //$form_data = {'meter_name_input' : $('#meter_name_input').val(), 'edit_meter_name' : $('#edit_meter_name').val(), 'edit_meter_energy_object_name' : $('#edit_meter_energy_object_name').val()};
        $form_data = {'name_input' : $('#name_input').val()};

        //var edit_meter_name = document.getElementById("edit_meter_name");

        makeAjaxPost(4, myScript.site_url + "/wp-content/plugins/askue/pages/accounts_manage/add_user/check_name.php", $form_data, name_error_message, name_status_icon);
        counterKeys = 0;
    }

    // ------ Проверка отчества -------
    function donetypingPatronymic() {
        //$form_data = {'meter_name_input' : $('#meter_name_input').val(), 'edit_meter_name' : $('#edit_meter_name').val(), 'edit_meter_energy_object_name' : $('#edit_meter_energy_object_name').val()};
        $form_data = {'patronymic_input' : $('#patronymic_input').val()};

        //var edit_meter_name = document.getElementById("edit_meter_name");

        makeAjaxPost(5, myScript.askue_plugin_url + "/askue/pages/accounts_manage/add_user/check_patronymic.php", $form_data, patronymic_error_message, patronymic_status_icon);
        counterKeys = 0;
    }

    // ------ Проверка телефона -------
    function donetypingPhone() {
        //$form_data = {'meter_name_input' : $('#meter_name_input').val(), 'edit_meter_name' : $('#edit_meter_name').val(), 'edit_meter_energy_object_name' : $('#edit_meter_energy_object_name').val()};
        $form_data = {'phone_input' : $('#phone_input').val()};

        //var edit_meter_name = document.getElementById("edit_meter_name");

        makeAjaxPost(6, myScript.askue_plugin_url + "/askue/pages/accounts_manage/add_user/check_phone.php", $form_data, phone_error_message, phone_status_icon);
        counterKeys = 0;
    }

    // ------ Проверка email адреса -------
    function donetypingEmail() {
        //$form_data = {'meter_name_input' : $('#meter_name_input').val(), 'edit_meter_name' : $('#edit_meter_name').val(), 'edit_meter_energy_object_name' : $('#edit_meter_energy_object_name').val()};
        $form_data = {'email_input' : $('#email_input').val()};

        //var edit_meter_name = document.getElementById("edit_meter_name");

        makeAjaxPost(7, myScript.askue_plugin_url + "/askue/pages/accounts_manage/add_user/check_email.php", $form_data, email_error_message, email_status_icon);
        counterKeys = 0;
    }

    function checkAllFields() {
        if(!success_fields[0]) donetypingLogin();
        if(!success_fields[1]) donetypingPassword();
        if(!success_fields[2]) donetypingRepeatPassword();
        if(!success_fields[3]) donetypingSurname();
        if(!success_fields[4]) donetypingName();
        if(!success_fields[5]) donetypingPatronymic();
        if(!success_fields[6]) donetypingPhone();
        if(!success_fields[7]) donetypingEmail();
    }

    $(form).submit(function(event) {
        // Stop the browser from submitting the form.
        event.preventDefault();

        if($(agreement_checkbox).is(':checked')) {
            //console.log('success_fields.size = ' + success_fields.length);
            var send_registration = true;
            //console.log('send_registration = ' + send_registration);
            jQuery.each(success_fields, function(index, item) {
                //console.log('success_fields['+index+'] = ' + success_fields[index] + " | item = " + item);
                if(!item) send_registration = false;
            });
            //console.log('send_registration = ' + send_registration);
            if(send_registration) {
                var formData = $(form).serialize();

                $.ajax({
                    type: 'POST',
                    url: myScript.site_url + '/user_room/registration/final_check.php',
                    data: formData
                }).done(function(response) {
                    console.log('registration success');
                    window.location.replace(myScript.site_url + "/user-room/auth/?login='.$login.'&registration_success=Успешная регистрация");

                }).fail(function(data) {
                    console.log('registration error!');
                    if (data.responseText !== '') {
                        console.log('response: ' + data.responseText);
                    }
                });
            }
            else {
                checkAllFields();
            }
        }
        else {
            checkAllFields();
            $(agreement_error_message).text('Вы не дали согласие на обработку персональных данных');
        }

    });

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