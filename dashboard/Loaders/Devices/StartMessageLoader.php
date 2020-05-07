<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 18.02.19
 * Time: 13:53
 */

namespace Loaders\Devices;


use Models\Devices\StartMessage;
use System\Main\Core;

class StartMessageLoader
{

    private $table = 'start_message';

    /**
     * @param StartMessage $obj
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

    public function getAll(){
        $db = Core::getInstance()->getDB();
        $query = "SELECT * FROM " . $this->table;
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
     * @param StartMessage $obj
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
     * @return StartMessage[]
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
        $objects = [];
        foreach ($req as $item)
            $objects[] = $this->toDataConverter($item);
        return $objects;
    }


    /**
     * @param array $data
     * @return StartMessage|null
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
     * @param StartMessage $region
     * @return array
     */
    private function toDBConverter($region){
        $data = $region->makeData();
        return $data;
    }

    /**
     * @param array $array
     * @return StartMessage
     */
    private function toDataConverter($array){
        $obj = new StartMessage();
        $obj->receiveData($array);
        return $obj;
    }
}