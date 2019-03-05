<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 19.04.2018
 * Time: 18:36
 */

require_once ("MeterType.php");
require_once ("Meter.php");
require_once ("MeterValue.php");
require_once ("EnergyObject.php");
require_once ("Customer.php");
require_once ("UserGroup.php");

class DataController
{
    private $err;
    private $wpdb;

    const MINUTES_30 = 1,
        HOURS_1 = 2,
        HOURS_2 = 3,
        DAY = 4,
        WEEK = 5,
        MONTH = 6,
        MINUTES_5 = 7,
        MINUTES_15 = 8;

    public function __construct($wpdb) {
        $this->wpdb = $wpdb;
    }

    /* --------------- ГЕТТЕРЫ ---------------- */

    public function getErrorMsg()
    {
        return $this->err;
    }

    public function get_resultsSQL($query) {
        if($this->wpdb) {
            $results = $this->wpdb->get_results($query);
            if($results) {
                return $results;
            }
            else {
                $err[] = "Пустой result";
            }
        }
        else {
            $err[] = "Отсутсвует соединение с базой данных!";
        }
        return null;
    }

    public function get_rowSQL($query) {
        if($this->wpdb) {
            $results = $this->wpdb->get_row($query);
            if($results) {
                return $results;
            }
            else {
                $err[] = "Пустой result";
            }
        }
        else {
            $err[] = "Отсутсвует соединение с базой данных!";
        }
        return null;
    }

    public function get_varSQL($query) {
        if($this->wpdb) {
            $results = $this->wpdb->get_var($query);
            if($results) {
                return $results;
            }
            else {
                $err[] = "Пустой result";
            }
        }
        else {
            $err[] = "Отсутсвует соединение с базой данных!";
        }
        return null;
    }

    public function querySQL($query) {
        if($this->wpdb) {
            $results = $this->wpdb->query($query);
            if($results) {
                return $results;
            }
            else {
                $err[] = "Пустой result";
            }
        }
        else {
            $err[] = "Отсутсвует соединение с базой данных!";
        }
        return null;
    }

    // ---------- Получим список с типами счетчиков -----------
    public function selectMeterTypes() {

        $types_array = array();

        $results = $this->get_resultsSQL("SELECT * FROM MeterTypes");

        if($results != null) {
            foreach ($results as $result_row) {
                $meterTypeModel = new MeterType($result_row);
                $types_array[] = $meterTypeModel;
            }
        }

        return $types_array;
    }

    // --------- Получим информацию о типе счетчика ------------
    public function selectMeterType($meterType_id) {

        $meterType = null;

        $select_query = $this->wpdb->prepare("SELECT * FROM MeterTypes WHERE id = %d", $meterType_id);
        $results = $this->get_rowSQL($select_query);
        if($results) $meterType = new MeterType($results);

        return $meterType;
    }


    // ----------- Получим список с энергетическими объектами -----------
    public function selectEnergyObjects($energyObject_id = -1) {

        $energy_objects_list = array();

        if($energyObject_id == -1) {
            $results = $this->get_resultsSQL("SELECT * FROM EnergyObjects");
        }
        else {
            $results = $this->get_resultsSQL($this->wpdb->prepare("SELECT * FROM EnergyObjects WHERE id != %d", $energyObject_id));
        }

        if($results != null) {
            foreach ($results as $result_row) {
                $energyObject = new EnergyObject($result_row);

                $meters_result = $this->selectMetersList($energyObject->getId());
                $energyObject->setMetersList($meters_result);

                /*if($energyObject_id != -1 && $energyObject->getEnergyObjectId() != $energyObject_id) {
                    $energy_objects_list[] = $energyObject;
                }
                else if($energyObject_id == -1) {
                    $energy_objects_list[] = $energyObject;
                }*/
                $energy_objects_list[] = $energyObject;
            }
        }

        return $energy_objects_list;

    }

    // ------------ Получим информацию о энергетическом объекте -------------
    public function selectEnergyObject($energyObject_Id) {

        $energyObject = null;

        $select_query = $this->wpdb->prepare("SELECT * FROM EnergyObjects WHERE id = %d", $energyObject_Id);
        $results = $this->get_rowSQL($select_query);
        if($results) $energyObject = new EnergyObject($results);

        return $energyObject;
    }

