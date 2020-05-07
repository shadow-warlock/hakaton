<?php


namespace Controllers\Android;


use Controllers\Controllable;
use Loaders\Devices\DevicesLoader;
use Loaders\Devices\ErrorsLoader;
use Loaders\Users\UserLoader;
use Models\Devices\Device;
use Models\Users\User;

class AndroidServiceController implements Controllable
{

    public function inputControl($post, $get = [])
    {
        $userLoader = new UserLoader();
        $user = $userLoader->getByData(['login'=>$post['login'], "password" => sha1($post['password'])]);
        if(!$user instanceof User){
            http_response_code(401);
        }else{
            $lastTime = $user->getLastServiceTime();

            $errorLoader = new ErrorsLoader();
            $errors = $errorLoader->getAllByData(["state" => 0]);
            $deviceLoader = new DevicesLoader();
            $data = [];
            foreach ($errors as $error){
                if(strtotime($error->getTime()) > strtotime($lastTime)){
                    $device = $deviceLoader->getByData(["id" => $error->getDeviceId()]);
                    if($device instanceof Device && $device->getCompany() == $user->getCompany()){
                        $data[] = [
                            "device_id"=>$device->getId(),
                            "error"=>$error->getError(),
                            "time"=>$error->getTime(),
                            "place"=>$device->getAddress()
                        ];
                    }
                }
            }
            $user->setLastServiceTime(date("d.m.Y H:i:s"));
            $userLoader = new UserLoader();
            $userLoader->updateById($user);
            echo json_encode($data);
        }
    }

    public function control($action, $params = [], $get = [])
    {
        // TODO: Implement control() method.
    }
}