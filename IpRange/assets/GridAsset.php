<?php
namespace cacatuidae\ipRange\assets;

use yii\web\AssetBundle;
use yii\web\View;

class GridAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@ipRange/assets/grid';

    /**
     * @var array
     */
    public $js = ['js/grid.js'];

    /**
     * @var array
     */
    public $css = ['css/grid.css'];

    /**
     * @var array
     */
    public $depends = ['\yii\web\JqueryAsset', '\yii\bootstrap\BootstrapAsset'];

    public function init() {
        $this->jsOptions['position'] = View::POS_HEAD;
        parent::init();
    }
}