    // ------- Получим список с энергетическими объектами для пользователя --------
    public function selectEnergyObjectsForAccount($account_id) {
        $energy_objects_list = array();

        $customer_id = $this->get_varSQL($this->wpdb->prepare("SELECT id FROM Customers WHERE account_id = %d", $account_id));
        if(!empty($customer_id)) {
            $results = $this->get_resultsSQL($this->wpdb->prepare("SELECT * FROM EnergyObjects WHERE customer_id = %d", $customer_id));
            if($results != null) {
                foreach ($results as $result_row) {
                    $energyObject = new EnergyObject($result_row);

                    $meters_result = $this->selectMetersList($energyObject->getId());
                    $energyObject->setMetersList($meters_result);
                    $energy_objects_list[] = $energyObject;
                }
            }
        }

        return $energy_objects_list;
    }


    // ----------- Получим список с счетчиками ------------
    public function selectMetersList($energyObject_id=-1) {

        $meters_list = array();

        if($energyObject_id == -1) {
            $select_query = "SELECT * FROM Meters";
        }
        else {
            $select_query = $this->wpdb->prepare("SELECT * FROM Meters WHERE energyObject_id = %d", $energyObject_id);
        }

        $results = $this->get_resultsSQL($select_query);

        if($results != null) {
            foreach ($results as $result_row) {
                $meter = new Meter($result_row);
                //$values_result = $this->selectMeterValuesList($meter->getId());
               // $meter->setMeterValues($values_result);
                $meters_list[] = $meter;
            }
        }

        return $meters_list;

    }

    // ------------ Получим список пустых счетчиков -------------
    public function selectEmptyMetersList() {

        $meters_list = array();
        $select_query = "SELECT * FROM Meters WHERE energyObject_id IS NULL";

        $results = $this->get_resultsSQL($select_query);

        if($results != null) {
            foreach ($results as $result_row) {
                $meter = new Meter($result_row);
                $meters_list[] = $meter;
            }
        }

        return $meters_list;
    }

    // ------------ Получим информацию о счетчике ---------------
    public function selectMeter($meter_id) {

        $meter = null;

        $select_query = $this->wpdb->prepare("SELECT * FROM Meters WHERE id = %d", $meter_id);
        $results = $this->get_rowSQL($select_query);
        if($results) {
            $meter = new Meter($results);
            //$values_result = $this->selectMeterValuesList($meter->getId());
            //$meter->setMeterValues($values_result);
        }

        return $meter;

    }


    // ----------- Получим список с данными о клиентах ------------
    public function selectCustomersList($page_number=-1, $items_on_page=-1, $search_filter=null) {

        $customers_list = array();
        $offset = 0;

        if($page_number!=-1) {
            $offset = $items_on_page * ($page_number - 1);
            if($search_filter!==null) {
                $search_filter = "%".$search_filter."%";
                $select_query = $this->wpdb->prepare("SELECT Customers.*, user_room_accounts.login FROM Customers INNER JOIN user_room_accounts ON Customers.account_id = user_room_accounts.id WHERE name LIKE '%s' OR surname LIKE '%s' OR patronymic LIKE '%s' LIMIT %d, %d", $search_filter, $search_filter, $search_filter, $offset, $items_on_page);
            }
            else {
                $select_query = $this->wpdb->prepare("SELECT Customers.*, user_room_accounts.login FROM Customers INNER JOIN user_room_accounts ON Customers.account_id = user_room_accounts.id LIMIT %d, %d", $offset, $items_on_page);
            }
        }
        else {
            $select_query = "SELECT Customers.*, user_room_accounts.login FROM Customers INNER JOIN user_room_accounts ON Customers.account_id = user_room_accounts.id";
        }

        $results = $this->get_resultsSQL($select_query);

        if($results != null) {
            foreach ($results as $result_row) {
                $customer = new Customer($result_row);
                $customers_list[] = $customer;
            }
        }

        return $customers_list;
    }

    // ---------- Получим информацию о пользователе --------------
    public function selectCustomer($customerId) {

        $customer = null;

        $select_query = $this->wpdb->prepare("SELECT Customers.*, user_room_accounts.login FROM Customers INNER JOIN user_room_accounts ON Customers.account_id = user_room_accounts.id WHERE Customers.id = %d", $customerId);
        $results = $this->get_rowSQL($select_query);
        if($results) $customer = new Customer($results);

        return $customer;
    }

    // ---------- Получим информацию о пользователе --------------
    public function selectCustomerByAccountId($account_id) {

        $customer = null;

        $select_query = $this->wpdb->prepare("SELECT Customers.*, user_room_accounts.login FROM Customers INNER JOIN user_room_accounts ON Customers.account_id = user_room_accounts.id WHERE user_room_accounts.id = %d", $account_id);
        $results = $this->get_rowSQL($select_query);
        if($results) $customer = new Customer($results);

        return $customer;
    }

