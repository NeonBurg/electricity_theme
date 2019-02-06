// ------------------------------ functions --------------------------------

var is_up_sort = false;

function change_page(page_num) {

    console.log('change_page: ' + page_num + ' | items_on_page: ' + items_on_page + ' | is_up_sort: ' + is_up_sort);
    current_page_num = page_num;
    var meter_id = document.getElementById("meter_id").value;

    $form_data = {'meter_id': meter_id, 'page_num': current_page_num, 'items_on_page': items_on_page, 'is_up_sort': is_up_sort};

    $form_action = myScript.askue_plugin_url + '/askue/pages/meter_details/change_page.php';
    changePageAjax($form_action, $form_data);
}

// --------------- Обновляем список аккаунтов с пользователями -----------------
function update_list(jsonResponse, access_level) {

    console.log('update_list access_level: ' + access_level);

    try {
        var updated_meters_list = jQuery.parseJSON(jsonResponse);

        $accounts_table_html = '';

        $new_pages_count = updated_meters_list.pop();

        //console.log('update_list pages: ' + $new_pages_count);

        if(pages !== $new_pages_count) {
            pages = $new_pages_count;
            updateSelectOptions();
        }

        if(current_page_num > pages || current_page_num === 0) {
            current_page_num = pages;
        }

        //console.log('update_list pages: ' + pages);

        $('#meter-values-table tr td').remove();

        var i;
        for(i=0; i<updated_meters_list.length; i++) {

            var meter_value = updated_meters_list[i];
            //console.log('meter_value['+meter_value.id+'] login: ' + meter_value.login);

            $group_edit_url_admin = myScript.site_url + '/wp-admin/admin.php?page=add_meter_value&edit='+meter_value.id;
            $group_edit_url_user_room = myScript.site_url + '/user-room/meters-management/meter-details/add-meter-value?edit='+meter_value.id;

            $accounts_table_html += '<tr>';

            if(meter_value.hasOwnProperty('value')) {
                $accounts_table_html += '<td style="text-align:left; padding-left:10px;">'+meter_value.value+'</td>';
            }

            if(meter_value.hasOwnProperty('date')) {
                $accounts_table_html += '<td>'+meter_value.date+'</td>';
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
                    '                                    <div class="delete-button" onclick="delete_user(' + meter_value.id + ', \'' + meter_value.login + '\')"></div>' +
                    '                                </div>' +
                    '                            </div>' +
                    '                        </td>';
            }

            $accounts_table_html += '                    </tr>';

        }

        document.getElementById("meter-values-table").insertAdjacentHTML('beforeend', $accounts_table_html);
        document.getElementById("page_number_select").selectedIndex = current_page_num-1;

        update_pages_row();
    }
    catch (err) {
        console.log('select page error: ' + err);
        console.log('response: ' + jsonResponse);
        is_error_search = true;
        document.getElementById("search_error_msg").innerHTML = "Ошибка: " + err;
    }
}


function sort_clicked() {

    is_up_sort = !is_up_sort;
    var sort_icon = document.getElementById("sort_icon");

    change_page(current_page_num);

    if(is_up_sort) {
        sort_icon.classList.remove("sort_down_icon");
        sort_icon.classList.add("sort_up_icon");
    }
    else {
        sort_icon.classList.remove("sort_up_icon");
        sort_icon.classList.add("sort_down_icon");
    }
}


jQuery(document).ready(function($) {

    initItemsOnPageSelect();

    change_page(current_page_num);
});