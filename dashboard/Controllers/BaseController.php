<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 11.08.18
 * Time: 19:28
 */

namespace Controllers;


use Loaders\Devices\DevicesLoader;
use Loaders\Devices\ErrorsLoader;
use Loaders\Users\UserLoader;
use System\Main\Core;
use Views\BaseMenuView;
use Views\BaseView;
use Views\UnloginView;


abstract class BaseController implements Controllable
{

    const ACTION_LOGIN = "login";
    const ACTION_LOGOUT = "logout";
    /**
     * @param $post
     * @param array $get
     */
    public function inputControl($post, $get = [])
    {

        if (isset($post['action'])){
            if($post['action'] == static::ACTION_LOGIN){
                $login = $post['params']['login'];
                $password = $post['params']['password'];
                $loader = new UserLoader();

                $user = $loader->getByData(["login" => $login, "password" => sha1($password)]);
                Core::getInstance()->login($user);
                echo json_encode([
                    'success' => ($user != null)
                ]);

                return;
            }
            if($post['action'] == static::ACTION_LOGOUT){
                Core::getInstance()->logout();
                return;
            }
            if(Core::getInstance()->getUser() == null){
                header("Refresh:0");
            }
            $this->control($post['action'], isset($post['params']) ? $post['params'] : [], $get);
        } else {
            $this->baseProcess();
            $view = $this->defaultProcess($get);
            if($view instanceof BaseMenuView){
                $loader = new ErrorsLoader();
                $view->setAllErrors(count($loader->getAllByData(["state"=>0])));
                $loader = new DevicesLoader();
                $view->setAllWorkedDevices(count($loader->getAll()));
            }
            $view->draw();

        }
    }

    private function baseProcess()
    {
        if(Core::getInstance()->getUser() == null){
            $view = new UnloginView();
            $view->draw();
            die();
        }
    }

    /**
     * @param array $get
     * @return BaseView
     */
    protected abstract function defaultProcess($get = []);

    public function echoPre($data)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }

}