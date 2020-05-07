<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 11.08.18
 * Time: 19:01
 */

namespace System\Main;


use Controllers\Controllable;
use Models\Users\User;

class Core
{

    private $db_name;
    private $db_password;
    private $db_user;

    private static $self = null;


    private function __construct()
    {
        header('Content-Type: text/html; charset=utf-8');
        $db_json = file_get_contents(__DIR__ . '/../config/database.json');
        $db = json_decode($db_json, true);
        $this->db_name = $db['account'];
        $this->db_password = $db['password'];
        $this->db_user = $db['user'];
    }

    public function getDB(){
        return new Database($this->db_user, $this->db_password, 'localhost', $this->db_name);
    }


    public static function init()
    {
        session_start();
        static::$self = new Core();
//        echo memory_get_peak_usage ();
    }

    /**
     * @param Controllable $controller
     */
    public function start($controller){
        $controller->inputControl($_POST, $_GET);
    }

    /**
     * @return Core|null
     */
    public static function getInstance()
    {
        if (self::$self == null) {
            echo "У вас ядра не видно, вы в курсе?";
        }
        return self::$self;
    }

    /**
     * @param User $user
     */
    public function login($user){
        $_SESSION['user'] = $user;
    }

    public function logout(){
        unset($_SESSION['user']);
        session_destroy();
    }

    /**
     * @return User|null
     */
    public function getUser(){
        if(isset($_SESSION['user']))
            return $_SESSION['user'];
        return null;
    }
}