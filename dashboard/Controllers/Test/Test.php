<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 10.02.19
 * Time: 23:19
 */

namespace Controllers\Test;


use Controllers\BaseController;
use Views\BaseView;

class Test extends BaseController
{

    /**
     * @param array $get
     * @return BaseView
     */
    protected function defaultProcess($get = [])
    {
        return null;
    }
}