<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 19.02.19
 * Time: 22:32
 */

namespace Controllers\Devices;


use Controllers\Barcodes\BarcodesController;
use Controllers\BaseController;
use Controllers\Coupons\CouponsController;
use Loaders\Companies\CompanyLoader;
use Loaders\Devices\DevicesLoader;
use Loaders\Regions\RegionLoader;
use Models\Devices\Device;
use Models\Support\Pagination;
use Models\Users\User;
use System\Main\Core;
use System\Main\EmailSender;
use Views\BaseMenuView;
use Views\BaseView;
use Views\Devices\DeviceAddModalView;
use Views\Devices\DeviceEditModalView;
use Views\Devices\DeviceEyeModalView;
use Views\Devices\DevicesView;

class DevicesController extends BaseController
{

    const DEVICES_PAGING = "devices paging";
    const CREATE_DEVICE = "create device";
    const CREATE_DEVICE_OPEN_MODAL = "create device open modal";
    const DELETE_DEVICE = "delete device";
    const OPEN_UPDATE_DEVICE = "open update device";
    const UPDATE_DEVICE = "update device";
    const OPEN_EYE_DEVICE = "open eye device";
    const GET_DEVICES_INFO = "get devices info";



    /**
     * @param array $get
     * @return BaseView
     */
    protected function defaultProcess($get = [])
    {
        return $this->makeViewAndData(1);
    }

    public function control($action, $params = [], $get = [])
    {
        switch ($action){
            case static::UPDATE_DEVICE:
                $device = new Device();
                if(isset($params['company']))
                    $device->setCompany($params['company']);
                else
                    $device->setCompany(Core::getInstance()->getUser()->getCompany());
                $device->setId($params['id']);
                $device->setAddress($params['address']);
                $device->setCoords($params['coords']);
                $device->setRegion($params['region']);
                $loader = new DevicesLoader();
                $loader->updateById($device);
                break;
            case static::DELETE_DEVICE:
                $loader = new DevicesLoader();
                $loader->deleteById($params['id']);
                break;
            case static::CREATE_DEVICE:
                $device = new Device();
                if(isset($params['company']))
                    $device->setCompany($params['company']);
                else
                    $device->setCompany(Core::getInstance()->getUser()->getCompany());
                $device->setId($params['id']);
                $device->setAddress($params['address']);
                $device->setCoords($params['coords']);
                $device->setRegion($params['region']);
                $loader = new DevicesLoader();
                $loader->create($device);
                break;
            case static::CREATE_DEVICE_OPEN_MODAL:
                $view = new DeviceAddModalView();
                $loader = new RegionLoader();
                $view->setRegions($loader->getAll());
                $loader = new CompanyLoader();
                $view->setCompanies($loader->getAll());
                $view->draw();
                break;
            case static::DEVICES_PAGING:
                $pageNumber = $params['page_number'];
                $filters = [];
                $notFilters = [];
                if($params['company_filter'] != 'all')
                    $filters['company'] = $params['company_filter'];
                if($params['status_filter'] != 'all'){
                    if($params['status_filter'] == "0")
                        $filters["errors"] = "-";
                    else
                        $notFilters["errors"] = "-";
                }

                $view = $this->makeViewAndData($pageNumber, $filters, $notFilters);
                ob_start();
                if(!isset($_GET['view']) || $_GET['view'] == 'list')
                    $view->getPaginationView()->drawPaging();
                $paginationHTML = ob_get_contents();
                ob_clean();
                $view->drawTable();
                $tableHTML = ob_get_contents();
                ob_end_clean();
                echo json_encode(["pagination"=>$paginationHTML, "devices"=>$tableHTML]);
                break;
            case static::OPEN_UPDATE_DEVICE:
                $view = new DeviceEditModalView();
                $loader = new DevicesLoader();
                $view->setDevice($loader->getByData(["id" => $params['id']]));
                $loader = new RegionLoader();
                $view->setRegions($loader->getAll());
                $loader = new CompanyLoader();
                $view->setCompanies($loader->getAll());
                $view->draw();
                break;
            case static::OPEN_EYE_DEVICE:
                $view = new DeviceEyeModalView();
                $loader = new DevicesLoader();
                $view->setDevice($loader->getByData(["id" => $params['id']]));
                $view->draw();
                break;
            case static::GET_DEVICES_INFO:
                foreach ($params as $k => $v){
                    if($v == 'all'){
                        unset($params[$k]);
                    }
                }
                $devicesInfo = $this->makeDevicesWithOutPagination($params);
                $formattedDevicesInfo = [];
                foreach ($devicesInfo as $device) {
                    $formattedDevicesInfo[] = $device->makeData();
                }

                echo json_encode($formattedDevicesInfo);
                break;
        }
    }

    private function makeDevicesWithOutPagination($filters = []){
        $loader = new DevicesLoader();
        $dataFilters = [];

        switch (Core::getInstance()->getUser()->getRole()){
            case User::SUPER_ADMIN:

                break;
            default:
                $dataFilters['company'] = Core::getInstance()->getUser()->getCompany();
                break;
        }
        $dataFilters = array_merge($dataFilters, $filters);
        if(empty($dataFilters)){
            $devices = $loader->getAll();
        }else{
            $devices = $loader->getAllByData($dataFilters);
        }
        return $devices;
    }

    private function makeViewAndData($pageNumber, $filters = [], $notFilters = []){
        $loader = new DevicesLoader();
        $dataFilters = [];

        switch (Core::getInstance()->getUser()->getRole()){
            case User::SUPER_ADMIN:

                break;
            default:
                $dataFilters['company'] = Core::getInstance()->getUser()->getCompany();
                break;
        }
        $dataFilters = array_merge($dataFilters, $filters);
        if(empty($dataFilters) && empty($notFilters)){
            $devices = $loader->getAllPagination(($pageNumber-1)*Pagination::PAGING, Pagination::PAGING);
            $count = $loader->getAllCount();
        }else{
            $devices = $loader->getAllByDataPagination($dataFilters, $notFilters, ($pageNumber-1)*Pagination::PAGING, Pagination::PAGING);
            $count = $loader->getAllByDataCount($dataFilters, $notFilters);
        }
        $pagination = new Pagination($pageNumber, $count%Pagination::PAGING == 0 ? (int)($count/Pagination::PAGING) : (int)($count/Pagination::PAGING)+1);
        $view = new DevicesView();
        $view->setPagination($pagination);
        $view->setDevices($devices);
        $viewPage = isset($_GET['view']) ? $_GET['view'] : 'list';
        $view->setPageView($viewPage);
        $view->setPhpSelf(substr($_SERVER['PHP_SELF'], 0, -9));
        $loader = new CompanyLoader();
        $view->setCompanies($loader->getAll());
        return $view;
    }
}