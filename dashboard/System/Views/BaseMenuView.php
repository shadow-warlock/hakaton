<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 14.07.18
 * Time: 20:01
 */

namespace Integrator\System\Views;


use Integrator\System\Main\Core;

abstract class BaseMenuView extends BaseView {



    public function drawMenu()
    {
        $this->loadCSS('menu.css', Core::getInstance()->getSystemResPath(), false);

        ?>
        <nav class="navbar navbar-expand-sm">
            <div class="container-fluid">
                <div class="navbar-header">
                    <img style="height: 10vh" src="<?=$this->getImgURL('logo.png', Core::getInstance()->getSystemResPath())?>">
                </div>
                <ul class="nav navbar-nav mr-auto">
                    <li class="active">
                        <a class="nav-link" href="http://c92142qe.beget.tech/MainPage">Главная</a>
                    </li>
                    <li>
                        <a class="nav-link" href="http://c92142qe.beget.tech/Payment">Пополнить счет</a>
                    </li>
                    <li>
                        <a class="nav-link" href="#">Техподдержка</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav mr-auto">
                    <li class="menu_account_item">
                        <div>Аккаунт</div>
                        <div><?=$this->user->getSubdomain()?></div>
                    </li>
                    <li class="menu_account_item">
                        <div>Баланс</div>
                        <div><?=$this->user->getCash()?>р</div>
                    </li>
                    <li class="menu_account_item">
                        <div>Пользователь</div>
                        <div><?=$this->user->getLogin()?></div>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="#" onclick="logOut();"><i class="fas fa-sign-out-alt"></i>Выйти</a>
                    </li>
                </ul>
            </div>
        </nav>
        <?
    }

}