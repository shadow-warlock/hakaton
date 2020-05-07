<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 16.02.19
 * Time: 0:39
 */

namespace Models\Companies;


use Models\Loadable;

class Company implements Loadable
{

    private $id;
    private $company;

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
}