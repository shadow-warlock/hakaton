<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 20.02.19
 * Time: 17:22
 */

namespace Views\Devices;


use Models\Companies\Company;
use Models\Devices\Device;
use Models\Regions\Region;

class DeviceEditModalView
{
    /**
     * @var Company[] $companies
     */
    private $companies;

    /**
     * @var Device
     */
    private $device;

    /**
     * @param Device $device
     */
    public function setDevice($device)
    {
        $this->device = $device;
    }





    /**
     * @param Company[] $companies
     */
    public function setCompanies($companies)
    {
        $this->companies = $companies;
    }

    /**
     * @var Region[] $regions
     */
    private $regions;

    /**
     * @param Region[] $regions
     */
    public function setRegions($regions)
    {
        $this->regions = $regions;
    }




    public function draw(){
        ?>
        <h2>Редактирование устройства</h2>
        <form onsubmit="return updateDevice($(this).serializeArray())">
            <label for="device-name" class="label users-modal-label">Имя:</label>
            <label for="device-name" class="label users-modal-label">№<?=$this->device->getId()?></label>
            <input name="id" type="hidden" value="<?=$this->device->getId()?>" class="input" id="device-name"><br><br>

            <label for="device-company-select" class="label users-modal-label">Группа устройств:</label>
            <select name="company" id="device-company-select" class="select">
                <?foreach ($this->companies as $company){?>
                    <?if($this->device->getCompany() == $company->getCompany()){?>
                        <option selected value="<?=$company->getCompany()?>"><?=$company->getCompany()?></option>
                    <?}else{?>
                        <option value="<?=$company->getCompany()?>"><?=$company->getCompany()?></option>
                    <?}?>
                <?}?>
            </select><br><br>

            <label for="device-region-select" class="label users-modal-label">Регион:</label>
            <select name="region" id="device-region-select" class="select">
                <?foreach ($this->regions as $region){?>
                    <?if($this->device->getRegion() == $region->getRegion()){?>
                        <option selected value="<?=$region->getRegion()?>"><?=$region->getRegion()?></option>
                    <?}else{?>
                        <option value="<?=$region->getRegion()?>"><?=$region->getRegion()?></option>
                    <?}?>
                <?}?>
            </select><br><br>

            <label for="device-address" class="label users-modal-label">Адрес:</label>
            <input value="<?=$this->device->getAddress()?>" name="address" type="text" class="input" id="device-address"><br><br>


            <label for="device-coords" class="label users-modal-label">Координаты:</label>
            <input value="<?=$this->device->getCoords()?>" name="coords" type="text" class="input" id="device-coords"><br><br><br>

            <input type="submit" value="Сохранить" class="button">
        </form>
        <?
    }
}