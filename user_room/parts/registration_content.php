<script type="text/javascript">
    jQuery("li.current-page-ancestor").addClass('current-menu-item');
</script>

<?php wp_enqueue_script('donetype_script'); ?>
<?php wp_enqueue_script('registration_ajax'); ?>


<table class="registration_table_main" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">
            <div class="registr_line"></div>
            <div class="registr_header">
                <div class="login_title">Регистрация нового пользователя</div>
            </div>
        </td>
    </tr>

    <tr align="left"><td>
            <!-- Форма регистрации -->
            <form method="post" action="" id="ajax-registration">
                <table class="registrationTable" cellspacing="0" cellpadding="0">

                    <!---------------- Login input ---------------->
                    <tr><th>
                            Логин пользователя:
                    </th></tr>

                    <tr><td>
                            <!-- Error message box -->
                            <div class="reg_error_container">
                                <div class="reg_error_text" id="login_error_message"></div>
                            </div>

                            <div class="user_room_input_spacing">
                                <!-- Login icon: -->
                                <div style="position:absolute;"><div class="icon_error" id="login_status_icon"></div></div>
                                <!-- Login input: -->
                                <input type="text" name="login_input" id="login_input" class="user_room_input" autocomplete="off" placeholder="Логин для входа в личный кабинет">
                            </div>
                    </td></tr>


                    <!---------------- Password input ---------------->
                    <tr><th>
                            Пароль:
                    </th></tr>

                    <tr><td>
                            <div class="reg_error_container">
                                <div class="reg_error_text" id="pass_error_message"></div>
                            </div>
                            <div class="user_room_input_spacing">
                                <div style="position:absolute;"><div class="icon_error" id="pass_status_icon"></div></div>
                                <input type="password" name="pass_input" id="pass_input" class="user_room_input" autocomplete="off" placeholder="Пароль для входа в личный кабинет">
                            </div>

                    </td></tr>


                    <!------------ Repeat password input ------------>
                    <tr><th>
                            Повтор пароля:
                    </th></tr>

                    <tr><td>
                            <div class="reg_error_container">
                                <div class="reg_error_text" id="pass_repeat_error_message"></div>
                            </div>
                            <div class="user_room_input_spacing">
                                <div style="position:absolute;"><div class="icon_error" id="pass_repeat_status_icon"></div></div>
                                <input type="password" name="pass_repeat_input" id="pass_repeat_input" class="user_room_input" autocomplete="off" placeholder="Повторите ввод пароля">
                            </div>
                    </td></tr>

                    <!---------------- Email input ---------------->
                    <tr><th>
                            E-mail:
                    </th></tr>

                    <tr><td>
                            <div class="reg_error_container">
                                <div class="reg_error_text" id="email_error_message"></div>
                            </div>
                            <div class="user_room_input_spacing">
                                <div style="position:absolute;"><div class="icon_error" id="email_status_icon"></div></div>
                                <input type="email" name="email_input" id="email_input" class="user_room_input" autocomplete="off" placeholder="Адрес вашей электронной почты">
                            </div>
                    </td></tr>


                    <!---------------- Agreement checkbox ---------------->
                    <tr>
                        <td>
                            <div class="reg_error_container">
                                <div class="reg_error_text" id="agreement_error_message"></div>
                            </div>

                            <div style="display:inline-block; margin-top:5px;">
                                <input type="checkbox" name="agreement" id="agreementBox" style="width:15px; height:15px;">


                            <div style="display:inline-block; vertical-align: top;">Я даю согласие на обработку персональных данных (<a href="/user_room/agreement.php" target="_blank">соглашение</a>)</div>
                            </div>

                        </td>
                    </tr>

                    <tr>
                        <td align="right"><input type="submit" name="reg_butt" value="Зарегистрировать" class="login_button login_button1"></td>
                    </tr>
                </table>
            </form>

        </td></tr>
</table>