<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 14.07.18
 * Time: 20:01
 */

namespace Views;


class UnloginView extends BaseView {


    /**
     * UnloginView constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTitle("Авторизация");
    }

    public function scriptLoad()
    {
        parent::scriptLoad(); // TODO: Change the autogenerated stub
        ?>
        <script src="<?=$this->assets('js/auth/auth.js');?>"></script>
        <?
    }

    public function drawContent()
    {
        ?>
        <div id="auth-container" class="auth">
            <div id="auth-form-container" class="auth__form">
                <form onsubmit="return authSubmit()" id="auth-form" method="post">
                    <h2 id="auth-form-name" class="auth__title">
                        Сервис-монитор умных мусорных контейнеров
                        <!-- Онлайн-сервис по управлению  сетью устройств сбора ТБО -->
                    </h2>
                    <label for="auth-login-input" class="auth__label">Логин:</label>
                    <input required type="text" name="login" id="auth-login-input" class="auth__input">
                    <br><br>
                    <label for="auth-pass-input" class="auth__label">Пароль:</label>
                    <input required type="password" name="password" id="auth-pass-input" class="auth__input">
                    <br><br>
                    <input type="submit" id="#auth-submit" value="Авторизоваться" class="auth__button">
                </form>
            </div>
        </div>
        <?
    }
}