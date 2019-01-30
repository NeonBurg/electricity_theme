var counterKeys = 0;
var last_success_search = "";
var is_error_search = false;

// ------------------------------ functions --------------------------------

function change_page(page_num) {

    console.log('change_page: ' + page_num + 'items_on_page: ' + items_on_page);
    current_page_num = page_num;

    $form_data = {};

    var search_filter = "";
    if(!is_error_search)
        search_filter = document.getElementById("searchInput").value;
    else {
        search_filter = last_success_search;
    }

    if(search_filter && search_filter.length !== 0) {
        $form_data = {'page_num': current_page_num, 'items_on_page': items_on_page, 'search_filter': search_filter};
    }
    else {
        $form_data = {'page_num': current_page_num, 'items_on_page': items_on_page};
    }
    $form_action = myScript.askue_plugin_url + '/askue/pages/accounts_manage/change_page.php';
    changePageAjax($form_action, $form_data);
}

// --------------- Обновляем список аккаунтов с пользователями -----------------
function update_list(jsonResponse, access_level) {

    console.log('update_list access_level: ' + access_level);

    try {
        var updated_accounts_list = jQuery.parseJSON(jsonResponse);

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


            if(access_level == 3) {
                $accounts_table_html +=
                    '                        <td>' +
                    '                            <div style="padding-top:6px;">' +
                    '                                <div style="display: inline-block;">';
                if(myScript.isAdmin) {
                    $accounts_table_html +='                                        <div class="edit-button" onclick="location.href=\'' + $group_edit_url_admin + '\'"></div>';
                }
                else {
                    $accounts_table_html +='                                        <div class="edit-button" onclick="location.href=\'' + $group_edit_url_user_room + '\'"></div>';
                }
                $accounts_table_html +=
                    '                                </div>' +
                    '                                <div style="display: inline-block; padding-left:5px;">' +
                    '                                    <div class="delete-button" onclick="delete_user(' + account.id + ', \'' + account.login + '\')"></div>' +
                    '                                </div>' +
                    '                            </div>' +
                    '                        </td>';
            }

            $accounts_table_html += '                    </tr>';

        }

        document.getElementById("accounts-table").insertAdjacentHTML('beforeend', $accounts_table_html);
        document.getElementById("page_number_select").selectedIndex = current_page_num-1;

        if(!is_error_search) {
            document.getElementById("search_error_msg").innerHTML = "";
            last_success_search = document.getElementById("searchInput").value;
        }
        update_pages_row();
    }
    catch (err) {
        console.log('select page error: ' + err);
        console.log('response: ' + jsonResponse);
        is_error_search = true;
        document.getElementById("search_error_msg").innerHTML = "Пользователь '"+document.getElementById("searchInput").value+"' не найден";
    }
}

function donetypingSearch() {
    console.log('donetypingSearch');
    pages = 0;
    change_page(0);
    $('#search_status_icon').css('visibility', 'hidden');
    counterKeys = 0;
}

jQuery(document).ready(function($) {

    initItemsOnPageSelect();

    change_page(current_page_num);

    var searchInput = $('#searchInput');

    $(searchInput).on('input', function() {
        console.log('searchInput onInput');
        console.log('counterKeys: ' + counterKeys);

        if(counterKeys === 0) {
            $('#search_status_icon').css('visibility', 'visible');
            is_error_search = false;
        }
        counterKeys++;
    });

    $(searchInput).donetyping(donetypingSearch);
});