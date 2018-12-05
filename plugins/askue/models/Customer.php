<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 07.05.2018
 * Time: 11:59
 */

class Customer
{
    private $name;
    private $account_id;
    private $phone;
    private $email;
    private $login;

    public function __construct($row_data)
    {
        $this->setLogin($row_data->login);
        $this->setName($row_data->name);
    }

    /* ---------------- СЕТТЕРЫ ---------------- */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    public function setName($name)
    {
        $this->name = $name;
    }


    /* --------------- ГЕТТЕРЫ --------------- */
    public function getLogin()
    {
        return $this->login;
    }

    public function getName()
    {
        return $this->name;
    }
}