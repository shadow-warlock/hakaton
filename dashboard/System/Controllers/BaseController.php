<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 11.08.18
 * Time: 19:28
 */

namespace System\Controllers;


use System\Interfaces\Controllable;
use System\Main\Core;
use System\Models\User;
use System\Views\BaseView;
use System\Views\UnloginView;

abstract class BaseController implements Controllable
{

    const ACTION_LOGIN = "login";
    const ACTION_LOGOUT = "logout";
    /**
     * @param $post
     * @param array $get
     */
    public function inputControll($post, $get = [])
    {
        if (isset($post['action'])){
            if($post['action'] == static::ACTION_LOGIN){
                $login = $post['params']['login'];
                $api = $post['params']['api'];
                $subdomen = $post['params']['subdomen'];
                $user = User::firstMake($subdomen, $api, $login);
                $connect = $user->checkConnectApi();
                if($connect){
                    $user->syncToDB();
                    Core::getInstance()->login($user);
                }
                echo json_encode([
                    'success' => $connect
                ]);
                return;
            }
            if($post['action'] == static::ACTION_LOGOUT){
                Core::getInstance()->logout();
                return;
            }
            $this->controll($post['action'], isset($post['params']) ? $post['params'] : [], $get);
        } else {
            $this->baseProcess();
            $view = $this->defaultProcess($get);
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