<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 14.07.18
 * Time: 20:01
 */

namespace Integrator\System\Views;


use Integrator\System\Main\Core;

class UnloginView extends BaseView {



    public function drawMenu()
    {
        ?>

        <?
    }

    public function scriptLoad()
    {
        $this->loadCSS('styles.css', Core::getInstance()->getSystemResPath(), false);
    }

    public function drawContent()
    {
        ?>
        <div class="page_backgr">
            <div style="padding-top: 1vw">
                <div class="row" style="">
                    <div class="col-6">
                        <div class="logo">

                        </div>
                        <div class="brand_name">
                            Integrator
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="site_description">
                            <div class="description_text">
                                Integrator - это система надстроек на AmoCRM, которая позволит вам экономить ваше время, контролировать отдел продаж, всегда иметь самые актуальные данные по ключевым точкам вашего бизнес процесса, не терять клиентов и использовать скрытый потенциал вашей CRM на полную.

                                <div class="register_button" onclick="openLoginModal();">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="product_container container-fluid" >
                <div class="pruzhinka_container">
                </div>
                <div style="background-color: white;">
                    <div class="container-fluid">
                        <div class="gears_header">Наша продукция</div>
                        <div class="row products_brands">
                            <div class="col product_brand_gear">

                            </div>
                            <div class="col product_brand_gear">

                            </div>
                            <div class="col product_brand_gear">

                            </div>
                        </div>
                        <div class="row products_infos">
                            <div class="col">
                                <div class="product_info">
                                    Информация о первом модуле Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias hic labore neque officia provident recusandae sapiente tempore. Ipsum minus molestias nemo nisi placeat quae repellendus, rerum tempora temporibus vel vero?
                                </div>
                                <div class="get_module">
                                    Попробовать бесплатно
                                </div>
                            </div>
                            <div class="col">
                                <div class="product_info">
                                    Информация о втором модуле Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias hic labore neque officia provident recusandae sapiente tempore. Ipsum minus molestias nemo nisi placeat quae repellendus, rerum tempora temporibus vel vero?
                                </div>
                                <div class="get_module">
                                    Попробовать бесплатно
                                </div>
                            </div>
                            <div class="col" >
                                <div class="product_info">
                                    Информация о третьем модуле Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias hic labore neque officia provident recusandae sapiente tempore. Ipsum minus molestias nemo nisi placeat quae repellendus, rerum tempora temporibus vel vero?
                                </div>
                                <div class="get_module">
                                    Попробовать бесплатно
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid footer" style="height:15vh;">
            </div>

        </div>
        <?
    }
}