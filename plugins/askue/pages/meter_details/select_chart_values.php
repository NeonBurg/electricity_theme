<?php
    require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
    require_once(ASKUE_PLUGIN_DIR . "models/DataController.php");

    global $wpdb;

    if(isset($_POST["interval"]) && isset($_POST["from_date"]) && isset($_POST["to_date"]) && isset($_POST["meter_id"])) {
        $interval = $_POST["interval"];
        $from_date = $_POST["from_date"];
        $to_date = $_POST["to_date"];
        $meter_id = $_POST["meter_id"];

        if(!empty($interval) && !empty($from_date) && !empty($to_date) && !empty($meter_id)) {
            if($wpdb) {
                $dataController = new DataController($wpdb);
                $meter_values_list = $dataController->selectFilteredMeterValues($interval, new DateTime($from_date), new DateTime($to_date), $meter_id);
                //http_response_code(200);
                //echo "Hello response";
                if(count($meter_values_list) > 0)
                    echo json_encode($meter_values_list);
                else
                    echo json_encode("[]");
            }
            else {
                http_response_code(400);
                echo "Отсутсвует соединение с базой данных";
                exit;
            }
        }
    }

?>