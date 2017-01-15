<?php
namespace cacatuidae\ipRange\traits;
use cacatuidae\ipRange\Module;
use Yii;

/**
 * Class ModuleTrait
 * @package cacatuidae\ipRange\traits
 */
trait ModuleTrait
{
    /**
     * @var Module
     */
    private $_module;

    /**
     * @return null|Module
     */
    public function getModule()
    {
        if ($this->_module === null) {
            $this->_module = Module::getInstance();
            if(!$this->_module)
                $this->_module = Yii::$app->getModule('ipRange');
        }
        return $this->_module;
    }
}
