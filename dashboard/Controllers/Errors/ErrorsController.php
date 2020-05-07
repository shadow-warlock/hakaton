<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 27.02.19
 * Time: 20:25
 */

namespace Controllers\Errors;


use Controllers\BaseController;
use Loaders\Devices\DevicesLoader;
use Loaders\Devices\ErrorsLoader;
use Models\Users\User;
use System\Main\Core;
use Views\BaseView;
use Views\EmptyMessageView;
use Views\Errors\ErrorsView;

class ErrorsController extends BaseController
{
    const FIX_PROBLEM = "fix problem";

    /**
     * @param array $get
     * @return BaseView
     */
    protected function defaultProcess($get = [])
    {
        $view = new ErrorsView();
        $devicesLoader = new DevicesLoader();

        if(isset($get['id'])){
            $errorLoader = new ErrorsLoader();
            $device = $devicesLoader->getByData(['id'=>$get['id']]);
            if($device == null){
                $view = new EmptyMessageView();
                $view->setMessage("Нет устройства с таким номером");
                return $view;
            }
            if(Core::getInstance()->getUser()->getRole() == User::SUPER_ADMIN || Core::getInstance()->getUser()->getCompany() == $device->getCompany()){
                $errors = $errorLoader->getAllByDevices([$device->getId()]);
            }else{
                $view = new EmptyMessageView();
                $view->setMessage("Нет доступа к устройству");
                return $view;
            }
        }else{
            $errorLoader = new ErrorsLoader();
            if(Core::getInstance()->getUser()->getRole() == User::SUPER_ADMIN){
                $errors = $errorLoader->getAll();
            }else{
                $devices = $devicesLoader->getAllByData(['company' => Core::getInstance()->getUser()->getCompany()]);
                $ids = [];
                foreach ($devices as $device){
                    $ids[] = $device->getId();
                }
                $errors = $errorLoader->getAllByDevices($ids);
            }
        }
        $view->setErrors($errors);
        return $view;
    }

    public function control($action, $params = [], $get = [])
    {
        switch ($action){
            case static::FIX_PROBLEM:
                $errorLoader = new ErrorsLoader();
                $error = $errorLoader->getByData(["id" => $params['id']]);
                $error->setState(1);
                $errorLoader->updateById($error);
                $deviceId = $error->getDeviceId();
                $deviceLoader = new DevicesLoader();
                $device = $deviceLoader->getByData(['id' => $deviceId]);
                $error = $errorLoader->getLastByDeviceAndStatus($deviceId);
                if($error != null){
                    $device->setErrors($error->getError());
                }else{
                    $device->setErrors("-");
                }
                $deviceLoader->updateById($device);

                break;
        }
    }
}