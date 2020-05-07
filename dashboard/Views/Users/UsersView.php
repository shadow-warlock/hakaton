<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 12.02.19
 * Time: 17:23
 */

namespace Views\Users;


use Models\Regions\Region;
use Models\Support\Pagination;
use Models\Users\User;
use Views\BaseMenuView;
use Views\PaginationView;

class UsersView extends BaseMenuView
{

    private $usersCount;
    /**
     * @var Region[] regions
     */
    private $regions;
    /**
     * @var User[] $users
     */
    private $users;
    /**
     * @var Pagination $pagination
     */
    private $pagination;

    public function __construct()
    {
        parent::__construct();
        $this->setTitle("Пользователи");
    }

    /**
     * @param mixed $usersCount
     */
    public function setUsersCount($usersCount)
    {
        $this->usersCount = $usersCount;
    }


    /**
     * @param Pagination $pagination
     */
    public function setPagination($pagination)
    {
        $this->pagination = $pagination;
        // $this->pagination = new PaginationView($pagination, "usersPaging");
    }

    /**
     * @param Region[] $regions
     */
    public function setRegions($regions)
    {
        $this->regions = $regions;
    }




    /**
     * @param mixed $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }

    public function getPageName()
    {
        return "users";
    }


    public function drawContent()
    {
        ?>

        <div id="toolbar">
            <div class="toolbar__buttons-wrapper">

                <?if($this->user->getRole() == User::SUPER_ADMIN || $this->user->getRole() == User::ADMIN){?>
                    <div class="toolbar__button-item">
                    <input onclick="createUserClick()" type="button" class="toolbar-button toolbar__btn" id="add-user-button"
                        value="Добавить пользователя">
                    </div>
                <?}?>

            </div>
        </div>

        <div id="users-toolbar-container">
            
            <div id="filter-bar" class="filter">
                <div class="filter__title">Фильтрация:</div>
                <div class="filter__block">
                    <span class="filter__blockname">по роли</span>
                    <select id="users-role-filter" onchange="usersPaging(1, $('#users-region-filter').val(), $('#users-role-filter').val())" class="select filter__select">
                        <option value="all">Все роли</option>
                        <?foreach (User::gelAllRoles() as $role){?>
                            <option value="<?=$role?>"><?=$role?></option>
                        <?}?>
                    </select>
                </div>
                <div class="filter__block">
                    <span class="filter__blockname">по региону</span>
                    <select id="users-region-filter" onchange="usersPaging(1, $('#users-region-filter').val(), $('#users-role-filter').val())" class="select filter__select">
                        <option value="all">Все регионы</option>
                        <?foreach ($this->regions as $region){?>
                            <option value="<?=$region->getRegion()?>"><?=$region->getRegion()?></option>
                        <?}?>
                    </select>
                </div>
            </div>

            <div id="users-paging-container">
                <?
                // $this->pagination->drawPaging();
                $this->drawPaging();
                ?>
            </div>

        </div>
        <div id="users-table-container">
            <?=$this->drawUsersTable();?>
        </div>
        <?
    }

    public function drawPaging(){
        $nums = $this->pagination->makeNumbers();
        if(count($nums) == 0)
            return;
        if(count($nums) == 1){
            $this->makeButton($nums[0], $this->pagination->getCurrentPage());
        }else{
            if($nums[0] + 1 == $nums[1]){
                $this->makeButton($nums[0], $this->pagination->getCurrentPage());
            }else{
                $this->makeButton($nums[0], $this->pagination->getCurrentPage(), "paging-button__after");
            }

            for($i = 1; $i < count($nums) - 1; $i++){
                $this->makeButton($nums[$i], $this->pagination->getCurrentPage());
            }

            if($nums[count($nums)  - 2] + 1 == $nums[count($nums)  - 1]){
                $this->makeButton($nums[count($nums) - 1], $this->pagination->getCurrentPage());
            }else{
                $this->makeButton($nums[count($nums) - 1], $this->pagination->getCurrentPage(), "paging-button__before");
            }
        }
    }

    public function drawUsersTable(){
        ?>
            <table id="users-table">
                <thead>
                <tr>
                    <td>id</td>
                    <td>Логин</td>
                    <td>Роль</td>
                    <td>Регион</td>
                    <td>Группа устройств</td>
                    <td>Дата создания</td>
                    <?if($this->user->getRole() == User::SUPER_ADMIN || $this->user->getRole() == User::ADMIN){?>
                        <td>Редактирование</td>
                    <?}?>
                </tr>
                </thead>
                <tbody>
                <?foreach ($this->users as $user){?>
                    <tr>
                        <td><?=$user->getId()?></td>
                        <td><?=$user->getLogin()?></td>
                        <td><?=$user->getRole()?></td>
                        <td><?=$user->getRegion()?></td>
                        <td><?=$user->getCompany()?></td>
                        <td><?=$user->getDateCreate()?></td>
                        <?if($this->user->getRole() == User::SUPER_ADMIN || $this->user->getRole() == User::ADMIN){?>
                            <td class="editor-cell">
                                <i onclick="updateUserClick(<?=$user->getId()?>)" class="fas fa-edit manage-icon" id="edit-user-button"></i>
                                <i onclick="deleteUser(<?=$user->getId()?>)" class="fas fa-trash manage-icon"></i>
                            </td>
                        <?}?>
                    </tr>
                <?}?>
                </tbody>
            </table>
        <?
    }

    public function makeClass($page_num,$cur_page) {
        $class = "";
        switch($page_num) {
            case ($cur_page):
                $class = "paging-button toolbar-button selected";
                break;
            default:
                $class = "paging-button toolbar-button";
                break;
        }
        if(abs($page_num - $cur_page) > 5){
            $class .= " last";
        }
        return $class;
    }

    public function makeButton($page_num, $cur_page, $tmp="") {
        $class_name = $this->makeClass($page_num,$cur_page);
        ?><div onclick='usersPaging(<?=$page_num?>, $("#users-region-filter").val(), $("#users-role-filter").val())' class='<?=$class_name?> <?=$tmp?>'><?=$page_num?></div><?
    }

    public function scriptLoad()
    {
        parent::scriptLoad();
        ?>
        <script src="<?=$this->assets('js/users/paging.js')?>"></script>
        <script src="<?=$this->assets('js/users/create_user.js')?>"></script>
        <?
    }
}