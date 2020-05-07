<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 11.02.19
 * Time: 0:58
 */

namespace Loaders\Users;


use Models\Users\User;
use System\Main\Core;

class UserLoader
{
    private $table = 'users';

    /**
     * @param User $user
     */
    public function create($user){
        $data = $this->toDBConverter($user);
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
        $user->receiveData(['id' => $id]);
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
     * @param User $user
     */
    public function updateById($user){
        $data = $this->toDBConverter($user);
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
     * @return User[]
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
        $users = [];
        foreach ($req as $item)
            $users[] = $this->toDataConverter($item);
        return $users;
    }

    /**
     * @param array $data
     * @param $offset
     * @param $limit
     * @return User[]
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

    /**
     * @param array $data
     * @return User|null
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
     * @param User $user
     * @return array
     */
    private function toDBConverter($user){
        $data = $user->makeData();
        $key = 'modules';
        if(array_key_exists($key, $data)){
            $data[$key] = json_encode($data[$key]);
        }
        return $data;
    }

    /**
     * @param array $array
     * @return User
     */
    private function toDataConverter($array){
        $user = new User();
        $key = 'modules';
        if(array_key_exists($key, $array)){
            $array[$key] = json_decode($array[$key], true);
        }
        $user->receiveData($array);
        return $user;
    }
}