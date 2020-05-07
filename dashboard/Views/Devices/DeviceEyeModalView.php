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
use Models\Users\User;
use System\Main\Core;

class DeviceEyeModalView
{

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


    public function draw(){
        ?>
        <h2>Информация об устройстве</h2>
        <label class="label devices-modal-label">Имя:</label>
        <label class="label users-modal-text"><?=$this->device->getId()?></label><br><br>

        <label class="label devices-modal-label">Группа устройств:</label>
        <label class="label users-modal-text"><?=$this->device->getCompany()?></label><br><br>

        <label class="label devices-modal-label">Адрес:</label>
        <label class="label users-modal-text"><?=$this->device->getAddress()?></label><br><br>

        <label class="label devices-modal-label">Координаты:</label>
        <label class="label users-modal-text"><?=$this->device->getCoords()?></label><br><br>

        <label class="label devices-modal-label">Время последней отчистки:</label>
        <label class="label users-modal-text"><?=$this->device->getDateClearBasket()?></label><br><br>

        <label class="label devices-modal-label">Заполненность:</label>
        <label class="label users-modal-text"><?=$this->device->getOccupancy()?>%</label><br><br>

        <?
    }
}