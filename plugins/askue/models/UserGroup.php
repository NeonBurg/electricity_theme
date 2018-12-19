<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 04.12.2018
 * Time: 12:33
 */

class UserGroup
{
    private $id;
    private $name;
    private $access_level;

    public function __construct($row_data) {
        $this->setId($row_data->id);
        $this->setName($row_data->name);
        $this->setAccessLevel($row_data->access_level);
    }

    /* ---------------- СЕТТЕРЫ ---------------- */
    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name= $name;
    }

    public function setAccessLevel($access_level) {
        $this->access_level = $access_level;
    }

    /* --------------- ГЕТТЕРЫ --------------- */
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getAccessLevel() {
        return $this->access_level;
    }
}