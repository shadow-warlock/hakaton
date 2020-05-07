<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 11.08.18
 * Time: 20:18
 */


namespace Views;


use System\Main\Core;

class BaseView extends AbstractView
{

    const MODULES = [
        'jquery' => 'html/base/jquery.html',
        'bootstrap' => 'html/base/bootstrap.html',
        'base' => 'html/base/basehtml.html'
    ];

    private $icon = null;
    private $title = 'Пандоматы';
    protected $user;

    /**
     * BaseView constructor.
     */
    public function __construct()
    {
        $this->user = Core::getInstance()->getUser();
    }

    /**
     * @param string $icon
     */

    public function setIcon($icon)
    {
        if($icon != null)
            $this->icon = $icon;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function draw()
    {
        ?>
        <html>
            <head>
                <?
                foreach (static::MODULES as $module) {
                    echo file_get_contents($this->assets($module));
                }
                ?>
                <link rel="SHORTCUT ICON" href="<?= $this->assets($this->icon)?>" type="image/png">
                <link href="https://fonts.googleapis.com/css?family=PT+Sans" rel="stylesheet">
                <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
                <title><?= $this->title ?></title>
                <?php
                $this->scriptLoad();
                ?>
            </head>
            <body>
                <?php
                $this->drawMenu();
                ?>
                <div id="integrator_content">
                    <?$this->drawContent(); ?>
                </div>
                <div class="modal-back" id="my-modal">
                    <div class="modal">
                        <div onclick="modal.close()" class="close-modal" id="close-my-modal">x</div>
                        <div class="modal-content-container">
                        </div>
                    </div>
                </div>
                <script>
                    $('body').on('click','#my-modal', function (event) {
                        if ($(event.target).closest('.modal').length)
                            return;
                        modal.close();
                    });
                    $('body').keydown(function(eventObject){
                        if (eventObject.which == 27){
                            modal.close();
                        }
                    });
                </script>
            </body>
        </html>
        <?
    }


    public function drawMenu()
    {
        // TODO: Implement drawMenu() method.
    }

    public function scriptLoad()
    {
        ?>
        <link rel="stylesheet" href="<?=$this->assets("css/style.css");?>">
        <script src="<?=$this->assets("js/my_ajax.js")?>"></script>
        <script src="<?=$this->assets("js/modal.js")?>"></script>
        <script src="<?=$this->assets("js/form/serialize_convert.js")?>"></script>
<!--        <script src="--><?//=$this->assets("js/datepicker.min.js")?><!--"></script>-->
<!--        <link rel="stylesheet" href="--><?//=$this->assets("css/datepicker.min.css")?><!--">-->

        <?
    }

    public function drawContent()
    {
        // TODO: Implement drawContent() method.
    }

}