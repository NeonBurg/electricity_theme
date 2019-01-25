<?php
if($_SERVER['CONTEXT_DOCUMENT_ROOT']) require_once( $_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/wp-load.php');
else require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

    $fields_count = $_POST["fields_count"];
    $meter_id = $_POST["meter_id"];
    $value_input_name = 'meter_value_input';
    $date_input_name = 'meter_date_input';

global $wpdb;

if($wpdb) {

    if(isset($_POST["edit_value_id"])) {

        $edit_value_id = $_POST["edit_value_id"];

        $value_input = $_POST[$value_input_name."_0"];
        $date_input = $_POST[$date_input_name."_0"];

        if (!empty($value_input) && !empty($date_input)) {

            if (strpos($value_input, ',') !== false) list($base, $decimal) = explode(',', $value_input);
            else if (strpos($value_input, '.') !== false) list($base, $decimal) = explode('.', $value_input);
            else {
                $base = $value_input;
                $decimal = 0;
            }

            //http_response_code(400);
            //echo "base = ".$base." | decim = ".$decimal;
            //exit;

            $sql_update_group = $wpdb->prepare("UPDATE meter_%d SET base=%d, decim=%s, date=%s WHERE id = %d", $meter_id, $base, $decimal, $date_input, $edit_value_id);
            $wpdb->query($sql_update_group);
            http_response_code(200);
        }
    }
    else {

        $value_input_list = array();
        $date_input_list = array();

        for ($i = 0; $i < $fields_count; $i++) {
            if (isset($_POST[$value_input_name . '_' . $i]) && isset($_POST[$date_input_name . '_' . $i])) {
                $value_input = $_POST[$value_input_name . '_' . $i];
                $date_input = $_POST[$date_input_name . '_' . $i];

                if (!empty($value_input) && !empty($date_input)) {

                    $value_input_list[] = $value_input;
                    $date_input_list[] = $date_input;

                } else {
                    http_response_code(400);
                    if (empty($value_input))
                        echo "Ошибка: пустое поле с показателями счетчика";
                    else if (empty($date_input)) {
                        echo "Ошибка: пустое поле с датой показаний";
                    }
                    //echo "Empty dataset";
                    exit;
                }
            } else {
                //http_response_code(400);
                //echo "Ошибка: пустое значение полей";
                //echo "Data not set: value_input_name = ".$value_input_name.'_'.$i." | date_input = ">$date_input_name.'_'.$i;
                //exit;
            }
        }

        for ($i = 0; $i < count($value_input_list); $i++) {

            if (strpos($value_input, ',') !== false) list($base, $decimal) = explode(',', $value_input_list[$i]);
            else if (strpos($value_input, '.') !== false) list($base, $decimal) = explode('.', $value_input_list[$i]);
            else {
                $base = $value_input_list[$i];
                $decimal = 0;
            }

            //http_response_code(400);
            //echo "[".$i."]: base = ".$base." | decimal = ".$decimal. "strpos(value_input, ',') = " . strpos($value_input, ',') . " | value_input = ".$value_input;
            //exit;

            $sql = $wpdb->prepare("INSERT INTO meter_%d (base, decim, date) VALUES (%d, %s, %s)", $meter_id, $base, $decimal, $date_input_list[$i]);
            $wpdb->query($sql);
        }
    }

}
else {
    http_response_code(400);
    echo "Отсутсвует соединение с базой данных";
    exit;
}

?>