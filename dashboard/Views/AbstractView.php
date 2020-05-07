<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 11.08.18
 * Time: 20:18
 */

namespace Views;

use Views\Drawable;

abstract class AbstractView implements Drawable {

    protected function assets($file)
    {
        return $this->makeURL(__DIR__ . "/../assets/".$file);
    }

    protected function makeURL($url){
        return str_replace('/home/c/c92142qe/integrator/public_html/', 'http://c92142qe.beget.tech/', $url);
    }



}