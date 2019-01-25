<?php
if($_SERVER['CONTEXT_DOCUMENT_ROOT']) require_once( $_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/wp-load.php');
else require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

require_once(ASKUE_PLUGIN_DIR . "models/DataController.php");

    global $wpdb;
    $accounts_list = array();

    if($wpdb) {
        if(isset($_POST["page_num"]) && isset($_POST["items_on_page"])) {

            $page_num = $_POST["page_num"];
            $items_on_page = $_POST["items_on_page"];

            $dataController = new DataController($wpdb);

            $count_pages = $dataController->countPages($items_on_page);
            if($page_num > $count_pages || $page_num == 0) {
                $page_num = $count_pages;
            }

            $accounts_list = $dataController->selectCustomersList($page_num, $items_on_page);
            $json_accounts_list;


            foreach($accounts_list as $account) {
                $json_accounts_list[] = array(  "id" => $account->getId(),
                                                "login" => $account->getLogin(),
                                                "name" => $account->getName(),
                                                "surname" => $account->getSurname(),
                                                "patronymic" => $account->getPatronymic(),
                                                "email" => $account->getEmail(),
                                                "group" => $dataController->selectUserGroup($account->getGroupId())->getName());
            }

            $json_accounts_list[] = $count_pages;

            echo json_encode($json_accounts_list);
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