<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 10.02.19
 * Time: 23:21
 */

namespace Controllers\Test;


use Controllers\BaseController;
use Views\BaseView;
use Views\Test\TestView;

class GovnoController extends BaseController
{

    /**
     * @param array $get
     * @return BaseView
     */
    protected function defaultProcess($get = [])
    {
        return new TestView();
    }

    public function controll($action, $params = [], $get = [])
    {
        // TODO: Implement controll() method.
    }
}