    // ---------- Получим группы пользователей --------------
    public function selectUserGroupsList() {
        $groups_list = array();

        $select_query = "SELECT * FROM UserGroups";
        $results = $this->get_resultsSQL($select_query);

        if($results != null) {
            foreach($results as $result_row) {
                $userGroup = new UserGroup($result_row);
                $groups_list[$userGroup->getId()] = $userGroup;
            }
        }

        return $groups_list;
    }

    // -------------- Получим гуппу по ID -----------------
    public function selectUserGroup($group_id) {

        $user_group = null;

        $select_query = $this->wpdb->prepare("SELECT * FROM UserGroups WHERE id = %d", $group_id);
        $results = $this->get_rowSQL($select_query);
        if($results) $user_group = new UserGroup($results);

        return $user_group;
    }

    // -------------- Получим список вложенных энергетичечких объектов по ID объекта -----------------
    public function selectNestedEnergyObjects($energy_object_id, $user_id=-1) {

        if($user_id==-1) {
            $results = $this->get_resultsSQL($this->wpdb->prepare("SELECT * FROM EnergyObjects WHERE energyObject_id = %d", $energy_object_id));
        }
        else {
            $results = $this->get_resultsSQL($this->wpdb->prepare("SELECT * FROM EnergyObjects WHERE energyObject_id = %d AND customer_id = %s", $energy_object_id, $user_id));
        }

        $energy_objects_list = array();

        if($results != null) {
            foreach($results as $result_row) {
                $energyObject = new EnergyObject($result_row);
                $energy_objects_list[$energyObject->getId()] = $energyObject;
            }
        }

        return $energy_objects_list;
    }

    // -------------- Получим корневые энергетические объекты -----------------
    public function selectRootEnergyObjects($user_id=-1) {
        $energy_objects_list = array();

        if($user_id == -1) {
            $results = $this->get_resultsSQL("SELECT * FROM EnergyObjects WHERE energyObject_id IS NULL OR energyObject_id = 0");
        }
        else {
            $results = $this->get_resultsSQL($this->wpdb->prepare("SELECT * FROM EnergyObjects WHERE energyObject_id IS NULL AND customer_id = %d", $user_id));
        }

        if($results != null) {
            foreach ($results as $result_row) {
                $energyObject = new EnergyObject($result_row);

                $meters_result = $this->selectMetersList($energyObject->getId());
                $energyObject->setMetersList($meters_result);
                $energy_objects_list[] = $energyObject;
            }
        }

        return $energy_objects_list;
    }

    // -------------- Получим все показания для счетчика -----------------
    public function selectMeterValuesList($meter_num, $page_number=-1, $items_on_page=-1, $is_up_sort=false) {
        $meter_values_list = array();

        $query = "";

        $sort_order = "ASC";
        if($is_up_sort == 'true') $sort_order = "DESC";

        if($page_number!=-1) {
            $offset = $items_on_page * ($page_number - 1);
            $query = $this->wpdb->prepare("SELECT * FROM meter_%d ORDER BY date ".$sort_order." LIMIT %d, %d", $meter_num, $offset, $items_on_page);
        }
        else {
            $query = $this->wpdb->prepare("SELECT * FROM meter_%d ORDER BY date ".$sort_order, $meter_num);
        }

        $results = $this->get_resultsSQL($query);
        if($results != null) {
            foreach($results as $result_row) {
                $meterValue = new MeterValue($result_row);
                $meter_values_list[] = $meterValue;
            }
        }

        return $meter_values_list;
    }

    // -------------- Получим отдельное показание для счетчика -----------------
    public function selectMeterValue($meter_num, $value_id) {
        $meter_value = null;

        $select_query = $this->wpdb->prepare("SELECT * FROM meter_%d WHERE id = %d", $meter_num, $value_id);
        $results = $this->get_rowSQL($select_query);
        if($results) $meter_value = new MeterValue($results);

        return $meter_value;
    }

