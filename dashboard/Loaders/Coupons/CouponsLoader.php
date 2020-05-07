<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 26.02.19
 * Time: 12:25
 */

namespace Loaders\Coupons;


use Models\Coupons\Coupon;
use System\Main\Core;

class CouponsLoader
{
    private $table = 'coupon';

    /**
     * @param Coupon $obj
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
     * @return Coupon[]
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


    public function getAllNames(){
        $db = Core::getInstance()->getDB();
        $query = "SELECT DISTINCT(name) FROM " . $this->table;
        $req = $db->getRows($query);
        $array = [];
        foreach ($req as $item){
            $array[] = $item['name'];
        }
        return $array;
    }

    public function getAllPartners(){
        $db = Core::getInstance()->getDB();
        $query = "SELECT DISTINCT(partner) FROM " . $this->table;
        $req = $db->getRows($query);
        $array = [];
        foreach ($req as $item){
            $array[] = $item['partner'];
        }
        return $array;
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
     * @return Coupon[]
     */
    public function getAllByDataPaginationSorted($data, $sorter, $offset, $limit){
        $prep = [];
        $patterns = [];

        foreach($data as $k => $v ) {
            $prep[':'.$k] = $v;
            $patterns[] = $k . " = " . ':'.$k;
        }
        $db = Core::getInstance()->getDB();
        $query = "SELECT * FROM " . $this->table . " WHERE ";

        $query .= join(" AND ", $patterns);
        $query .= " ORDER BY ".$sorter." DESC" . " LIMIT " . $offset . ", " . $limit;
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

    public function getAllPaginationSorted($sorter, $offset, $limit){
        $sortType = "DESC";
        if($sorter == "date"){
            $sorter = "DATE_FORMAT(STR_TO_DATE(date, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')";
        }
        if($sorter == 'name')
            $sortType = "ASC";

        $db = Core::getInstance()->getDB();
        $query = "SELECT * FROM " . $this->table . " ORDER BY ".$sorter." ". $sortType . " LIMIT " . $offset . ", " . $limit;
        $req = $db->getRows($query);
        $users = [];
        foreach ($req as $item)
            $users[] = $this->toDataConverter($item);
        return $users;
    }

    /**
     * @param Coupon $obj
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
     * @return Coupon[]
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
     * @param string $company
     * @return Coupon[]
     */
    public function getAllByCompanyLimitedCounted($company){
        $db = Core::getInstance()->getDB();
        $query = "SELECT * FROM " . $this->table . " WHERE company = ? AND type = ? AND count > 0";

        $req = $db->getRows($query, [$company, Coupon::LIMITED]);
        $objects = [];
        foreach ($req as $item)
            $objects[] = $this->toDataConverter($item);
        return $objects;
    }


    /**
     * @param array $data
     * @return Coupon|null
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
     * @param Coupon $region
     * @return array
     */
    private function toDBConverter($region){
        $data = $region->makeData();
        return $data;
    }

    /**
     * @param array $array
     * @return Coupon
     */
    private function toDataConverter($array){
        $obj = new Coupon();
        $obj->receiveData($array);
        return $obj;
    }
}