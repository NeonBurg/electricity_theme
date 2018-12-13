<?php
    $accountsList = $dataController->selectCustomersList();
    $userGroupsList = $dataController->selectUserGroupsList();
?>

<script type="text/javascript">
    function delete_user(user_id, user_name) {
        if (confirm("Удалить пользователя: '" + user_name + "' ?")) {
            console.log('delete user: id = ' + user_id);
            $form_data = {'user_id': user_id};
            $form_action = '<?=plugins_url("/add_user/delete_user.php", __FILE__);?>';
            makeDeleteAjax($form_action, $form_data);
        }
        else {
            console.log('cancel delete user');
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

<div class="accounts_container">
    <div class="block_title">Список пользователей</div>
    <div class="groups_content">
        <?php if(count($accountsList) > 0): ?>

            <table class="energy-object-table" cellpadding="0" cellspacing="0">
                <tr>
                    <th width="33%" style="text-align:left; padding-left:50px;">ФИО</th>
                    <th>Логин</th>
                    <th width="33%">Email</th>
                    <th>Группа</th>
                    <th width="6%"></th>
                </tr>

                <?php foreach ($accountsList as $account):?>

                    <tr>
                        <td style="text-align:left; padding-left:50px;"><?php echo $account->getSurname()." ".$account->getName()." ".$account->getPatronymic(); ?></td>
                        <td><?=$account->getLogin();?></td>
                        <td><?php if($account->getEmail()) echo $account->getEmail(); else echo "null"; ?></td>
                        <td><?php if($account->getGroupId()) echo $userGroupsList[$account->getGroupId()]->getName(); else echo "null"; ?></td>
                        <td>
                            <div style="padding-top:6px;">
                                <div style="display: inline-block;">
                                    <div class="edit-button" onclick="location.href='/wp-admin/admin.php?page=add_user&edit=<?=$account->getId();?>'"></div>
                                </div>
                                <div style="display: inline-block; padding-left:5px;">
                                    <div class="delete-button" onclick="delete_user(<?=$account->getId();?>, '<?=$account->getLogin();?>')"></div>
                                </div>
                            </div>
                        </td>
                    </tr>

                <?php endforeach;?>
            </table>
    </div>
        <?php else:?>
            Пустой список пользователей
        <?php endif; ?>
<div style="width:100%; display: inline-block;"><div class="add_group_button_wrap"><div class="askue-button" onclick="location.href='/wp-admin/admin.php?page=add_user';">Добавить пользователя</div></div></div>
</div>
