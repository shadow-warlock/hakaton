<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 28.02.19
 * Time: 20:21
 */

namespace Views;


class NotAccessPageView extends EmptyMessageView
{

    /**
     * NotAccessPageView constructor.
     */
    public function __construct()
    {
        $this->setMessage("Нет доступа к странице <br> Вы будете перенаправлены на страницу Устройства");
        $this->setScript("setTimeout(function(){location.href='../devices'}, 2000)");
    }
}