<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 15.02.19
 * Time: 23:57
 */

namespace Views\Users;


use Models\Companies\Company;
use Models\Regions\Region;
use Models\Users\User;
use System\Main\Core;

class UsersModalView
{

    const ADD_USER = "add user";
    const EDIT_USER = "edit user";

    /**
     * @var Company[] $companies
     */
    private $companies;
    /**
     * @var Region[] $regions
     */
    private $regions;

    /**
     * @param mixed $companies
     */
    public function setCompanies($companies)
    {
        $this->companies = $companies;
    }

    /**
     * @param mixed $regions
     */
    public function setRegions($regions)
    {
        $this->regions = $regions;
    }



    private function isSuperAccess(){
        return Core::getInstance()->getUser()->getRole() == User::SUPER_ADMIN;
    }

    public function draw($type, $params=[]){
        if($type == static::ADD_USER){?>
            <form onsubmit="return createUser($(this).serializeArray())">
        <?}?>
        <?if($type == static::EDIT_USER){?>
            <form onsubmit="return updateUser($(this).serializeArray())">
        <?}
        switch ($type) {
            case static::ADD_USER:
                ?><h2>Добавление пользователя</h2><?
                break;
            case static::EDIT_USER:
                ?>
                <h2>Редактирование пользователя</h2>
                <input name="id" type="hidden" value="<?=$params['id']?>">
                <?
                break;
        }
        ?>
            <label for="users-role-select" class="label users-modal-label">Роль:</label>
            <select name="role" required id="users-role-select" class="select">
                <option value=""></option>
                <?foreach (User::getRolesForAdmins() as $role){?>
                    <option value="<?=$role?>"><?=$role?></option>
                <?}?>
            </select><br><br>
            <?$this->drawCompanyInput();?>
            <br><br>

            <label for="users-region-select" class="label users-modal-label">Регион:</label>
            <select name="region" required id="users-region-select" class="select">
                <option value=""></option>
                <?foreach ($this->regions as $region){?>
                    <option value="<?=$region->getRegion()?>"><?=$region->getRegion()?></option>
                <?}?>
            </select><br><br>

            <label for="users-login" class="label users-modal-label">Логин:</label>
            <input name="login" required type="text" class="input" id="users-login"><br><br>

            <label for="users-password" class="label users-modal-label">Пароль:</label>
            <input name="password" required type="password" class="input" id="users-password"><br><br><br>

            <input required type="submit" value="Сохранить" class="button">
        </form>
        <form onsubmit="return createCompany($(this).serializeArray())">
            <h2>Добавить компанию</h2>
            <label for="company-name" class="label users-modal-label">Название:</label>
            <input name="company" required type="text" class="input" id="company-name"><br><br>
            <input type="submit" value="Добавить" class="button modal-inline-button">
        </form>
        <form onsubmit="return createRegion($(this).serializeArray())">
            <h2>Добавить регион</h2>
            <label for="region-name" class="label users-modal-label">Название:</label>
            <input name="region" required type="text" class="input" id="region-name"><br><br>
            <input type="submit" value="Добавить" class="button modal-inline-button">
        </form>
        <?
    }

    protected function drawCompanyInput(){
        if($this->isSuperAccess()){
            ?>
            <label for="users-company-select" class="label users-modal-label">Компания:</label>
            <select required id="users-company-select" class="select">
                <option value=""></option>
                <?foreach ($this->companies as $company){?>
                    <option value="<?=$company->getCompany()?>"><?=$company->getCompany()?></option>
                <?}?>
            </select>
            <?
        }else{
            ?>
            <label class="label users-modal-label">Компания: </label>
            <label class="label users-modal-label"><?=Core::getInstance()->getUser()->getCompany()?></label>
            <?
        }
    }
}