    // -------------- Получим выборку показаний счетчика по дате и интервалу -----------------
    public function selectFilteredMeterValues($interval, $from_date, $to_date, $meter_num) {

        $meter_values_list = array();

        $iterator_date = new DateTime(date_format($from_date, 'd.m.Y H:i'));

        $next_date = new DateTime(date_format($iterator_date, 'd.m.Y H:i'));

        $date_format_style = '';
        $statement_sign = '';
        $modify_date_expr = "+1 day";

        switch ($interval) {
            case self::MINUTES_5:
                $modify_date_expr = "+5 minutes";
                //$next_date->modify("+5 minutes");
                $date_format_style = 'd.m H:i';
                $statement_sign = '<=';
                break;
            case self::MINUTES_15:
                $modify_date_expr = "+15 minutes";
                //$next_date->modify("+15 minutes");
                $date_format_style = 'd.m H:i';
                $statement_sign = '<=';
                break;
            case self::MINUTES_30:
                $modify_date_expr = "+30 minutes";
                //$next_date->modify("+30 minutes");
                $date_format_style = 'd.m H:i';
                $statement_sign = '<=';
                break;
            case self::HOURS_1:
                $modify_date_expr = "+60 minutes";
                //$next_date->modify("+60 minutes");
                $date_format_style = 'd.m H:i';
                $statement_sign = '<=';
                break;
            case self::DAY:
                $modify_date_expr = "+1 day";
                //$next_date->modify("+1 day");
                $date_format_style = 'd.m';
                $statement_sign = '<';
                break;
            case self::WEEK:
                $modify_date_expr = "+7 day";
                //$next_date->modify("+7 day");
                $date_format_style = 'd.m';
                $statement_sign = '<';
                break;
            case self::MONTH:
                $modify_date_expr = "+1 month";
                //$next_date->modify("+1 month");
                $date_format_style = 'd.m.y';
                $statement_sign = '<';
                break;
            default:
                $modify_date_expr = "+1 day";
                //$next_date->modify("+1 day");
                $date_format_style = 'd.m';
                break;
        }

        $next_date->modify($modify_date_expr);

        //while($iterator_date < $to_date) {
        while($iterator_date < $to_date) {

            //$meter_values_list[] = date_format($iterator_date, 'Y-m-d H:i') . " - " . date_format($next_date, 'Y-m-d H:i');
            $select_query = $this->wpdb->prepare("SELECT * FROM meter_%d WHERE date = ( SELECT MAX(date) as date FROM meter_%d WHERE date >= '%s' AND date ".$statement_sign." '%s')", $meter_num, $meter_num, date_format($iterator_date, 'Y-m-d H:i'), date_format($next_date, 'Y-m-d H:i'));
            $row_sql = $this->get_rowSQL($select_query);

            if($row_sql) {
                //echo "[iterator_date = " . date_format($iterator_date, 'Y-m-d H:i');
                //echo "next_date = " . date_format($next_date, 'Y-m-d H:i')."]";

                $meter_value = new MeterValue($row_sql);
                $meter_values_list[] = [date_format($iterator_date, $date_format_style), $meter_value->getValue()];
                //$last_date = new DateTime(date_format($next_date, 'Y-m-d H:i'));
            }
            else {
                $meter_values_list[] = [date_format($iterator_date, $date_format_style), 0];
            }

            $iterator_date->modify($modify_date_expr);
            $next_date->modify($modify_date_expr);

            //echo "iterator_date = " . date_format($iterator_date, 'd.m.Y H:i');
        }

        //echo "date_to = " . date_format($to_date, 'd.m.Y H:i');

        $meter_values_list[] = [date_format($to_date, $date_format_style), 0];

        return $meter_values_list;
    }

    public function selectMeterLastValue($meter_num) {

        $meter_last_value = null;
        $select_query = $this->wpdb->prepare("SELECT * FROM meter_%d WHERE date = (SELECT MAX(date) as date FROM meter_%d)", $meter_num, $meter_num);
        $results = $this->get_rowSQL($select_query);
        if($results) $meter_last_value = new MeterValue($results);

        return $meter_last_value;
    }

    public function selectMeterFirstValue($meter_num) {

        $meter_last_value = null;
        $select_query = $this->wpdb->prepare("SELECT * FROM meter_%d WHERE date = (SELECT MIN(date) as date FROM meter_%d)", $meter_num, $meter_num);
        $results = $this->get_rowSQL($select_query);
        if($results) $meter_last_value = new MeterValue($results);

        return $meter_last_value;
    }

