<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 19.02.19
 * Time: 23:51
 */

namespace Models\Devices;

use Models\Loadable;

class Device implements Loadable
{

    private $id;
    private $date_last_data;
    private $region;
    private $address;
    private $coords;
    private $company;
    private $date_clear_basket;
    private $occupancy;
    private $temp;
    private $errors;



    /**
     * @return mixed
     */
    public function getTemp()
    {
        return $this->temp;
    }

    /**
     * @param mixed $temp
     */
    public function setTemp($temp)
    {
        $this->temp = $temp;
    }




    /**
     * @return mixed
     */
    public function getCoords()
    {
        return $this->coords;
    }

    /**
     * @param mixed $coords
     */
    public function setCoords($coords)
    {
        $this->coords = $coords;
    }


    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
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
    public function getDateLastData()
    {
        return $this->date_last_data;
    }

    /**
     * @param mixed $date_last_data
     */
    public function setDateLastData($date_last_data)
    {
        $this->date_last_data = $date_last_data;
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
     * @return mixed
     */
    public function getDateClearBasket()
    {
        return $this->date_clear_basket;
    }

    /**
     * @param mixed $date_clear_basket
     */
    public function setDateClearBasket($date_clear_basket)
    {
        $this->date_clear_basket = $date_clear_basket;
    }

    /**
     * @return mixed
     */
    public function getOccupancy()
    {
        return $this->occupancy;
    }

    /**
     * @param mixed $occupancy
     */
    public function setOccupancy($occupancy)
    {
        $this->occupancy = $occupancy;
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param mixed $errors
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
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