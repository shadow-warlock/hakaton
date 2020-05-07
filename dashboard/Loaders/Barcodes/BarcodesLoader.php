<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 24.02.19
 * Time: 23:51
 */

namespace Loaders\Barcodes;


use Models\Barcodes\Barcode;
use System\Main\Core;

class BarcodesLoader
{
    private $table = 'barcode';

    /**
     * @param Barcode $obj
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
     * @return Barcode[]
     */
    public function getAll(){
        $db = Core::getInstance()->getDB();
        $query = "SELECT * FROM " . $this->table;
        $req = $db->getRows($query);
        $objects = [];
        foreach ($req as $item)
            $objects[] = $this->toDataConverter($item);
        return $objects;
    }

    /**
     * @return Barcode[]
     */
    public function getAllNews(){
        $db = Core::getInstance()->getDB();
        $query = "SELECT * FROM " . $this->table . " WHERE DATE_FORMAT(STR_TO_DATE(date, '%d.%m.%Y'), '%Y-%m-%d') >= ?";
        $req = $db->getRows($query, [date("Y-m-d", time()-60*60*24*7)]);
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
     * @return Barcode[]
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
        $query .= " ORDER BY DATE_FORMAT(STR_TO_DATE(date, '%d.%m.%Y'), '%Y-%m-%d') DESC, time DESC" . " LIMIT " . $offset . ", " . $limit;
        $req = $db->getRows($query, $prep);
        $users = [];
        foreach ($req as $item)
            $users[] = $this->toDataConverter($item);
        return $users;
    }

    /**
     * @param string $filter
     * @param $offset
     * @param $limit
     * @return Barcode[]
     */
    public function getAllByAllFilterPagination($filter, $offset, $limit){
        $prep = [];
        $patterns = [];

        $data = [
            "id",
            "product_name",
            "type",
            "manufacturer",
            "volume",
            "material",
            "weight",
            "date",
            "time"
        ];

        foreach($data as $v ) {
            $patterns[] = $v . " LIKE '%".$filter . "%'";
        }
        $db = Core::getInstance()->getDB();
        $query = "SELECT * FROM " . $this->table . " WHERE ";

        $query .= join(" OR ", $patterns);
        $query .= " ORDER BY DATE_FORMAT(STR_TO_DATE(date, '%d.%m.%Y'), '%Y-%m-%d') DESC, time DESC" . " LIMIT " . $offset . ", " . $limit;
        $req = $db->getRows($query);
        $users = [];
        foreach ($req as $item)
            $users[] = $this->toDataConverter($item);
        return $users;
    }

    /**
     * @param string $filter

     * @return int
     */
    public function getAllByAllFilterCount($filter){
        $prep = [];
        $patterns = [];

        $data = [
            "id",
            "product_name",
            "type",
            "manufacturer",
            "volume",
            "material",
            "weight",
            "date",
            "time"
        ];

        foreach($data as $v ) {
            $patterns[] = $v . " LIKE '%" . $filter . "%'";
        }
        $db = Core::getInstance()->getDB();
        $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE ";

        $query .= join(" OR ", $patterns);
        $req = $db->getRows($query);
        return $req[0]["COUNT(*)"];
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
        $query = "SELECT * FROM " . $this->table . " ORDER BY DATE_FORMAT(STR_TO_DATE(date, '%d.%m.%Y'), '%Y-%m-%d') DESC, time DESC" . " LIMIT " . $offset . ", " . $limit;
        $req = $db->getRows($query);
        $users = [];
        foreach ($req as $item)
            $users[] = $this->toDataConverter($item);
        return $users;
    }

    /**
     * @param Barcode $obj
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
     * @return Barcode[]
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
     * @return Barcode|null
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
     * @param Barcode $region
     * @return array
     */
    private function toDBConverter($region){
        $data = $region->makeData();
        return $data;
    }

    /**
     * @param array $array
     * @return Barcode
     */
    private function toDataConverter($array){
        $obj = new Barcode();
        $obj->receiveData($array);
        return $obj;
    }
}