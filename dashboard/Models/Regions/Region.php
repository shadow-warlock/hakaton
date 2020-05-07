<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 16.02.19
 * Time: 0:39
 */

namespace Models\Regions;


use Models\Loadable;

class Region implements Loadable
{

    private $id;
    private $region;

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