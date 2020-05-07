<?php

namespace System\Main;

use PDO;
use PDOException;

class Database
{
    public $isConnect;
    protected $datab;

    // Connect to DB

    public function __construct($username = "root", $password = "", $host = "localhost", $dbname = "DBNAME", $options = [])
    {
//        mysql_set_charset("utf8");
        $this->isConnect = TRUE;
        try
        {
            $this->datab = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password, $options);
//           $this->datab->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->datab->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->datab->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            echo $e->getMessage();
        }
    }

    // Disconnect from DB
    public function Disconnect()
    {
        $this->datab = NULL;
        $this->isConnect = FALSE;
    }
    // Get Row
    public function getRow($query, $params = [])
    {
        try
        {
            $stmt = $this->datab->prepare($query);
            $params2 = [];
            foreach ($params as $k => $v){
                $params2[$k] = isset($v)?$v:NULL;
            }
            $stmt->execute($params2);
            return $stmt->fetch();
        }
        catch (PDOException $e)
        {
            echo $e->getMessage();
        }
    }
    public function getRows($query, $params = [])
    {
        try
        {
            $stmt = $this->datab->prepare($query);
            $params2 = [];
            foreach ($params as $k => $v){
                $params2[$k] = isset($v)?$v:NULL;
            }
            $stmt->execute($params2);
            return $stmt->fetchAll();
        }
        catch (PDOException $e)
        {
            echo $e->getMessage();
        }
    }
    // Insert Row
    public function insertRow($query, $params = [])
    {
        try
        {
            $stmt = $this->datab->prepare($query);
            $stmt->execute($params);
            return $this->datab->lastInsertId();
        }
        catch (PDOException $e)
        {
            echo $e->getMessage();
            return null;
        }
    }
    // Update Row
    public function updateRow($query, $params = [])
    {
        $this->insertRow($query, $params);
    }
    // Delete Row
    public function deleteRow($query, $params = [])
    {
        $this->insertRow($query, $params);
    }
}


?>