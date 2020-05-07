<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');


use System\Main\Core;

include_once "../dashboard/autoloader.php";
Core::init();
$controller = new \Controllers\Devices\DevicesController();
Core::getInstance()->start($controller);