    public function selectEnergyObjectValue($energyObject, $user_id=-1) {

        $meters_values_list = array();
        $energyObjectValue = 0;

        $meters_values_list = array_merge($meters_values_list, $this->selectEnergyObjectMeterValues($energyObject->getId(), $energyObject->getMeterId()));

        if($user_id==-1) {
            $results = $this->get_resultsSQL($this->wpdb->prepare("SELECT id, meter_id FROM EnergyObjects WHERE energyObject_id = %d", $energyObject->getId()));
        }
        else {
            $results = $this->get_resultsSQL($this->wpdb->prepare("SELECT id, meter_id FROM EnergyObjects WHERE energyObject_id = %d AND customer_id = %d", $energyObject->getId(), $user_id));
        }

        if($results) {
            foreach($results as $result) {
                $energyObjectValue += $this->selectEnergyObjectValue($this->selectEnergyObject($result->id));
            }
        }

        /*while($results) {

            foreach($results as $result_row) {
                $meters_values_list = array_merge($meters_values_list, $this->selectEnergyObjectMeterValues($result_row->id, $result_row->meter_id));
            }

            if($user_id==-1) {
                $results = $this->get_resultsSQL($this->wpdb->prepare("SELECT id, meter_id FROM EnergyObjects WHERE energyObject_id = %d", $result_row->id));
            }
            else {
                $results = $this->get_resultsSQL($this->wpdb->prepare("SELECT id, meter_id FROM EnergyObjects WHERE energyObject_id = %d AND customer_id = %d", $result_row->id, $user_id));
            }
        }*/

        foreach($meters_values_list as $meter_value) {
            if($meter_value != null) $energyObjectValue += $meter_value->getValue();
        }

        return $energyObjectValue;
    }

    public function selectEnergyObjectMeterValues($energyObject_id, $energyObjectMeter_id=null) {

        $energy_object_meter_values = array();
        $query_sql = '';
        if($energyObjectMeter_id == null) {
            $query_sql = $this->wpdb->prepare("SELECT id, num FROM Meters WHERE energyObject_id = %d", $energyObject_id);
        }
        else {
            //$query_sql = $this->wpdb->prepare("SELECT id FROM Meters WHERE Meters.energyObject_id = %d AND Meters.id != %d", $energyObject_id, $energyObjectMeter_id);
            $query_sql = $this->wpdb->prepare("SELECT id, num FROM Meters WHERE Meters.id = %d", $energyObjectMeter_id);
        }
        $meter_results = $this->get_resultsSQL($query_sql);
        if($meter_results) {
            foreach($meter_results as $meter_row) {
                $meter_value = $this->selectMeterLastValue($meter_row->num);
                $energy_object_meter_values[] = $meter_value;
            }
        }

        return $energy_object_meter_values;
    }

    public function selectCurrentElectricityValue($user_id=-1) {

        $currentValue =  0;

        if($user_id == -1) {
            //$energyObjectsResult = $this->get_resultsSQL("SELECT * FROM EnergyObjects");
            $energyObjectsResult = $this->selectRootEnergyObjects();
        }
        else {
            //$energyObjectsResult = $this->get_resultsSQL($this->wpdb->prepare("SELECT * FROM EnergyObjects WHERE customer_id = %d", $user_id));
            //$energyObjectsList = $this->selectNestedEnergyObjects($energyObject_id);
            //$currentValue = $this->selectEnergyObjectValue();
            $energyObjectsResult = $this->selectRootEnergyObjects($user_id);
        }

        if($energyObjectsResult) {
            foreach($energyObjectsResult as $energyObjectRow) {
                $currentValue += $this->selectEnergyObjectValue($energyObjectRow, $user_id);
            }
        }

        return $currentValue;
    }

    public function countAccountsPages($items_on_page, $search_filter=null) {

        $pages = 0;

        $query = "";

        if($search_filter !== null) {
            $search_filter = "%".$search_filter."%";
            $query = $this->wpdb->prepare("SELECT COUNT(*) as count FROM Customers WHERE name LIKE '%s' OR surname LIKE '%s' OR patronymic LIKE '%s'", $search_filter, $search_filter, $search_filter);
        }
        else {
            $query = "SELECT COUNT(*) as count FROM user_room_accounts";
        }

        $count_items = $this->get_varSQL($query);
        if(!empty($count_items)) {
            $pages = ceil($count_items/$items_on_page);
        }

        return $pages;
    }

    public function countMeterValuesPages($meter_num, $items_on_page) {

        $meterValues = 0;

        $query = $this->wpdb->prepare("SELECT COUNT(*) as count FROM meter_%d",$meter_num);

        $count_items = $this->get_varSQL($query);
        if(!empty($count_items)) {
            $meterValues = ceil($count_items/$items_on_page);
        }

        return $meterValues;

    }

