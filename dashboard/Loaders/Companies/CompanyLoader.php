<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 16.02.19
 * Time: 13:09
 */

namespace Loaders\Companies;


use Models\Companies\Company;
use System\Main\Core;

class CompanyLoader
{
    private $table = 'company';

    /**
     * @param Company $company
     */
    public function create($company){
        $data = $this->toDBConverter($company);
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
        $company->receiveData(['id' => $id]);
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
     * @param $company $company
     */
    public function updateById($company){
        $data = $this->toDBConverter($company);
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
     * @return Company[]
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
        $companies = [];
        foreach ($req as $item)
            $companies[] = $this->toDataConverter($item);
        return $companies;
    }


    /**
     * @param array $data
     * @return Company|null
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
     * @param Company $company
     * @return array
     */
    private function toDBConverter($company){
        $data = $company->makeData();
        $key = 'modules';
        if(array_key_exists($key, $data)){
            $data[$key] = json_encode($data[$key]);
        }
        return $data;
    }

    /**
     * @param array $array
     * @return Company
     */
    private function toDataConverter($array){
        $company = new Company();
        $key = 'modules';
        if(array_key_exists($key, $array)){
            $array[$key] = json_decode($array[$key], true);
        }
        $company->receiveData($array);
        return $company;
    }
}