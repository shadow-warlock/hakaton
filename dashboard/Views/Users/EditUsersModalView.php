<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 16.02.19
 * Time: 21:31
 */

namespace Views\Users;


use Models\Companies\Company;
use Models\Regions\Region;
use Models\Users\User;
use System\Main\Core;

class EditUsersModalView
{

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

    /**
     * @param User $user
     */
    public function draw($user){
        ?>
        <form onsubmit="return updateUser($(this).serializeArray())">
            <h2>Редактировать пользователя</h2>
            <input name="id" value="<?=$user->getId()?>" type="hidden">
            <label for="users-role-select" class="label users-modal-label">Роль:</label>
            <select name="role" required id="users-role-select" class="select">
                <?foreach (User::getRolesForAdmins() as $role){?>
                    <?if($user->getRole() == $role){?>
                        <option selected value="<?=$role?>"><?=$role?></option>
                    <?}else{?>
                        <option value="<?=$role?>"><?=$role?></option>
                    <?}?>
                <?}?>
            </select><br><br>
            <?$this->drawCompanyInput($user);?>
            <br><br>

            <label for="users-region-select" class="label users-modal-label">Регион:</label>
            <select name="region" required id="users-region-select" class="select">
                <option value=""></option>
                <?foreach ($this->regions as $region){?>
                    <?if($user->getRegion() == $region->getRegion()){?>
                        <option selected value="<?=$region->getRegion()?>"><?=$region->getRegion()?></option>
                    <?}else{?>
                        <option value="<?=$region->getRegion()?>"><?=$region->getRegion()?></option>
                    <?}?>
                <?}?>
            </select><br><br>

            <label for="users-login" class="label users-modal-label">Логин:</label>
            <input value="<?=$user->getLogin()?>" name="login" required type="text" class="input" id="users-login"><br><br>

            <label for="users-email" class="label users-modal-label">Email:</label>
            <input value="<?=$user->getEmail()?>" name="email" required type="text" class="input" id="users-email"><br><br>


            <label for="users-password" class="label users-modal-label">Пароль:</label>
            <input value="*****" name="password" required type="password" class="input" id="users-password"><br><br><br>

            <input  required type="submit" value="Сохранить" class="button">
        </form>
        <?
    }

    /**
     * @param User $user
     */
    protected function drawCompanyInput($user){
        if($this->isSuperAccess()){
            ?>
            <label for="users-company-select" class="label users-modal-label">Группа устройств:</label>
            <select name="company" required id="users-company-select" class="select">
                <?foreach ($this->companies as $company){?>
                    <?if($user->getCompany() == $company->getCompany()){?>
                        <option selected value="<?=$company->getCompany()?>"><?=$company->getCompany()?></option>
                    <?}else{?>
                        <option value="<?=$company->getCompany()?>"><?=$company->getCompany()?></option>
                    <?}?><?}?>
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