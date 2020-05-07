<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 11.08.18
 * Time: 20:18
 */


namespace System\Views;


use System\Main\Core;

abstract class BaseView extends AbstractView
{

    const MODULES = [
        'jquery' => 'html/base/jquery.html',
        'bootstrap' => 'html/base/bootstrap.html',
        'base' => 'html/base/basehtml.html'
    ];

    private $icon = 'logo.png';
    private $title = 'Integrator';

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
        $this->icon = $icon;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    public abstract function drawMenu();

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
            </body>
        </html>
        <?
    }

    public abstract function scriptLoad();

    public abstract function drawContent();

}