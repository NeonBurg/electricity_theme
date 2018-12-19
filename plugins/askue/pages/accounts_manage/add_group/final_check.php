<?php

require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
include(ASKUE_PLUGIN_DIR . "pages/accounts_manage/add_group/check_name.php");

global $wpdb;

if($wpdb) {

    $access_level = $_POST["access_level_select"];

    if(isset($_POST["edit_group_name"])) {
        $edit_group_id = $_POST["edit_group_id"];

        $sql_update_group = $wpdb->prepare("UPDATE UserGroups SET name=%s, access_level=%d WHERE id = %d", $group_name, $access_level, $edit_group_id);
        $wpdb->query($sql_update_group);

        $result = $wpdb->get_row($wpdb->prepare("SELECT * FROM UserGroups WHERE id = %d", $edit_group_id));
        if(strcmp($result->name, $group_name) === 0) {
            http_response_code(200);
        }
        else {
            http_response_code(400);
            echo "Ошибка сохранения данных о группе";
            exit;
        }
    }
    else {

        $sql_insert_group = $wpdb->prepare("INSERT INTO UserGroups(name, access_level) VALUES('%s', %d)", $group_name, $access_level);
        $wpdb->query($sql_insert_group);

        $result = $wpdb->get_row("SELECT id FROM Meters ORDER BY id DESC LIMIT 1");

        if ($result) {
            http_response_code(200);
        } else {
            http_response_code(400);
            echo "Ошибка mysql";
            exit;
        }
    }

}
else {
    http_response_code(400);
    echo "Отсутсвует соединение с базой данных";
    exit;
}

?>