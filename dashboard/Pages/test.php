<?php

use System\Main\Core;

include_once "../autoloader.php";

Core::init();
$userLoader = new \Loaders\Users\UserLoader();

for ($k=1; $k<= 10000; $k*=10){
    $time = time();
    for($i=0; $i<500; $i++){
        $userLoader->getAllPagination(0, 25);
    }

    echo "Вывод пользователей ". 500*$k. " раз (тест нагрузки на БД на странице пользователи)<br>";
    echo "Время выполнения запросов " . (time() - $time) . "<br>";
}

