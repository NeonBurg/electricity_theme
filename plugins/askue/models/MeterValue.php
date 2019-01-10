<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 24.12.2018
 * Time: 12:43
 */

class MeterValue
{
    private $id;
    private $type;
    private $date;
    private $base;
    private $decim;

    public function __construct($row_data)
    {
        $this->setId($row_data->id);
        $this->setType($row_data->type);
        $this->setBase($row_data->base);
        $this->setDecim($row_data->decim);
        $this->setDate($row_data->date);
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

    public function setBase($base) {
        $this->base = $base;
    }

    public function setDecim($decim) {
        $this->decim = $decim;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    /* --------------- ГЕТТЕРЫ --------------- */

    public function getId()
    {
        return $this->id;
    }

    public function getType() {
        return $this->type;
    }

    public function getValue() {
        return doubleval($this->base.'.'.$this->decim);
    }

    public function getDate() {
        return $this->date;
    }

    public function getFormattedDate() {
        return date_format(date_create($this->date), 'd.m.Y H:i');
    }

    public function getBase() {
        return $this->base;
    }

    public function getDecim() {
        return $this->decim;
    }

}