<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 07.05.2018
 * Time: 11:59
 */

class Customer
{
    private $id;
    private $name;
    private $surname;
    private $patronymic;
    private $account_id;
    private $phone;
    private $email;
    private $login;
    private $group_id;

    public function __construct($row_data)
    {
        $this->setId($row_data->id);
        $this->setAccountId($row_data->account_id);
        $this->setLogin($row_data->login);
        $this->setName($row_data->name);
        $this->setSurname($row_data->surname);
        $this->setPatronymic($row_data->patronymic);
        $this->setGroupId($row_data->group_id);
        $this->setPhone($row_data->phone);
        $this->setEmail($row_data->email);
    }

    /* ---------------- СЕТТЕРЫ ---------------- */
    public function setId($id) {
        $this->id = $id;
    }

    public function setAccountId($account_id) {
        $this->account_id = $account_id;
    }

    public function setLogin($login)
    {
        $this->login = $login;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setGroupId($group_id) {
        $this->group_id = $group_id;
    }

    public function setSurname($surname) {
        $this->surname = $surname;
    }

    public function setPatronymic($patronymic) {
        $this->patronymic = $patronymic;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    /* --------------- ГЕТТЕРЫ --------------- */
    public function getId() {
        return $this->id;
    }

    public function getAccountId()
    {
        return $this->account_id;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSurname()
    {
        return $this->surname;
    }

    public function getPatronymic()
    {
        return $this->patronymic;
    }

    public function getGroupId() {
        return $this->group_id;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getEmail()
    {
        return $this->email;
    }
}