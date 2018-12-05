<?php

require_once(ASKUE_PLUGIN_DIR . "models/DataController.php");

global $wpdb;
$dataController = new DataController($wpdb);

$edit_group = null;

if(isset($_GET["edit"])) {
    $group_id = $_GET["edit"];
    $edit_group = $dataController->selectUserGroup($group_id);
}
?>

<?php wp_enqueue_script('donetype_script'); ?>
<?php wp_enqueue_script('add_user_group_ajax'); ?>


<h1>
    <?php if($edit_group) echo "АСКУЭ » Редактирование группы пользователей: '".$edit_group->getName()."'";
    else echo "АСКУЭ » Добавление группы пользователей"?>
</h1>

<div class="askue-admin-content">

    <!-- Форма добавления нового счетчика -->
    <form method="post" action="" id="ajax-add-group">
        <div class="input-form-container">
            <table class="inputs-vertical-table" cellspacing="0" cellpadding="0">

                <!---------------- Name input ---------------->
                <tr><th>
                        Название группы:
                    </th></tr>

                <tr><td>
                        <!-- Error message box -->
                        <div class="inputs_error_container_askue">
                            <div class="inputs_error_text" id="name_error_message"></div>
                        </div>

                        <!-- Name input: -->
                        <div style="display: inline-block; width:100%; padding-right:50px;">
                            <input type="text" name="group_name_input" id="group_name_input" class="askue_input" autocomplete="off" placeholder="Название группы" <?php if($edit_group) echo 'value="'.$edit_group->getName().'"';?>>
                            <?php if($edit_group) {
                                echo '<input type="hidden" id="edit_group_id" name="edit_group_id" value="' . $edit_group->getId() . '">';
                                echo '<input type="hidden" id="edit_group_name" name="edit_group_name" value="' . $edit_group->getName() . '">';
                            }
                            ?>
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
                    <input type="submit" class="askue-submit-button" <?php if($edit_group) echo 'value="Сохранить"'; else echo 'value="Добавить"'; ?>>
                </td></tr>
        </table>
    </form>

</div>
