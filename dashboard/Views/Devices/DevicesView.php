<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 19.02.19
 * Time: 22:34
 */

namespace Views\Devices;


use Models\Companies\Company;
use Models\Devices\Device;
use Models\Support\Pagination;
use Models\Users\User;
use System\Main\Core;
use Views\BaseMenuView;
use Views\PaginationView;

class DevicesView extends BaseMenuView
{



    const OCCUPANCY_RED_PERCENT = 70;

    private $pageView;

    public function __construct()
    {
        parent::__construct();
        $this->setTitle("Устройства");
    }

    public function setPageView($pageView) {
        $this->pageView = $pageView;
    }

    private  $phpSelf;

    public function setPhpSelf($phpSelf) {
        $this->phpSelf = $phpSelf;
    }

    /**
     * @var PaginationView $paginationView
     */
    private $paginationView;

    /**
     * @var Device[]
     */
    private $devices;

    /**
     * @var Company[] $companies
     */
    private $companies;

    /**
     * @param Company[] $companies
     */
    public function setCompanies($companies)
    {
        $this->companies = $companies;
    }


    /**
     * @param Device[] $devices
     */
    public function setDevices($devices)
    {
        $this->devices = $devices;
    }


    /**
     * @param Pagination $pagination
     */
    public function setPagination($pagination)
    {
        $this->paginationView = new PaginationView($pagination, "devicesPaging");
    }

    /**
     * @return PaginationView
     */
    public function getPaginationView()
    {
        return $this->paginationView;
    }


    public function getPageName()
    {
        return "devices";
    }


    public function drawContent()
    {

        $companyFilterHidden = $this->user->getRole() == User::SUPER_ADMIN ? "" : "hidden";
        ?>


        <div class="toolbar">
            <div class="toggle">

                <?= $this->pageView=='list' ? 
                '<div class="toggle__part">
                    <a href="/devices/?view=map" class="toggle__link">Карта</a>    
                </div><div class="toggle__part toggle__part_current">
                    <span>Список</span>    
                </div>' :
                '<div class="toggle__part toggle__part_current">
                    <span>Карта</span>    
                </div><div class="toggle__part">
                    <a href="/devices/?view=list" class="toggle__link">Список</a>
                </div>'?>
            </div>
        
            
            <div class="toolbar__buttons-wrapper">

                <?
                $marginLeft = '';
                $isSuperAdmin = Core::getInstance()->getUser()->getRole() == User::SUPER_ADMIN;
                if($isSuperAdmin && $this->pageView=='list'){
                    $marginLeft = 'class="margin-left"';
                    ?>
                    <div class="toolbar__button-item">
                    <input onclick="openDeviceAddModal()" type="button" class="toolbar-button toolbar__btn" id="add-device-button"
                        value="Добавить устройство">
                    </div>
                <?}?>

                <div class="toolbar__button-item">
                    <a target="_blank" href="../errors/" class="toolbar__link">Журнал ошибок</a>
                </div>
            </div>
        </div>
        

        <!-- <a style="margin-left: 20px" target="_blank" href="../errors/">Журнал ошибок</a><br><br> -->

        <div id="users-toolbar-container">

            <div id="filter-bar" class="filter">
                <div class="filter__title">Фильтрация:</div>
                <div class="filter__block">
                    <?if($companyFilterHidden == ""){?>
                        <span class="filter__blockname">по группе устройств</span>
                    <?}
                    if($this->pageView == 'list'){
                        $onchange = 'onchange="devicesPaging(1)"';
                    }else{
                        $onchange = 'onchange="mapUpd()"';
                    }
                    ?>
                    <select <?=$companyFilterHidden?> id="device-filter-company" <?=$onchange?> class="select filter__select">
                        <option value="all">Все</option>
                        <?foreach ($this->companies as $company){?>
                            <option value="<?=$company->getCompany()?>"><?=$company->getCompany()?></option>
                        <?}?>
                    </select>
                </div>
                <div class="filter__block">
                    <span class="filter__blockname">по наличию ошибок</span>
                    <select id="device-filter-status" <?=$onchange?> class="select filter__select">
                        <option value="all">Все</option>
                        <option value="1">С ошибками</option>
                        <option value="0">Без ошибки</option>
                    </select>
                </div>
            </div>
                
            <div id="users-paging-container">
                <?
                if($this->pageView=='list') {
                    $this->paginationView->drawPaging();
                }
                ?>
            </div>
        </div>
        <div id="devices-content-container">
            <?php
            switch ($this->pageView) {
                case 'map':
                    ?>

                    <div id="map"></div>
                    <?php
                    break;
                case 'list':
                    ?>
                    <div id="users-table-container">
                        <? $this->drawTable()?>
                    </div>
                    <?php
                    break;
            }
            ?>
        </div>
        <?
    }

