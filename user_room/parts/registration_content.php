<script type="text/javascript">
    jQuery("li.current-page-ancestor").addClass('current-menu-item');
</script>

<?php wp_enqueue_script('donetype_script'); ?>
<?php wp_enqueue_script('registration_ajax'); ?>


<div class="registation-content">
<table class="registration_table_main" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">
            <div class="registr_line"></div>
            <div class="registr_header">
                <div class="login_title">Регистрация нового пользователя</div>
            </div>
        </td>
    </tr>
</table>


            <!-- Форма регистрации -->
            <form method="post" action="" id="ajax-registration">
                <div class="input-form-container">
                    <table class="inputs-vertical-table" cellspacing="0" cellpadding="0">

                        <!---------------- Login input ---------------->
                        <tr><th>
                                Логин: <div class="req_field">*</div>
                            </th></tr>

                        <tr><td>
                                <!-- Error message box -->
                                <div class="inputs_error_container_askue">
                                    <div class="inputs_error_text" id="login_error_message"></div>
                                </div>

                                <!-- Name input: -->
                                <div style="display: inline-block; width:100%; padding-right:50px;">
                                    <input type="text" name="login_input" id="login_input" class="user_room_input" autocomplete="off" placeholder="">
                                </div>

                            </td>
                            <!-- Name icon: -->
                            <td width="35" align="center"><div class="icon-status-container">
                                    <div class="icon_typing" id="login_status_icon"></div>
                                </div></td>
                        </tr>

                        <!---------------- Password input ---------------->
                        <tr><th>
                                Пароль: <div class="req_field">*</div>
                            </th></tr>

                        <tr><td>
                                <!-- Error message box -->
                                <div class="inputs_error_container_askue">
                                    <div class="inputs_error_text" id="password_error_message"></div>
                                </div>

                                <!-- Name input: -->
                                <div style="display: inline-block; width:100%; padding-right:50px;">
                                    <input type="password" name="password_input" id="password_input" class="user_room_input" autocomplete="off" placeholder="">
                                </div>

                            </td>
                            <!-- Name icon: -->
                            <td width="35" align="center"><div class="icon-status-container">
                                    <div class="icon_typing" id="password_status_icon"></div>
                                </div></td>
                        </tr>

                        <!---------------- Repeat Password input ---------------->
                        <tr><th>
                                Повтор пароля: <div class="req_field">*</div>
                            </th></tr>

                        <tr><td>
                                <!-- Error message box -->
                                <div class="inputs_error_container_askue">
                                    <div class="inputs_error_text" id="repeat_password_error_message"></div>
                                </div>

                                <!-- Name input: -->
                                <div style="display: inline-block; width:100%; padding-right:50px;">
                                    <input type="password" name="repeat_password_input" id="repeat_password_input" class="user_room_input" autocomplete="off" placeholder="">
                                </div>

                            </td>
                            <!-- Name icon: -->
                            <td width="35" align="center"><div class="icon-status-container">
                                    <div class="icon_typing" id="repeat_password_status_icon"></div>
                                </div></td>
                        </tr>

                        <!---------------- Surname input ---------------->
                        <tr><th>
                                Фамилия: <div class="req_field">*</div>
                            </th></tr>

                        <tr><td>
                                <!-- Error message box -->
                                <div class="inputs_error_container_askue">
                                    <div class="inputs_error_text" id="surname_error_message"></div>
                                </div>

                                <!-- Surname input: -->
                                <div style="display: inline-block; width:100%; padding-right:50px;">
                                    <input type="text" name="surname_input" id="surname_input" class="user_room_input" autocomplete="off" placeholder="">
                                </div>

                            </td>
                            <!-- Name icon: -->
                            <td width="35" align="center"><div class="icon-status-container">
                                    <div class="icon_typing" id="surname_status_icon"></div>
                                </div></td>
                        </tr>

                        <!---------------- Name input ---------------->
                        <tr><th>
                                Имя: <div class="req_field">*</div>
                            </th></tr>

                        <tr><td>
                                <!-- Error message box -->
                                <div class="inputs_error_container_askue">
                                    <div class="inputs_error_text" id="name_error_message"></div>
                                </div>

                                <!-- Name input: -->
                                <div style="display: inline-block; width:100%; padding-right:50px;">
                                    <input type="text" name="name_input" id="name_input" class="user_room_input" autocomplete="off" placeholder="">
                                </div>

                            </td>
                            <!-- Name icon: -->
                            <td width="35" align="center"><div class="icon-status-container">
                                    <div class="icon_typing" id="name_status_icon"></div>
                                </div></td>
                        </tr>

                        <!---------------- Patronymic input ---------------->
                        <tr><th>
                                Отчество: <div class="req_field">*</div>
                            </th></tr>

                        <tr><td>
                                <!-- Error message box -->
                                <div class="inputs_error_container_askue">
                                    <div class="inputs_error_text" id="patronymic_error_message"></div>
                                </div>

                                <!-- Patronymic input: -->
                                <div style="display: inline-block; width:100%; padding-right:50px;">
                                    <input type="text" name="patronymic_input" id="patronymic_input" class="user_room_input" autocomplete="off" placeholder="">
                                </div>

                            </td>
                            <!-- Name icon: -->
                            <td width="35" align="center"><div class="icon-status-container">
                                    <div class="icon_typing" id="patronymic_status_icon"></div>
                                </div></td>
                        </tr>

                        <!---------------- Phone input ---------------->
                        <tr><th>
                                Телефон:
                            </th></tr>

                        <tr><td>
                                <!-- Error message box -->
                                <div class="inputs_error_container_askue">
                                    <div class="inputs_error_text" id="phone_error_message"></div>
                                </div>

                                <!-- Phone input: -->
                                <div style="display: inline-block; width:100%; padding-right:50px;">
                                    <input type="text" name="phone_input" id="phone_input" class="user_room_input" autocomplete="off" placeholder="">
                                </div>

                            </td>
                            <!-- Name icon: -->
                            <td width="35" align="center"><div class="icon-status-container">
                                    <div class="icon_typing" id="phone_status_icon"></div>
                                </div></td>
                        </tr>

                        <!---------------- Email input ---------------->
                        <tr><th>
                                Email адрес:
                            </th></tr>

                        <tr><td>
                                <!-- Error message box -->
                                <div class="inputs_error_container_askue">
                                    <div class="inputs_error_text" id="email_error_message"></div>
                                </div>

                                <!-- Email input: -->
                                <div style="display: inline-block; width:100%; padding-right:50px;">
                                    <input type="text" name="email_input" id="email_input" class="user_room_input" autocomplete="off" placeholder="">
                                </div>

                            </td>
                            <!-- Name icon: -->
                            <td width="35" align="center"><div class="icon-status-container">
                                    <div class="icon_typing" id="email_status_icon"></div>
                                </div></td>
                        </tr>

                        <!---------------- Agreement checkbox ---------------->
                        <tr>
                            <td>
                                <div class="reg_error_container">
                                    <div class="reg_error_text" id="agreement_error_message"></div>
                                </div>

                                <div style="display:inline-block; margin-top:5px;">
                                    <input type="checkbox" name="agreement" id="agreementBox" style="width:15px; height:15px;">


                                    <div style="display:inline-block; vertical-align: top;">Я даю согласие на обработку персональных данных (<a href="/user_room/agreement.php" target="_blank">соглашение</a>)</div>
                                    <div class="req_field">*</div>
                                </div>

                            </td>
                        </tr>

                        <tr><td>(<div class="req_field">*</div>) - обязательные поля</td></tr>

                        <tr>
                            <td align="right"><input type="submit" name="reg_butt" value="Зарегистрировать" class="login_button login_button1"></td>
                        </tr>
                    </table>
                </div>
            </form>
</div>