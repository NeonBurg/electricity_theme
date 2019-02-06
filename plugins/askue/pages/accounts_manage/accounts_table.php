
<!--------------- Search Input --------------->
<div style="display:table; width:100%; height:30px;">
    <div style="display: inline-block; height:100%; margin-right:10px;"><div style="display: inline-block; vertical-align: middle;">Поиск:</div></div>

    <div style="display: table-cell; width:100%; padding-right:10px;">
        <input type="text" id="searchInput" style="height:100%; width:100%; padding-left:5px;" placeholder="ФИО пользователя">
    </div>

    <div style="display: inline-block; position:absolute; right:25px; margin-top:3px;">
        <div class="icon_typing" id="search_status_icon"></div>
    </div>
</div>

<div id="search_error_msg" style="color:red; margin-top:10px;"></div>
<!----------------------------------------------->

<input type="hidden" id="access_level" value="<?=ACCESS_LEVEL?>">


<div class="accounts_container">
<div class="groups_content">
<?php if(!is_admin()):?>
    <div class="block_title_user_room">
<?php endif;?>
        <div class="block_title">Список пользователей</div>
 <?php if(!is_admin()):?>
    </div><div class="user_room_block_border">
<?php endif;?>

        <table class="energy-object-table" id="accounts-table" cellpadding="0" cellspacing="0">
            <tr>
                <th width="33%" style="text-align:left; padding-left:10px;">ФИО</th>
                <th>Логин</th>
                <th width="33%">Email</th>
                <th>Группа</th>
                <?php if(ACCESS_LEVEL == 3): ?>
                <th width="6%"></th>
                <?php endif; ?>
            </tr>
        </table>
</div>

<?php if(!is_admin()):?>
</div>
<?php endif;?>

<?php //include("pages_table.php"); ?>

<!----------------- Select pages ------------------->
<div class="page_coinainer">
    <div class="page_select_block">
        <div id="pages_row"></div>
        <select id="page_number_select" onchange="selectPageChange()"></select>
    </div>
    <div class="items_on_page_block">
        Записей на странице:
        <div style="display:inline-block;">
            <select id="items_on_page_select" onchange="selectItemsOnPageChange()"></select>
        </div>
    </div>
</div>

<?php if(ACCESS_LEVEL == 3): ?>
    <div style="display: inline-block; float:right;"><div class="add_group_button_wrap"><div class="askue-button" onclick="<?php if(is_admin()) echo "location.href='".site_url('/wp-admin/admin.php?page=add_user')."'"; else echo "location.href='".site_url('/user-room/accounts-management/add-user')."'"; ?>">Добавить пользователя</div></div></div>
<?php endif; ?>
</div>