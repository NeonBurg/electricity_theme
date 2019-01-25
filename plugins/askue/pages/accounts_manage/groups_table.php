<?php
    $userGroupsList = $dataController->selectUserGroupsList();
?>

<div class="groups_container">
    <div class="groups_content">
    <?php if(!is_admin()):?>
        <div class="block_title_user_room">
    <?php endif;?>
            <div class="block_title">
                Группы пользователей
            </div>
    <?php if(!is_admin()):?>
        </div><div class="user_room_block_border">
    <?php endif;?>
        <?php if(count($userGroupsList) > 0): ?>
            <div class="groups_table">
                <?php foreach ($userGroupsList as $userGroup):?>

                    <?php
                        $group_edit_url_admin = site_url('/wp-admin/admin.php?page=add_user_group&edit='.$userGroup->getId());
                        $group_edit_url_user_room = site_url('/user-room/accounts-management/add-group?edit='.$userGroup->getId());
                    ?>

                    <div class="group_row">
                        <?php echo $userGroup->getName(); ?>

                        <?php if(ACCESS_LEVEL == 3): ?>
                        <div style="display:inline-block; float:right;">
                            <div style="display: inline-block;">
                                <?php if(is_admin()):?>
                                    <div class="edit-button" onclick="location.href='<?=$group_edit_url_admin?>'"></div>
                                <?php else: ?>
                                    <div class="edit-button" onclick="location.href='<?=$group_edit_url_user_room?>'"></div>
                                <?php endif;?>
                            </div>
                            <div style="display: inline-block; padding-left:5px;">
                                <div class="delete-button" onclick="delete_group(<?=$userGroup->getId();?>, '<?=$userGroup->getName();?>')"></div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach;?>
            </div>
        <?php else:?>
            Пустой список групп
        <?php endif; ?>
    </div>
    <?php if(!is_admin()):?>
        </div>
     <?php endif;?>
    <?php if(ACCESS_LEVEL == 3): ?>
        <div style="width:100%; display: inline-block;"><div class="add_group_button_wrap"><div class="askue-button" onclick="<?php if(is_admin()) echo "location.href='".site_url('/wp-admin/admin.php?page=add_user_group')."'"; else echo "location.href='".site_url('/user-room/accounts-management/add-group')."'"; ?>">Добавить группу</div></div></div>
    <?php endif; ?>
</div>
