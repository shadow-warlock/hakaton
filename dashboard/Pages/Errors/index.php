<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');


use System\Main\Core;

include_once "../../autoloader.php";

Core::init();
$controller = new \Controllers\Errors\ErrorsController();
Core::getInstance()->start($controller);