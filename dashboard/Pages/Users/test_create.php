<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');


use Models\Users\User;
use System\Main\Core;

include_once "../../autoloader.php";

Core::init();
$controller = new \Controllers\Devices\DevicesController();
for ($i=200; $i< 666; $i++){
    $params = [
        'id'=> $i,
        'region' => 'СПб',
        'company' => "все просто"
    ];

    $controller->control(\Controllers\Devices\DevicesController::CREATE_DEVICE, $params);
}
