<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 20.02.19
 * Time: 17:22
 */

namespace Views\Devices;


use Models\Companies\Company;
use Models\Regions\Region;

class DeviceAddModalView
{
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
        <h2>Добавление устройства</h2>
        <form onsubmit="return addDevice($(this).serializeArray())">
            <label for="device-name" class="label users-modal-label">Имя:</label>
            <input name="id" type="number" required class="input" id="device-name"><br><br>

            <label for="device-company-select" class="label users-modal-label">Группа устройств:</label>
            <select name="company" required id="device-company-select" class="select">
                <option value=""></option>
                <?foreach ($this->companies as $company){?>
                    <option value="<?=$company->getCompany()?>"><?=$company->getCompany()?></option>
                <?}?>
            </select><br><br>

            <label for="device-region-select" class="label users-modal-label">Регион:</label>
            <select name="region" required id="device-region-select" class="select">
                <option value=""></option>
                <?foreach ($this->regions as $region){?>
                    <option value="<?=$region->getRegion()?>"><?=$region->getRegion()?></option>
                <?}?>
            </select><br><br>

            <label for="device-address" class="label users-modal-label">Адрес:</label>
            <input name="address" required type="text" class="input" id="device-address"><br><br>

            <label for="device-coords" class="label users-modal-label">Координаты:</label>
            <input name="coords" required type="text" class="input" id="device-coords"><br><br><br>

            <input type="submit" value="Сохранить" class="button">
        </form>
        <?
    }
}