<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 27.02.19
 * Time: 20:52
 */

namespace Loaders\Devices;


use Models\Devices\Error;
use System\Main\Core;

class ErrorsLoader
{

    private $table = 'error';

    /**
     * @param Error $obj
     */
    public function create($obj){
        $data = $this->toDBConverter($obj);
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
        $obj->receiveData(['id' => $id]);
    }

    /**
     * @return Error[]
     */
    public function getAll(){
        $db = Core::getInstance()->getDB();
        $query = "SELECT * FROM " . $this->table;
        $query .= " ORDER BY id DESC";

        $req = $db->getRows($query);
        $objects = [];
        foreach ($req as $item)
            $objects[] = $this->toDataConverter($item);
        return $objects;
    }

    public function getAllCount(){
        $db = Core::getInstance()->getDB();
        $query = "SELECT COUNT(*) FROM " . $this->table;
        $req = $db->getRows($query);
        return $req[0]['COUNT(*)'];
    }

    /**
     * @param array $data
     * @param $offset
     * @param $limit
     * @return Error[]
     */
    public function getAllByDataPagination($data, $offset, $limit){
        $prep = [];
        $patterns = [];

        foreach($data as $k => $v ) {
            $prep[':'.$k] = $v;
            $patterns[] = $k . " = " . ':'.$k;
        }
        $db = Core::getInstance()->getDB();
        $query = "SELECT * FROM " . $this->table . " WHERE ";

        $query .= join(" AND ", $patterns);
        $query .= " ORDER BY id DESC" . " LIMIT " . $offset . ", " . $limit;
        $req = $db->getRows($query, $prep);
        $users = [];
        foreach ($req as $item)
            $users[] = $this->toDataConverter($item);
        return $users;
    }

    public function getAllByDataCount($data){
        $prep = [];
        $patterns = [];

        foreach($data as $k => $v ) {
            $prep[':'.$k] = $v;
            $patterns[] = $k . " = " . ':'.$k;
        }
        $db = Core::getInstance()->getDB();
        $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE ";

        $query .= join(" AND ", $patterns);
        $req = $db->getRows($query, $prep);
        return $req[0]['COUNT(*)'];
    }

    public function getAllPagination($offset, $limit){
        $db = Core::getInstance()->getDB();
        $query = "SELECT * FROM " . $this->table . " ORDER BY id DESC" . " LIMIT " . $offset . ", " . $limit;
        $req = $db->getRows($query);
        $users = [];
        foreach ($req as $item)
            $users[] = $this->toDataConverter($item);
        return $users;
    }

    /**
     * @param Error $obj
     */
    public function updateById($obj){
        $data = $this->toDBConverter($obj);
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
     * @return Error[]
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
        $query .= " ORDER BY id DESC";
        $req = $db->getRows($query, $prep);
        $objects = [];
        foreach ($req as $item)
            $objects[] = $this->toDataConverter($item);
        return $objects;
    }

    /**
     * @param integer $deviceId
     * @return Error
     */
    public function getLastByDeviceAndStatus($deviceId){
        $db = Core::getInstance()->getDB();
        $query = "SELECT * FROM " . $this->table . " WHERE device_id = ? AND state = 0 ORDER BY id DESC LIMIT 1";

        $req = $db->getRows($query, [$deviceId]);
        if(count($req) > 0)
            return $this->toDataConverter($req[0]);
        else
            return null;
    }


    /**
     * @param array $ids
     * @return Error[]
     */
    public function getAllByDevices($ids){

        $db = Core::getInstance()->getDB();
        $query = "SELECT * FROM " . $this->table . " WHERE device_id IN('".join("', '", $ids)."') ORDER BY id DESC";

        $req = $db->getRows($query);
        $objects = [];
        foreach ($req as $item)
            $objects[] = $this->toDataConverter($item);
        return $objects;
    }

    /**
     * @param array $data
     * @return Error|null
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
        $query .= " ORDER BY id DESC";
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
     * @param Error $region
     * @return array
     */
    private function toDBConverter($region){
        $data = $region->makeData();
        return $data;
    }

    /**
     * @param array $array
     * @return Error
     */
    private function toDataConverter($array){
        $obj = new Error();
        $obj->receiveData($array);
        return $obj;
    }

}