function delete_meter(meter_id, meter_name) {
    if (confirm("Удалить счетчик: '" + meter_name + "' ?")) {
        console.log('delete meter: id = ' + meter_id);
        $form_data = {'meter_id': meter_id};
        //$form_action = '<?=plugins_url("/remove_meter.php", __FILE__);?>';
        $form_action = myScript.askue_plugin_url + '/askue/pages/remove_meter.php';
        makeDeleteAjax($form_action, $form_data);
    }
    else {
        console.log('cancel delete meter');
    }
}

function delete_energy_object(energy_object_id, energy_object_name) {
    if (confirm("Удалить энергетический объект: '" + energy_object_name + "' ?")) {
        console.log('delete meter: id = ' + energy_object_id);
        $form_data = {'energy_object_id': energy_object_id};
        //$form_action = '<?=plugins_url("/remove_energy_object.php", __FILE__);?>';
        $form_action = myScript.askue_plugin_url + '/askue/pages/remove_energy_object.php';
        makeDeleteAjax($form_action, $form_data);
    }
    else {
        console.log('cancel delete meter');
    }
}

function delete_group(group_id, group_name) {
    if (confirm("Удалить группу: '" + group_name + "' ?")) {
        console.log('delete group: id = ' + group_id);
        $form_data = {'group_id': group_id};
        //$form_action = '<?=plugins_url("/add_group/delete_group.php", __FILE__);?>';
        $form_action = myScript.askue_plugin_url + '/askue/pages/accounts_manage/add_group/delete_group.php';
        makeDeleteAjax($form_action, $form_data);
    }
    else {
        console.log('cancel delete meter');
    }
}


function delete_user(user_id, user_name) {
    if (confirm("Удалить пользователя: '" + user_name + "' ?")) {
        console.log('delete user: id = ' + user_id);
        $form_data = {'user_id': user_id};
        //$form_action = '<?=plugins_url("/add_user/delete_user.php", __FILE__);?>';
        $form_action = myScript.askue_plugin_url + '/askue/pages/accounts_manage/add_user/delete_user.php';
        makeDeleteAjax($form_action, $form_data);
    }
    else {
        console.log('cancel delete user');
    }
}

function delete_meter_value(meter_id, value_id, value, date) {
    if (confirm("Удалить показания счетчика "+value+" (Кв/ч) на момент: '" + date + "' ?")) {

        $form_data = {'meter_id': meter_id, 'value_id': value_id};
        $form_action = myScript.askue_plugin_url + '/askue/pages/meter_details/add_meter_value/delete_meter_value.php';
        makeDeleteAjax($form_action, $form_data);
    }
    else {
        console.log('cancel delete user');
    }
}


// --------------- Ajax запрос на удаление --------------------
function makeDeleteAjax(action, form_data) {
    jQuery.ajax({
        type: 'POST',
        url: action,
        data: form_data
    }).done(function (response) {
        location.reload(); // Обновляем страницу после удаления
    }).fail(function (data) {
        console.log('delete error!');
        if (data.responseText !== '') {
            console.log('response: ' + data.responseText);
        }
    });
}