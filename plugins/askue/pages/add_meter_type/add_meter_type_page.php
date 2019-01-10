<?php if(isset($access_level) && $access_level == 3 || is_admin()):?>
<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 15.05.2018
 * Time: 12:01
 */
?>

<?php wp_enqueue_script('donetype_script'); ?>
<?php wp_enqueue_script('add_meter_type_ajax'); ?>

<h1>АСКУЭ » Добавление нового типа счетчиков</h1>

<div class="askue-admin-content">

    <!-- Форма добавления нового счетчика -->
    <form method="post" action="" id="ajax-add-meter">
        <div class="input-form-container">
            <table class="inputs-vertical-table" cellspacing="0" cellpadding="0">

                <!---------------- Name input ---------------->
                <tr><th>
                        Название:
                </th></tr>

                <tr><td>
                        <!-- Error message box -->
                        <div class="inputs_error_container_askue">
                            <div class="inputs_error_text" id="name_error_message"></div>
                        </div>

                        <!-- Name input: -->
                        <div style="display: inline-block; width:100%; padding-right:50px;">
                            <input type="text" name="type_name_input" id="type_name_input" class="askue_input" autocomplete="off" placeholder="Название для типа счетчика">
                        </div>

                    </td>
                    <!-- Name icon: -->
                    <td width="35" align="center"><div class="icon-status-container">
                            <div class="icon_typing" id="name_status_icon"></div>
                        </div></td>
                </tr>

                <!---------------- Meter type input ---------------->
                <tr><th>
                        Тип счетчика:
                    </th></tr>

                <tr><td>
                        <!-- Error message box -->
                        <div class="inputs_error_container_askue">
                            <div class="inputs_error_text" id="name_error_message"></div>
                        </div>

                        <!-- Name input: -->
                        <div style="display: inline-block; width:100%; padding-right:50px;">
                            <!--<input type="text" name="type_name_input" id="type_name_input" class="askue_input" autocomplete="off" placeholder="Название счетчика">-->
                            <select id="meter_type" name="meter_type">
                                <option value="singlephase">Однофазный</option>
                                <option value="threephase">Трёхфазный</option>
                            </select>
                        </div>

                    </td>
                    <!-- Name icon: -->
                    <td width="35" align="center"><div class="icon-status-container">
                            <div class="icon_typing" id="name_status_icon"></div>
                        </div></td>
                </tr>
            </table>
        </div>

        <!---------------- Submit button ----------------->
        <table class="askue-submit-button-table" cellpadding="0" cellspacing="0">
            <tr><td align="right">
                    <input type="submit" class="askue-submit-button" value="Добавить">
                </td></tr>
        </table>
    </form>
</div>
    <?php else: ?>
    <div class="edit-title">Нет доступа к данной странице</div>
<?php endif; ?>