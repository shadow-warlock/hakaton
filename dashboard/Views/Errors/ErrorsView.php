<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 27.02.19
 * Time: 20:29
 */

namespace Views\Errors;


use Models\Devices\Error;
use Models\Users\User;
use System\Main\Core;
use Views\BaseView;

class ErrorsView extends BaseView
{
    /**
     * @var Error[] $errors
     */
    private $errors;

    public function __construct()
    {
        parent::__construct();
        $this->setTitle("Ошибки");
    }

    /**
     * @param Error[] $errors
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }


    public function drawContent()
    {
        ?>
        <div class="margin-all">
            <div id="errors-table-container">
                <table id="errors-table">
                    <thead>
                    <tr>
                        <td>ID устройства</td>
                        <td>Название ошибки</td>
                        <td>Время ошибки</td>
                        <td>Состояние ошибки</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?foreach ($this->errors as $error){?>
                        <tr>
                            <td><?=$error->getDeviceId()?></td>
                            <td><?=$error->getError()?></td>
                            <td><?=$error->getTime()?></td>
                            <?
                            $onclick = "";
                            if(Core::getInstance()->getUser()->getRole() == User::SUPER_ADMIN || Core::getInstance()->getUser()->getRole() == User::ADMIN){
                                $onclick = "onclick='fixProblem(".$error->getId().")'";
                            }
                            ?>
                            <?if($error->getState()){?>
                                <td <?=$onclick?> class="green-text">Решена</td>
                            <?}else{?>
                                <td <?=$onclick?> class="red-text">Не решена</td>
                            <?}?>
                        </tr>
                    <?}?>
                    </tbody>
                </table>
            </div>
        </div>
        <?
    }

    public function scriptLoad()
    {
        parent::scriptLoad(); // TODO: Change the autogenerated stub
        ?><script src="<?=$this->assets('js/errors/errors.js')?>"></script><?
    }
}