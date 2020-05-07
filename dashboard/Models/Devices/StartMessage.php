<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 18.02.19
 * Time: 13:33
 */

namespace Models\Devices;


use Models\Loadable;

class StartMessage implements Loadable
{


    private $id;
    private $device_id;
    private $date;

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
    public function getDeviceId()
    {
        return $this->device_id;
    }

    /**
     * @param mixed $device_id
     */
    public function setDeviceId($device_id)
    {
        $this->device_id = $device_id;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
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