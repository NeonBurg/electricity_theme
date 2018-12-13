<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 19.04.2018
 * Time: 18:36
 */

require_once ("MeterType.php");
require_once ("Meter.php");
require_once ("EnergyObject.php");
require_once ("Customer.php");
require_once ("UserGroup.php");

class DataController
{
    private $err;
    private $wpdb;

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
    public function selectEnergyObjects() {

        $energy_objects_list = array();

        $results = $this->get_resultsSQL("SELECT * FROM EnergyObjects");
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
            $select_query = "SELECT * FROM Meters WHERE energyObject_id = " . $energyObject_id;
        }

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
        if($results) $meter = new Meter($results);

        return $meter;

    }


    // ----------- Получим список с данными о клиентах ------------
    public function selectCustomersList() {

        $customers_list = array();

        $select_query = "SELECT Customers.*, user_room_accounts.login FROM Customers INNER JOIN user_room_accounts ON Customers.account_id = user_room_accounts.id";

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


    // ------------------------------- Data modifiers ------------------------------------

    // ---------------- Удалим счетчик ------------------
    public function deleteMeter($meter_id) {

        $this->wpdb->query($this->wpdb->prepare("DELETE FROM Meters WHERE id = %d", $meter_id));
        $this->wpdb->query("DROP TABLE meter_" . $meter_id);
    }

    // --------- Удалим энергетический объект -----------
    public function deleteEnergyObject($energy_object_id) {

        $meters_results = $this->get_resultsSQL($this->wpdb->prepare("SELECT id FROM Meters WHERE energyObject_id = %d", $energy_object_id));

        if ($meters_results) {
            foreach ($meters_results as $meter) {
                //$this->wpdb->query($this->wpdb->prepare("DELETE FROM Meters WHERE id = %d", $meter->id));
                $this->deleteMeter($meter->id);
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
}