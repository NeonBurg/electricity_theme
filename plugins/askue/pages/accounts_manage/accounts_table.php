<?php
    $accountsList = $dataController->selectCustomersList();
    $userGroupsList = $dataController->selectUserGroupsList();
?>

<div class="accounts_container">
    <div class="groups_content">
    <?php if(!is_admin()):?>
        <div class="block_title_user_room">
    <?php endif;?>
            <div class="block_title">Список пользователей</div>
     <?php if(!is_admin()):?>
        </div><div class="user_room_block_border">
    <?php endif;?>
        <?php if(count($accountsList) > 0): ?>

            <table class="energy-object-table" cellpadding="0" cellspacing="0">
                <tr>
                    <th width="33%" style="text-align:left; padding-left:50px;">ФИО</th>
                    <th>Логин</th>
                    <th width="33%">Email</th>
                    <th>Группа</th>
                    <?php if($access_level == 3): ?>
                    <th width="6%"></th>
                    <?php endif; ?>
                </tr>

                <?php foreach ($accountsList as $account):?>

                    <tr>
                        <td style="text-align:left; padding-left:50px;"><?php echo $account->getSurname()." ".$account->getName()." ".$account->getPatronymic(); ?></td>
                        <td><?=$account->getLogin();?></td>
                        <td><?php if($account->getEmail()) echo $account->getEmail(); else echo "null"; ?></td>
                        <td><?php if($account->getGroupId()) echo $userGroupsList[$account->getGroupId()]->getName(); else echo "null"; ?></td>
                        <?php if($access_level == 3): ?>
                        <td>
                            <div style="padding-top:6px;">
                                <div style="display: inline-block;">
                                    <?php if(is_admin()): ?>
                                        <div class="edit-button" onclick="location.href='/wp-admin/admin.php?page=add_user&edit=<?=$account->getId();?>'"></div>
                                    <?php else: ?>
                                        <div class="edit-button" onclick="location.href='/user-room/accounts_management/add-user?edit=<?=$account->getId();?>'"></div>
                                    <?php endif; ?>
                                </div>
                                <div style="display: inline-block; padding-left:5px;">
                                    <div class="delete-button" onclick="delete_user(<?=$account->getId();?>, '<?=$account->getLogin();?>')"></div>
                                </div>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>

                <?php endforeach;?>
            </table>
<?php else:?>
    Пустой список групп
<?php endif; ?>
    </div>
<?php if(!is_admin()):?>
    </div>
<?php endif;?>
    <?php if($access_level == 3): ?>
        <div style="width:100%; display: inline-block;"><div class="add_group_button_wrap"><div class="askue-button" onclick="<?php if(is_admin()) echo "location.href='/wp-admin/admin.php?page=add_user'"; else echo "location.href='/user-room/accounts-management/add-user'"; ?>">Добавить пользователя</div></div></div>
    <?php endif; ?>
</div>