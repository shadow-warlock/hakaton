<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 11.08.18
 * Time: 18:40
 */

namespace System\Interfaces;


interface Controllable
{
    public function inputControll($post, $get = []);

    public function controll($action, $params = [], $get = []);
}