    public function drawTable(){
        ?>
        <table id="devices-table">
            <thead>
            <tr>
                <td>ID</td>
                <!-- <td>Статус подключения</td> -->
                <td>Группа устройств</td>
                <td>Адрес</td>
                <td>Дата последней отчистки корзины</td>
                <td>Заполненность</td>
                <td>Дата последнего обновления</td>
                <!-- <td>Всего бутылок</td> -->
                <!-- <td>Бутылок за сутки</td> -->
                <td>Ошибки</td>
<!--                --><?//if(Core::getInstance()->getUser()->getRole() == User::SUPER_ADMIN){?>
                    <td>Редактировать</td>
<!--                --><?//}?>
            </tr>
            </thead>
            <tbody>
            <?foreach ($this->devices as $device){
                $tdOccupancyColor = $device->getOccupancy() < static::OCCUPANCY_RED_PERCENT? "green-text":"red-text";
                $tdErrorColor = $device->getErrors() == "-" ? "" : "red-text";
                ?>
                <tr>
                    <td class="table__id">№<?=$device->getId()?></td>
                    <td><?=$device->getCompany()?></td>
                    <td><?=$device->getAddress()?></td>
                    <td><?=$device->getDateClearBasket()?></td>
                    <td class="table__occupancy <?=$tdOccupancyColor?>"><?=$device->getOccupancy()?>%</td>
                    <td><?=$device->getDateLastData()?></td>
                    <td title="<?=$device->getErrors()?>" class="table__error <?=$tdErrorColor?>"><?=$device->getErrors() != "-" ?$device->getErrors():"Без ошибок"?></td>
                    <td class="editor-cell">
                        <i onclick="deviceEye(<?=$device->getId()?>)" class="fas fa-eye manage-icon"></i>
                        <?if(Core::getInstance()->getUser()->getRole() == User::SUPER_ADMIN || Core::getInstance()->getUser()->getRole() == User::ADMIN){?>
                        <i onclick="openUpdateDevice(<?=$device->getId()?>)" class="fas fa-edit manage-icon"></i>
                        <?}?>
                        <?if(Core::getInstance()->getUser()->getRole() == User::SUPER_ADMIN){?>
                            <i onclick="deleteDevice(<?=$device->getId()?>)" class="fas fa-trash manage-icon"></i>
                        <?}?>
                    </td>
                </tr>
            <?}?>
            </tbody>
        </table>
        <?
    }

    public function scriptLoad()
    {
        parent::scriptLoad(); // TODO: Change the autogenerated stub
        ?>
        <script src="https://api-maps.yandex.ru/2.1/?apikey=75482e3d-c289-44f7-84a9-147f30d36ef5&lang=ru_RU"></script>
        <script src="<?=$this->assets("js/devices/paging.js")?>"></script>
        <script src="<?=$this->assets("js/devices/create_device.js")?>"></script>
        <?
        if($this->pageView == 'map') {
            ?>
            <script src="<?=$this->assets("js/devices/draw_map.js")?>"></script>
            <?php
        }
    }
}