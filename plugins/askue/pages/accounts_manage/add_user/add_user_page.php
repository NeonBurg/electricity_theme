<?php if($access_level == 3 || is_admin()):?>
<?php
require_once(ASKUE_PLUGIN_DIR . "models/DataController.php");

global $wpdb;
$dataController = new DataController($wpdb);

$edit_user = null;
$userGroupsList = $dataController->selectUserGroupsList();

//echo "count groups: ".count($userGroupsList)."<br>";

if(isset($_GET["edit"])) {
    $user_id = $_GET["edit"];
    $edit_user = $dataController->selectCustomer($user_id);
}
?>

<?php wp_enqueue_script('donetype_script'); ?>
<?php wp_enqueue_script('add_user_ajax'); ?>

<div class="edit-title">
    <?php if($edit_user) echo "АСКУЭ » Редактирование пользователя: '".$edit_user->getLogin()."'";
    else echo "АСКУЭ » Добавление пользователя"?>
</div>

<div class="askue-admin-content">

    <!-- Форма добавления нового счетчика -->
    <form method="post" action="" id="ajax-add-user">

        <?php if($edit_user) { echo '<input type="hidden" id="edit_user_id" name="edit_user_id" value="' . $edit_user->getId() . '">'; } ?>

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

                        <!-- Login input: -->
                        <div style="display: inline-block; width:100%; padding-right:50px;">
                            <input type="text" name="login_input" id="login_input" class="askue_input" autocomplete="off" placeholder="" <?php if($edit_user) echo 'value="'.$edit_user->getLogin().'"';?>>
                            <?php if($edit_user) {  echo '<input type="hidden" id="edit_user_login" name="edit_user_login" value="' . $edit_user->getLogin() . '">';
                                                    echo '<input type="hidden" id="edit_user_account_id" name="edit_user_account_id" value="' . $edit_user->getAccountId() . '">'; } ?>
                        </div>

                    </td>
                    <!-- Login icon: -->
                    <td width="35" align="center"><div class="icon-status-container">
                            <div class="icon_typing" id="login_status_icon"></div>
                        </div></td>
                </tr>

                <!---------------- Password input ---------------->
                <tr><th>
                        Пароль: <?php if(!$edit_user) echo '<div class="req_field">*</div>'; ?>
                    </th></tr>

                <tr><td>
                        <!-- Error message box -->
                        <div class="inputs_error_container_askue">
                            <div class="inputs_error_text" id="password_error_message"></div>
                        </div>

                        <!-- Password input: -->
                        <div style="display: inline-block; width:100%; padding-right:50px;">
                            <input type="password" name="password_input" id="password_input" class="askue_input" autocomplete="off" placeholder="">
                        </div>

                    </td>
                    <!-- Password icon: -->
                    <td width="35" align="center"><div class="icon-status-container">
                            <div class="icon_typing" id="password_status_icon"></div>
                        </div></td>
                </tr>

                <!---------------- Repeat Password input ---------------->
                <tr><th>
                        Повтор пароля: <?php if(!$edit_user) echo '<div class="req_field">*</div>'; ?>
                    </th></tr>

                <tr><td>
                        <!-- Error message box -->
                        <div class="inputs_error_container_askue">
                            <div class="inputs_error_text" id="repeat_password_error_message"></div>
                        </div>

                        <!-- Repeat Password input: -->
                        <div style="display: inline-block; width:100%; padding-right:50px;">
                            <input type="password" name="repeat_password_input" id="repeat_password_input" class="askue_input" autocomplete="off" placeholder="">
                        </div>

                    </td>
                    <!-- Repeat Password icon: -->
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
                            <input type="text" name="surname_input" id="surname_input" class="askue_input" autocomplete="off" placeholder="" <?php if($edit_user) echo 'value="'.$edit_user->getSurname().'"';?>>
                        </div>

                    </td>
                    <!-- Surname icon: -->
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
                            <input type="text" name="name_input" id="name_input" class="askue_input" autocomplete="off" placeholder="" <?php if($edit_user) echo 'value="'.$edit_user->getName().'"';?>>
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
                            <input type="text" name="patronymic_input" id="patronymic_input" class="askue_input" autocomplete="off" placeholder="" <?php if($edit_user) echo 'value="'.$edit_user->getPatronymic().'"';?>>
                        </div>

                    </td>
                    <!-- Patronymic icon: -->
                    <td width="35" align="center"><div class="icon-status-container">
                            <div class="icon_typing" id="patronymic_status_icon"></div>
                        </div></td>
                </tr>

                <!---------------- User Group input ---------------->
                <tr><th>
                        Группа пользователя: <div class="req_field">*</div>
                    </th></tr>

                <tr><td>
                        <!-- User Group input: -->
                        <input list="user_groups_list" name="group_input" id="group_input" class="askue-list-input" placeholder="Выберите группу пользователя" autocomplete="off" <?php if($edit_user && $edit_user->getGroupId()) echo 'value="'.$userGroupsList[$edit_user->getGroupId()]->getName().'"';?>>
                        <datalist id="user_groups_list" >
                            <?php foreach ($userGroupsList as $userGroup): ?>
                                <option value="<?=$userGroup->getName();?>"></option>
                            <?php endforeach; ?>
                        </datalist>

                        <!-- User Group icon: -->
                        <div class="icon-status-container">
                            <div class="icon_typing" id="group_status_icon"></div>
                        </div>

                        <!-- Error message box -->
                        <div class="inputs_error_container_horizontal">
                            <div class="inputs_error_text" id="group_error_message"></div>
                        </div>
                    </td></tr>

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
                            <input type="text" name="phone_input" id="phone_input" class="askue_input" autocomplete="off" placeholder="" <?php if($edit_user) echo 'value="'.$edit_user->getPhone().'"';?>>
                            <?php if($edit_user) { echo '<input type="hidden" id="edit_user_phone" name="edit_user_phone" value="' . $edit_user->getPhone() . '">'; } ?>
                        </div>

                    </td>
                    <!-- Phone icon: -->
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

                        <!-- Name input: -->
                        <div style="display: inline-block; width:100%; padding-right:50px;">
                            <input type="text" name="email_input" id="email_input" class="askue_input" autocomplete="off" placeholder="" <?php if($edit_user) echo 'value="'.$edit_user->getEmail().'"';?>>
                            <?php if($edit_user) { echo '<input type="hidden" id="edit_user_email" name="edit_user_email" value="' . $edit_user->getEmail() . '">'; } ?>
                        </div>

                    </td>
                    <!-- Name icon: -->
                    <td width="35" align="center"><div class="icon-status-container">
                            <div class="icon_typing" id="email_status_icon"></div>
                        </div></td>
                </tr>

                <tr><td>(<div class="req_field">*</div>) - обязательные поля</td></tr>
            </table>
        </div>

        <!---------------- Submit button ----------------->
        <table class="askue-submit-button-table" cellpadding="0" cellspacing="0">
            <tr><td align="right">
                    <input type="submit" class="askue-submit-button" <?php if($edit_user) echo 'value="Сохранить"'; else echo 'value="Добавить"'; ?>>
                </td></tr>
        </table>
    </form>

</div>
<?php else: ?>
    <div class="edit-title">Нет доступа к данной странице</div>
<?php endif; ?>