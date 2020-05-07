<?php


namespace Controllers\Android;


use Controllers\Controllable;
use Loaders\Users\UserLoader;
use Models\Users\User;

class AndroidController implements Controllable
{

    public function inputControl($post, $get = [])
    {
        $userLoader = new UserLoader();
        $user = $userLoader->getByData(['login'=>$post['login'], "password" => sha1($post['password'])]);
        if(!$user instanceof User){
            http_response_code(401);
        }else{
            echo "success";
        }
    }

    public function control($action, $params = [], $get = [])
    {
        // TODO: Implement control() method.
    }
}