<?php


namespace Controllers\Android;


use Controllers\Controllable;
use Loaders\Devices\DevicesLoader;
use Loaders\Devices\ErrorsLoader;
use Loaders\Users\UserLoader;
use Models\Users\User;

class AndroidListController implements Controllable
{

    public function inputControl($post, $get = [])
    {
        $userLoader = new UserLoader();
        $user = $userLoader->getByData(['login'=>$post['login'], "password" => sha1($post['password'])]);
        if(!$user instanceof User){
            http_response_code(401);
        }else{
            $deviceLoader = new DevicesLoader();
            $devices = $deviceLoader->getAllByData(["company" => $user->getCompany()]);
            $errors = [];
            $errorLoader = new ErrorsLoader();
            foreach ($devices as $device){
                $localErrors = $errorLoader->getAllByData(["device_id"=>$device->getId(), "state"=>0]);
                foreach ($localErrors as $error){
                    $errors[] = [
                        "device_id"=>$device->getId(),
                        "error"=>$error->getError(),
                        "time"=>$error->getTime(),
                        "place"=>$device->getAddress()
                    ];
                }
            }
            echo json_encode($errors);
        }
    }

    public function control($action, $params = [], $get = [])
    {
        // TODO: Implement control() method.
    }
}