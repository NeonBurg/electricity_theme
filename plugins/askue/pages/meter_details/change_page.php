<?php
if($_SERVER['CONTEXT_DOCUMENT_ROOT']) require_once( $_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/wp-load.php');
else require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

require_once(ASKUE_PLUGIN_DIR . "models/DataController.php");

global $wpdb;
$accounts_list = array();

if($wpdb) {
    if(isset($_POST["meter_id"]) && isset($_POST["page_num"]) && isset($_POST["items_on_page"]) && isset($_POST["is_up_sort"])) {

        $page_num = $_POST["page_num"];
        $items_on_page = $_POST["items_on_page"];
        $meter_id = $_POST["meter_id"];
        $is_up_sort = $_POST["is_up_sort"];

        $dataController = new DataController($wpdb);
        $meter = $dataController->selectMeter($meter_id);

        $count_pages = $dataController->countMeterValuesPages($meter->getNum(), $items_on_page);
        if($page_num > $count_pages || $page_num == 0) {
            $page_num = $count_pages;
        }

        $meter_values_list = $dataController->selectMeterValuesList($meter->getNum(), $page_num, $items_on_page, $is_up_sort);
        $json_meter_values_list = array();


        foreach($meter_values_list as $meter_value) {
            $json_meter_values_list[] = array(  "id" => $meter_value->getId(),
                "value" => $meter_value->getValue(),
                "date" => $meter_value->getFormattedDate());
        }

        $json_meter_values_list[] = $count_pages;

        echo json_encode($json_meter_values_list);
    }
    else {
        http_response_code(400);
        echo "Пустая переменная page_num или items_on_page";
        exit;
    }
}
else {
    http_response_code(400);
    echo "Отсутсвует соединение с базой данных";
    exit;
}

?>