<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 25.04.2018
 * Time: 10:41
 */

class Meter
{
    private $id;
    private $name;
    private $num;
    private $table_name;
    private $network_address;

    private $energyObject_id;
    private $meterType_id;
    private $concentrator_id;

    public function __construct($row_data)
    {

        $this->setId($row_data->id);
        $this->setName($row_data->name);
        $this->setNum($row_data->num);
        $this->setTableName($row_data->table_name);
        $this->setNetworkAddress($row_data->network_address);
        $this->setEnergyObjectId($row_data->energyObject_id);
        $this->setMeterTypeId($row_data->meterType_id);
        $this->setConcentratorId($row_data->concentrator_id);

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

    public function setNum($num)
    {
        $this->num = $num;
    }

    public function setTableName($table_name)
    {
        $this->table_name = $table_name;
    }

    public function setNetworkAddress($network_address)
    {
        $this->network_address = $network_address;
    }

    public function setEnergyObjectId($energyObject_id)
    {
        $this->energyObject_id = $energyObject_id;
    }

    public function setMeterTypeId($meterType_id)
    {
        $this->meterType_id = $meterType_id;
    }

    public function setConcentratorId($concentrator_id)
    {
        $this->concentrator_id = $concentrator_id;
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

    public function getNum()
    {
        return $this->num;
    }

    public function getTableName()
    {
        return $this->table_name;
    }

    public function getNetworkAddress()
    {
        return $this->network_address;
    }

    public function getEnergyObjectId()
    {
        return $this->energyObject_id;
    }

    public function getMeterTypeId()
    {
        return $this->meterType_id;
    }

    public function getConcentratorId()
    {
        return $this->concentrator_id;
    }



}