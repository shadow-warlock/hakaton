<?php

use Controllers\Barcodes\BarcodesController;
use System\Main\Core;

include_once "../../autoloader.php";

Core::init();

$uploaddir = __DIR__ . "/";
$uploadfile = $uploaddir . basename($_FILES['myFile']['name']);

if (move_uploaded_file($_FILES['myFile']['tmp_name'], $uploadfile)) {
    $fullArray = [];
    $handle = fopen($uploadfile, 'r');
    while (($data = fgetcsv($handle)) !== FALSE){
        $fullArray[] = $data;
    }
    $controller = new BarcodesController();
    $controller->control(BarcodesController::PACKAGE_IMPORT_BARCODES, ["data" => $fullArray]);
}