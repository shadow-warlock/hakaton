<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 17.09.18
 * Time: 12:17
 */

namespace Models\Users;


use Models\Loadable;
use System\Main\Core;

class User implements Loadable
{
    private $id;
    private $login;
    private $email;
    private $password;
    private $region;
    private $company;
    private $role;
    private $date_create;
    private $last_service_time;
    const SUPER_ADMIN = "Супер-администратор";
    const ADMIN = "Администратор";
    const TECHNICIAN = "Техник";
    const USER = "Пользователь";
    const DEFAULT_PASSWORD = "*****";

    public static function getRolesForAdmins(){
        if(Core::getInstance()->getUser()->getRole() == static::SUPER_ADMIN || Core::getInstance()->getUser()->getRole() == static::ADMIN){
            return [
                static::ADMIN,
                static::TECHNICIAN,
                static::USER
            ];
        }else{
            return [];
        }
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getLastServiceTime()
    {
        return $this->last_service_time;
    }

    /**
     * @param mixed $last_service_time
     */
    public function setLastServiceTime($last_service_time)
    {
        $this->last_service_time = $last_service_time;
    }



    public static function gelAllRoles(){
        return [
            static::SUPER_ADMIN,
            static::ADMIN,
            static::TECHNICIAN,
            static::USER
        ];
    }

    /**
     * @return array
     */
    public function makeData(){
        $data = get_object_vars($this);
        foreach ($data as $k => $v){
            if($v === null){
                unset($data[$k]);
            }
        }
        return $data;
    }

    /**
     * @param array $array
     */
    public function receiveData($array){
        $data = get_object_vars($this);
        foreach ($data as $k => $v){
            array_key_exists($k, $array) ? $this->$k = $array[$k] : NULL;
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param mixed $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return mixed
     */
    public function getDateCreate()
    {
        return $this->date_create;
    }

    /**
     * @param mixed $date_create
     */
    public function setDateCreate($date_create)
    {
        $this->date_create = $date_create;
    }


    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }



}