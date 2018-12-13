<?php
    $userGroupsList = $dataController->selectUserGroupsList();
?>

<script type="text/javascript">
    function delete_group(group_id, group_name) {
        if (confirm("Удалить группу: '" + group_name + "' ?")) {
            console.log('delete group: id = ' + group_id);
            $form_data = {'group_id': group_id};
            $form_action = '<?=plugins_url("/add_group/delete_group.php", __FILE__);?>';
            makeDeleteAjax($form_action, $form_data);
        }
        else {
            console.log('cancel delete meter');
        }
    }

    function makeDeleteAjax(action, form_data) {
        jQuery.ajax({
            type: 'POST',
            url: action,
            data: form_data
        }).done(function (response) {
            //console.log(response);
            location.reload(); // Обновляем страницу после удаления
        }).fail(function (data) {
            console.log('delete error!');
            if (data.responseText !== '') {
                console.log('response: ' + data.responseText);
            }
        });
    }
</script>

<div class="groups_container">
    <div class="block_title">Группы пользователей</div>
    <div class="groups_content">
        <?php if(count($userGroupsList) > 0): ?>
            <div class="groups_table">
                <?php foreach ($userGroupsList as $userGroup):?>
                    <div class="group_row">
                        <?php echo $userGroup->getName(); ?>

                        <div style="display:inline-block; float:right;">
                            <div style="display: inline-block;">
                                <div class="edit-button" onclick="location.href='/wp-admin/admin.php?page=add_user_group&edit=<?=$userGroup->getId();?>'"></div>
                            </div>
                            <div style="display: inline-block; padding-left:5px;">
                                <div class="delete-button" onclick="delete_group(<?=$userGroup->getId();?>, '<?=$userGroup->getName();?>')"></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach;?>
            </div>
        <?php else:?>
            Пустой список групп
        <?php endif; ?>
    </div>
    <div style="width:100%; display: inline-block;"><div class="add_group_button_wrap"><div class="askue-button" onclick="location.href='/wp-admin/admin.php?page=add_user_group';">Добавить группу</div></div></div>
</div>
