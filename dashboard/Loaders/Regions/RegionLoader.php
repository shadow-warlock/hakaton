<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 16.02.19
 * Time: 13:04
 */

namespace Loaders\Regions;


use Models\Regions\Region;
use System\Main\Core;

class RegionLoader
{

    private $table = 'region';

    /**
     * @param Region $region
     */
    public function create($region){
        $data = $this->toDBConverter($region);
        $prep = [];
        foreach($data as $k => $v ) {
            $prep[':'.$k] = $v;
        }
        $db = Core::getInstance()->getDB();
        $query = "INSERT INTO " . $this->table . " (";
        $query .= join(", ", array_keys($data));
        $query .= ") VALUES(";
        $query .= join(", ", array_keys($prep));
        $query .= ")";
        $id = $db->insertRow($query, $prep);
        $region->receiveData(['id' => $id]);
    }

    public function getAll(){
        $db = Core::getInstance()->getDB();
        $query = "SELECT * FROM " . $this->table;
        $req = $db->getRows($query);
        $users = [];
        foreach ($req as $item)
            $users[] = $this->toDataConverter($item);
        return $users;
    }

    public function getAllCount(){
        $db = Core::getInstance()->getDB();
        $query = "SELECT COUNT(*) FROM " . $this->table;
        $req = $db->getRows($query);
        return $req[0]['COUNT(*)'];
    }


    /**
     * @param Region $region
     */
    public function updateById($region){
        $data = $this->toDBConverter($region);
        $prep = [];
        $pattern = [];
        $idPattern = '';
        foreach($data as $k => $v ) {
            $prep[':'.$k] = $v;
            if($k != 'id')
                $pattern[] = $k .'=:'. $k;
            else
                $idPattern = $k .'=:'. $k;
        }
        $db = Core::getInstance()->getDB();
        $query = "UPDATE " . $this->table . " SET ";
        $query .= join(", ", $pattern);
        $query .= " WHERE " . $idPattern;
        $db->updateRow($query, $prep);
    }

    /**
     * @param array $data
     * @return Region[]
     */
    public function getAllByData($data){
        $prep = [];
        $patterns = [];

        foreach($data as $k => $v ) {
            $prep[':'.$k] = $v;
            $patterns[] = $k . " = " . ':'.$k;
        }
        $db = Core::getInstance()->getDB();
        $query = "SELECT * FROM " . $this->table . " WHERE ";

        $query .= join(" AND ", $patterns);
        $req = $db->getRows($query, $prep);
        $regions = [];
        foreach ($req as $item)
            $regions[] = $this->toDataConverter($item);
        return $regions;
    }


    /**
     * @param array $data
     * @return Region|null
     */
    public function getByData($data){
        $prep = [];
        $patterns = [];

        foreach($data as $k => $v ) {
            $prep[':'.$k] = $v;
            $patterns[] = $k . " = " . ':'.$k;
        }
        $db = Core::getInstance()->getDB();
        $query = "SELECT * FROM " . $this->table . " WHERE ";

        $query .= join(" AND ", $patterns);
        $req = $db->getRows($query, $prep);
        if(empty($req)){
            return null;
        }else{
            return $this->toDataConverter($req[0]);
        }
    }


    /**
     * @param int $id
     */
    public function deleteById($id){
        $prep = [':id' => $id];
        $db = Core::getInstance()->getDB();
        $query = "DELETE FROM " . $this->table . " WHERE id=:id";
        $db->deleteRow($query, $prep);
    }

    /**
     * @param Region $region
     * @return array
     */
    private function toDBConverter($region){
        $data = $region->makeData();
        $key = 'modules';
        if(array_key_exists($key, $data)){
            $data[$key] = json_encode($data[$key]);
        }
        return $data;
    }

    /**
     * @param array $array
     * @return Region
     */
    private function toDataConverter($array){
        $region = new Region();
        $key = 'modules';
        if(array_key_exists($key, $array)){
            $array[$key] = json_decode($array[$key], true);
        }
        $region->receiveData($array);
        return $region;
    }
}