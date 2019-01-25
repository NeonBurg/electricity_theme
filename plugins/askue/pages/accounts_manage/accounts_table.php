<?php
/*$page_num = 1;
$items_on_page = 5;

$pages = $dataController->countPages($items_on_page);

if(isset($_GET["page_number"])) {
    $page_num = $_GET["page_number"];
}
else {
    if($pages !=0) {
        $page_num = $pages;
    }
}*/

?>


<script type="text/javascript">

var pages = 0;
var current_page_num = 0;
var items_on_page = 0;

function change_page(page_num) {

    console.log('change_page: ' + page_num + 'items_on_page: ' + items_on_page);
    current_page_num = page_num;

    $form_data = {'page_num': current_page_num, 'items_on_page': items_on_page};
    $form_action = myScript.askue_plugin_url + '/askue/pages/accounts_manage/change_page.php';
    changePageAjax($form_action, $form_data);
}


// --------------- Обновляем список аккаунтов с пользователями -----------------
function update_list(updated_accounts_list) {

    //console.log(updated_accounts_list);

    $accounts_table_html = '';

    $new_pages_count = updated_accounts_list.pop();

    //console.log('update_list pages: ' + $new_pages_count);

    if(pages !== $new_pages_count) {
        pages = $new_pages_count;
        updateSelectOptions();
    }

    if(current_page_num > pages || current_page_num === 0) {
        current_page_num = pages;
    }

    //console.log('update_list pages: ' + pages);

    $('#accounts-table tr td').remove();

    var i;
    for(i=0; i<updated_accounts_list.length; i++) {

        var account = updated_accounts_list[i];
        //console.log('account['+account.id+'] login: ' + account.login);

        $group_edit_url_admin = myScript.site_url + '/wp-admin/admin.php?page=add_user&edit='+account.id;
        $group_edit_url_user_room = myScript.site_url + '/user-room/accounts_management/add-user?edit='+account.id;

        $accounts_table_html += '<tr>';

        if(account.hasOwnProperty('name') && account.hasOwnProperty('surname') && account.hasOwnProperty('patronymic')) {
            $accounts_table_html += '<td style="text-align:left; padding-left:10px;">'+account.surname+' '+account.name+' '+account.patronymic+'</td>';
        }

        if(account.hasOwnProperty('login')) {
            $accounts_table_html += '<td>'+account.login+'</td>';
        }

        if(account.hasOwnProperty('email')) {
            $accounts_table_html += '<td>'+account.email+'</td>';
        }

        if(account.hasOwnProperty('group')) {
            $accounts_table_html += '<td>'+account.group+'</td>';
        }


        $accounts_table_html +=
            '                        <?php if(ACCESS_LEVEL == 3): ?>' +
            '                        <td>' +
            '                            <div style="padding-top:6px;">' +
            '                                <div style="display: inline-block;">' +
            '                                    <?php if(is_admin()): ?>' +
            '                                        <div class="edit-button" onclick="location.href=\''+$group_edit_url_admin+'\'"></div>' +
            '                                    <?php else: ?>' +
            '                                        <div class="edit-button" onclick="location.href=\''+$group_edit_url_user_room+'\'"></div>' +
            '                                    <?php endif; ?>' +
            '                                </div>' +
            '                                <div style="display: inline-block; padding-left:5px;">' +
            '                                    <div class="delete-button" onclick="delete_user('+account.id+', \''+account.login+'\')"></div>' +
            '                                </div>' +
            '                            </div>' +
            '                        </td>' +
            '                        <?php endif; ?>' +
            '                    </tr>';

    }

    document.getElementById("accounts-table").insertAdjacentHTML('beforeend', $accounts_table_html);
    document.getElementById("page_number_select").selectedIndex = current_page_num-1;

    update_pages_row();
}

// --------------- Ajax запрос на смену страницы --------------------
function changePageAjax(action, form_data) {
    jQuery.ajax({
        type: 'POST',
        url: action,
        data: form_data
    }).done(function (response) {
        var json_response =  jQuery.parseJSON(response);
        update_list(json_response);
    }).fail(function (data) {
        console.log('change page error!');
        if (data.responseText !== '') {
            console.log('response: ' + data.responseText);
        }
    });
}


// ----------------- Обновляем блок с выбором страницы -----------------
function update_pages_row() {

    //console.log('update_pages_row');
    //var pages_row = document.getElementById("pages_row");

    $page_row_html = "";
    $page_row_html += "Страницы: ";
    $page_row_html += "<div class='pages_nums'>";

    $current_page = current_page_num;
    $start_page = current_page_num;
    $end_page = current_page_num;

    if(($current_page-1)>0)
        $start_page--;
    if(($current_page+1)<=pages)
        $end_page++;

    if(($start_page - 2)>0) {
        $page_row_html += "<a onclick='change_page(1)'>1</a> ... ";
    }
    else if(($start_page-1)>0) {
        $page_row_html += "<a onclick='change_page(1)'>1</a> ";
    }

    var i;
    for(i=$start_page; i<=$end_page; i++) {
        if(i === $current_page)
            $page_row_html += " <a onclick='change_page("+i+")' style='color:red !important;'>"+i+"</a> ";
    else
        $page_row_html += " <a onclick='change_page("+i+")'>"+i+"</a> ";
    }

    if(($end_page + 2)<=pages)
        $page_row_html += " ... <a onclick='change_page(pages)'>"+pages+"</a> ";
else if(($end_page + 1)<=pages)
        $page_row_html += "<a onclick='change_page(pages)'>"+pages+"</a> ";

    $page_row_html += "</div>";
    pages_row.innerHTML = "";
    pages_row.innerHTML = $page_row_html;
    //console.log($page_row_html);
}

function updateSelectOptions() {
    var i;
    var page_number_select = document.getElementById("page_number_select");
    for(i=0; i<pages; i++) {
        var option = document.createElement("option");
        option.text = i+1;
        page_number_select.add(option);
    }
    page_number_select.selectedIndex = current_page_num-1;
}

function initItemsOnPageSelect() {

    $items_on_page_options = [2, 5, 10, 25, 50];

    var i;
    var items_on_page_select = document.getElementById("items_on_page_select");
    for(i=0; i<$items_on_page_options.length; i++) {
        var option = document.createElement("option");
        option.text = $items_on_page_options[i];
        items_on_page_select.add(option);
    }
    items_on_page_select.selectedIndex = 1;

    items_on_page = parseInt(items_on_page_select.value);

}

function selectPageChange() {
    change_page(parseInt(document.getElementById("page_number_select").value));
}

function selectItemsOnPageChange() {
    items_on_page = parseInt(items_on_page_select.value);
    change_page(parseInt(document.getElementById("page_number_select").value));
}

jQuery(document).ready(function($) {
    initItemsOnPageSelect();

    change_page(current_page_num);
});
</script>


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

<div style="display:inline-block; margin-top:10px;">
    <div style="display:inline-block;">
        <div id="pages_row"></div>
        <select id="page_number_select" onchange="selectPageChange()"></select>
    </div>
    <div style="display:inline-block; margin-left: 30px; vertical-align: top;">
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