<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 24.04.2018
 * Time: 18:30
 */

class EnergyObject
{
    private $id;
    private $name;
    private $address;
    private $customer_id;
    private $meters_list = array();

    public function __construct($row_data)
    {
        $this->setId($row_data->id);
        $this->setAddress($row_data->address);
        $this->setName($row_data->name);
        $this->setCustomerId($row_data->customer_id);
    }

    /* ---------------- СЕТТЕРЫ ---------------- */

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function setCustomerId($customer_id)
    {
        $this->customer_id = $customer_id;
    }

    public function setMetersList($meters_list)
    {
        $this->meters_list = $meters_list;
    }

    /* --------------- ГЕТТЕРЫ --------------- */

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getCustomerId()
    {
        return $this->customer_id;
    }

    public function getMetersList()
    {
        return $this->meters_list;
    }


}