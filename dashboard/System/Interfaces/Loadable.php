<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 24.09.18
 * Time: 12:30
 */

namespace System\Interfaces;


interface Loadable
{
    /**
     * @return array
     */
    public function makeData();

    /**
     * @param array $array
     */
    public function receiveData($array);
}