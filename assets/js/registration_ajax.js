jQuery(document).ready(function($) {
    // Get the form
    var form = $('#ajax-registration');

    // Get the inputs
    var inputLogin = $('#login_input');
    var inputPass = $('#pass_input');
    var inputPassRepeat = $('#pass_repeat_input');
    var inputEmail = $('#email_input');

    var login_status_icon = $('#login_status_icon');
    var pass_status_icon = $('#pass_status_icon');
    var pass_repeat_status_icon = $('#pass_repeat_status_icon');
    var email_status_icon = $('#email_status_icon');

    var login_error_message = $('#login_error_message');
    var pass_error_message = $('#pass_error_message');
    var pass_repeat_error_message = $('#pass_repeat_error_message');
    var email_error_message = $('#email_error_message');
    var agreement_error_message = $('#agreement_error_message');

    var agreement_checkbox = $('#agreementBox');

    var counterKeys = 0;

    var success_fields = [false, false, false, true];

    $(inputLogin).on('input', function() {
        if(counterKeys === 0) {
            setIconClass(login_status_icon, 'icon_typing');
        }
        counterKeys++;
    });

    $(inputPass).on('input', function() {
        if(counterKeys === 0) {
            setIconClass(pass_status_icon, 'icon_typing');
        }
        counterKeys++;
    });

    $(inputPassRepeat).on('input', function() {
        if(counterKeys === 0) {
            setIconClass(pass_repeat_status_icon, 'icon_typing');
        }
        counterKeys++;
    });

    $(inputEmail).on('input', function() {
        if(counterKeys === 0) {
            setIconClass(email_status_icon, 'icon_typing');
        }
        counterKeys++;
    });

    $(inputLogin).donetyping(donetypingLogin);
    $(inputPass).donetyping(donetypingPass);
    $(inputPassRepeat).donetyping(donetypingPassRepeat);
    $(inputEmail).donetyping(donetypingEmail);

    $(agreement_checkbox).change(
        function(){
            if ($(agreement_checkbox).is(':checked')) {
                $(agreement_error_message).text('');
            }
        });

    // Set up an event listener for the contact form.
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
                    url: '/user_room/registration/final_check.php',
                    data: formData
                }).done(function(response) {
                    console.log('registration success');
                    window.location.replace("/user_room_page/auth/?login='.$login.'&registration_success=Успешная регистрация");

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


    // -------------- Функции проверки полей для ввода -------------

    function checkAllFields() {
        if(!success_fields[0]) donetypingLogin();
        if(!success_fields[1]) donetypingPass();
        if(!success_fields[2]) donetypingPassRepeat();
        if(!success_fields[3]) donetypingEmail();
    }

    // ------ Проверка логина -------
    function donetypingLogin() {

        $form_data = {'login_input' : $('#login_input').val()};
        makeAjaxPost(0, '/user_room/registration/check_login.php', $form_data, login_error_message, login_status_icon);
        counterKeys = 0;
    }

    // ------ Проверка пароля -------
    function donetypingPass() {

        $form_data = {'pass_input' : $('#pass_input').val()};
        makeAjaxPost(1, '/user_room/registration/check_pass.php', $form_data, pass_error_message, pass_status_icon);
        counterKeys = 0;
    }

    // ------ Проверка повтора пароля -------
    function donetypingPassRepeat() {

        $form_data = {'pass_input' : $('#pass_input').val(),
            'pass_repeat_input' : $('#pass_repeat_input').val()};
        makeAjaxPost(2, '/user_room/registration/check_pass_repeat.php', $form_data, pass_repeat_error_message, pass_repeat_status_icon);
        counterKeys = 0;
    }

    // ------ Проверка email -------
    function donetypingEmail() {

        $form_data = {'email_input' : $('#email_input').val()};
        makeAjaxPost(3, '/user_room/registration/check_email.php', $form_data, email_error_message, email_status_icon);
        counterKeys = 0;
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

        }).fail(function(data) {
            setIconClass(status_icon_box, 'icon_error');
            // Set error message
            if (data.responseText !== '') {
                $(error_message_box).text(data.responseText);
            }
            success_fields[field_index] = false;
        });
    }
    // ----------------------------------------------------------------------
});

function setIconClass(icon, icon_class) {
    console.log('setIcon: ' + icon_class + " | ");
    icon.removeClass(icon.attr('class'));
    icon.addClass(icon_class);
    icon.css('visibility', 'visible');
}