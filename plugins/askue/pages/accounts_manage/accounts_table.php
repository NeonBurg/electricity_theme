<script type="text/javascript">

var pages = 0;
var current_page_num = 0;
var items_on_page = 0;

// --------------- Ajax запрос на смену страницы --------------------
function changePageAjax(action, form_data) {
    jQuery.ajax({
        type: 'POST',
        url: action,
        data: form_data
    }).done(function (response) {

        var access_level = document.getElementById("access_level").value;
        update_list(response, access_level);

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

</script>

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