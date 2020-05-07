<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 18.02.19
 * Time: 13:26
 */

namespace Controllers\Arduino;


use Controllers\Controllable;
use Loaders\Devices\DevicesLoader;
use Loaders\Devices\ErrorsLoader;
use Loaders\Users\UserLoader;
use Models\Devices\Error;
use System\Main\EmailSender;


class ArduinoController implements Controllable
{

    const MESSAGE = "message";
    const MESSAGE_OCCUPANCY = "Устройство загружено";
    const WARNING_OCCUPANCY = 70;

    public function inputControl($post, $get = [])
    {
        $this->control(static::MESSAGE, $post);
    }

    public function control($action, $params = [], $get = [])
    {
        $deviceLoader = new DevicesLoader();
        $device = $deviceLoader->getByData(["id" => $params['id']]);
        if($device == null){
            http_response_code(401);
            return;
        }
        switch ($action){
            case static::MESSAGE:
                if(!isset($params['occupancy'])){
                    http_response_code(403);
                    return;
                }
                if($params['occupancy'] < $device->getOccupancy() && $params['occupancy'] < 15){
                    $device->setDateClearBasket(date("d.m.Y H:i"));
                    $errorLoader = new ErrorsLoader();
                    $errors = $errorLoader->getAllByData(["device_id"=>$device->getId(), "error"=>static::MESSAGE_OCCUPANCY]);
                    foreach ($errors as $error){
                        $error->setState(1);
                        $errorLoader->updateById($error);
                    }
                    $errors = $errorLoader->getAllByData(["device_id" => $device->getId(), "state" => 0]);
                    if(empty($errors)){
                        $device->setErrors("-");
                    }else{
                        $device->setErrors($errors[count($errors-1)]->getError());
                    }
                }

                if($params['occupancy'] >= static::WARNING_OCCUPANCY && $device->getOccupancy() < static::WARNING_OCCUPANCY){
                    $error = new Error();
                    $error->setError(static::MESSAGE_OCCUPANCY);
                    $error->setTime(date("d.m.Y H:i:s"));
                    $error->setDeviceId($params['id']);
                    $loader = new ErrorsLoader();
                    $loader->create($error);
                    $device->setErrors(static::MESSAGE_OCCUPANCY);

                    $sender = new EmailSender();
                    $usersLoader = new UserLoader();
                    $users = $usersLoader->getAllByData(["company" => $device->getCompany()]);
                    foreach ($users as $user){
                        $message = "Урна номер " . $device->getId() . " загружена на " . $params['occupancy'] . "%. Находится по адресу " . $device->getAddress();
                        try{
                            $sender->send([$user->getEmail() => $user->getLogin()], static::MESSAGE_OCCUPANCY, $message);
                        }catch (\Swift_RfcComplianceException $e){

                        }
                    }
                }
                $device->setDateLastData(date("d.m.Y H:i"));
                $device->setOccupancy($params['occupancy']);
                $deviceLoader->updateById($device);
                break;

        }
    }
}