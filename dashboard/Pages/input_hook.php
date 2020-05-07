<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

//echo "1,1,0,1,1";


use System\Main\Core;

include_once "../autoloader.php";

Core::init();
$controller = new \Controllers\Arduino\ArduinoController();
Core::getInstance()->start($controller);