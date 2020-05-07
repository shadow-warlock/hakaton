<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 11.08.18
 * Time: 18:40
 */

namespace Controllers;


interface Controllable
{
    public function inputControl($post, $get = []);

    public function control($action, $params = [], $get = []);
}