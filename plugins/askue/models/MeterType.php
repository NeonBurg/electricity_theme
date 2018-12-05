<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 19.04.2018
 * Time: 18:24
 */

// Модель: тип счетчика
class MeterType
{
    private $id;
    private $name;
    private $type;

    public function __construct($row_data)
    {
        $this->setId($row_data->id);
        $this->setName($row_data->name);
        $this->setType($row_data->type);
    }

    /* ---------------- СЕТТЕРЫ ---------------- */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    /* --------------- ГЕТТЕРЫ --------------- */
    public function getId()
    {
        return $this->id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getName()
    {
        return $this->name;
    }

}