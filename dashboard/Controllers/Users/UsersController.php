<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 12.02.19
 * Time: 17:14
 */

namespace Controllers\Users;


use Controllers\BaseController;
use Loaders\Companies\CompanyLoader;
use Loaders\Regions\RegionLoader;
use Loaders\Users\UserLoader;
use Models\Companies\Company;
use Models\Regions\Region;
use Models\Support\Pagination;
use Models\Users\User;
use System\Main\Core;
use Views\BaseView;
use Views\EmptyMessageView;
use Views\NotAccessPageView;
use Views\Users\CreateUsersModalView;
use Views\Users\EditUsersModalView;
use Views\Users\UsersModalView;
use Views\Users\UsersView;

class UsersController extends BaseController
{


    const CREATE_USER = "create user";
    const USERS_PAGING = "users paging";
    const CREATE_USER_MODAL_OPEN = "create user modal open";
    const UPDATE_USER_MODAL_OPEN = "update user modal open";
    const CREATE_COMPANY = "create company";
    const CREATE_REGION = "create region";
    const DELETE_USER = "delete user";
    const UPDATE_USER = "update user";



    /**
     * @param array $get
     * @return BaseView
     */
    protected function defaultProcess($get = [])
    {
        if(Core::getInstance()->getUser()->getRole() == User::TECHNICIAN){
            $view = new NotAccessPageView();
            return $view;
        }
        return $this->makeViewAndData(1);
    }

    public function control($action, $params = [], $get = [])
    {
        switch ($action){
            case static::CREATE_USER:
                $user = new User();
                if(isset($params['company']))
                    $user->setCompany($params['company']);
                else
                    $user->setCompany(Core::getInstance()->getUser()->getCompany());
                $user->setDateCreate(date("d.m.Y H:i:s"));
                $user->setLogin($params['login']);
                $user->setEmail($params['email']);
                $user->setPassword(sha1($params['password']));
                $user->setRegion($params['region']);
                $user->setRole($params['role']);
                $loader = new UserLoader();
                $loader->create($user);
                break;
            case static::UPDATE_USER:
                $user = new User();
                if(isset($params['company']))
                    $user->setCompany($params['company']);
                else
                    $user->setCompany(Core::getInstance()->getUser()->getCompany());
                $user->setId($params['id']);
                $user->setEmail($params['email']);
//                $user->setDateCreate(date("d.m.Y H:i:s"));
                $user->setLogin($params['login']);
                if($params['password'] != User::DEFAULT_PASSWORD){
                    $user->setPassword(sha1($params['password']));
                }
                $user->setRegion($params['region']);
                $user->setRole($params['role']);
                $loader = new UserLoader();
                $loader->updateById($user);
                break;
            case static::USERS_PAGING:
                $pageNumber = $params['page_number'];
                $filters = [];
                if($params['region_filter'] != 'all')
                    $filters['region'] = $params['region_filter'];
                if($params['role_filter'] != 'all')
                    $filters['role'] = $params['role_filter'];
                $view = $this->makeViewAndData($pageNumber, $filters);
                ob_start();
                $view->drawPaging();
                $paginationHTML = ob_get_contents();
                ob_clean();
                $view->drawUsersTable();
                $tableHTML = ob_get_contents();
                ob_end_clean();
                echo json_encode(["pagination"=>$paginationHTML, "users"=>$tableHTML]);
                break;
            case static::CREATE_USER_MODAL_OPEN:
                $view = new CreateUsersModalView();
                $loader = new RegionLoader();
                $view->setRegions($loader->getAll());
                $loader = new CompanyLoader();
                $view->setCompanies($loader->getAll());
                $view->draw();
                break;
            case static::UPDATE_USER_MODAL_OPEN:
                $view = new EditUsersModalView();
                $loader = new RegionLoader();
                $view->setRegions($loader->getAll());
                $loader = new CompanyLoader();
                $view->setCompanies($loader->getAll());
                $loader = new UserLoader();
                $view->draw($loader->getByData(["id"=>$params['id']]));
                break;
            case static::CREATE_COMPANY:
                $company = new Company();
                $company->setCompany($params['company']);
                $loader = new CompanyLoader();
                $loader->create($company);
                break;
            case static::CREATE_REGION:
                $region = new Region();
                $region->setRegion($params['region']);
                $loader = new RegionLoader();
                $loader->create($region);
                break;
            case static::DELETE_USER:
                $loader = new UserLoader();
                $loader->deleteById($params['id']);
                break;
        }
    }

    private function makeViewAndData($pageNumber, $filters = []){
        $loader = new UserLoader();
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
            $users = $loader->getAllPagination(($pageNumber-1)*Pagination::PAGING, Pagination::PAGING);
            $count = $loader->getAllCount();
        }else{
            $users = $loader->getAllByDataPagination($dataFilters, ($pageNumber-1)*Pagination::PAGING, Pagination::PAGING);
            $count = $loader->getAllByDataCount($dataFilters);
        }
        $pagination = new Pagination($pageNumber, $count%Pagination::PAGING == 0 ? (int)($count/Pagination::PAGING) : (int)($count/Pagination::PAGING)+1);
        $view = new UsersView();
        $view->setPagination($pagination);
        $view->setUsersCount($count);
        $view->setUsers($users);
        $loader = new RegionLoader();
        $view->setRegions($loader->getAll());
        return $view;
    }
}