    public function countEnergyObjectMeters($energyObject_id) {
        $count_elements = 0;
        $child_meters = $this->get_varSQL($this->wpdb->prepare("SELECT COUNT(*) as meters_count FROM Meters WHERE energyObject_id = %d", $energyObject_id));
        if($child_meters) {
            $count_elements += $child_meters;
        }
        return $count_elements;
    }

    public function countEnergyObjectChildElements($energyObject_id) {

        $count_elements = 0;
        $child_meters = $this->get_varSQL($this->wpdb->prepare("SELECT COUNT(*) as meters_count FROM Meters WHERE energyObject_id = %d", $energyObject_id));
        $child_objects = $this->get_varSQL($this->wpdb->prepare("SELECT COUNT(*) as objects_count FROM EnergyObjects WHERE energyObject_id = %d", $energyObject_id));

        if($child_meters) {
            $count_elements += $child_meters;
        }

        if($child_objects) {
            $count_elements += $child_objects;
        }

        /*$child_objects_ids = $this->get_resultsSQL($this->wpdb->prepare("SELECT id FROM EnergyObjects WHERE energyObject_id = %d", $energyObject_id));
        if($child_objects_ids) {
            foreach ($child_objects_ids as $child_object_id) {
                $child_object_meters = $this->get_varSQL($this->wpdb->prepare("SELECT COUNT(*) as meters_count FROM Meters WHERE energyObject_id = %d", $child_object_id->id));
                if($child_object_meters) {
                    $count_elements += $child_object_meters;
                }
                $count_elements++;
            }
        }*/

        return $count_elements;

    }

    // ----------------================ Data modifiers ===============------------------

    // ---------------- Удалим счетчик ------------------
    public function deleteMeter($meter_id, $meter_num) {

        $check_energy_object = $this->get_rowSQL($this->wpdb->prepare("SELECT id FROM EnergyObjects WHERE meter_id = %d", $meter_id));
        if($check_energy_object) {
            $this->wpdb->query($this->wpdb->prepare("UPDATE EnergyObjects SET meter_id = NULL WHERE id = %d", $check_energy_object->id));
        }

        $this->wpdb->query($this->wpdb->prepare("DELETE FROM Meters WHERE id = %d", $meter_id));
        $this->wpdb->query("DROP TABLE meter_" . $meter_num);
    }

    // --------- Удалим энергетический объект -----------
    public function deleteEnergyObject($energy_object_id) {

        $meters_results = $this->get_resultsSQL($this->wpdb->prepare("SELECT id, num FROM Meters WHERE energyObject_id = %d", $energy_object_id));

        if ($meters_results) {
            foreach ($meters_results as $meter) {
                //$this->wpdb->query($this->wpdb->prepare("DELETE FROM Meters WHERE id = %d", $meter->id));
                $this->deleteMeter($meter->id, $meter->num);
            }
        }

        $child_energy_objects = $this->get_resultsSQL($this->wpdb->prepare("SELECT id from EnergyObjects WHERE energyObject_id = %d", $energy_object_id));

        if($child_energy_objects) {
            foreach($child_energy_objects as $child_energy_object) {
                $this->deleteEnergyObject($child_energy_object->id);
            }
        }

        $this->wpdb->query($this->wpdb->prepare("DELETE FROM EnergyObjects WHERE id = %d", $energy_object_id));

    }

    // ---------------- Удалим пользователя ------------------
    public function deleteUser($customer_id) {

        $account_id = $this->get_varSQL($this->wpdb->prepare("SELECT account_id FROM Customers WHERE id = %d", $customer_id));

        if(!empty($account_id)) {
            $this->wpdb->query($this->wpdb->prepare("DELETE FROM Customers WHERE id = %d", $customer_id));
            $this->wpdb->query($this->wpdb->prepare("DELETE FROM user_room_accounts WHERE id = %d", $account_id));
        }
        else {
            return array("SQL Ошибка удаления пользователя");
        }
    }

    // ---------------- Удалим группу ------------------
    public function deleteGroup($group_id) {

        $this->wpdb->query($this->wpdb->prepare("DELETE FROM UserGroups WHERE id = %d", $group_id));
    }

    // ---------------- Удалим группу ------------------
    public function deleteMeterValue($meter_num, $value_id) {

        $this->wpdb->query($this->wpdb->prepare("DELETE FROM meter_%d WHERE id = %d", $meter_num, $value_id));
    }
}