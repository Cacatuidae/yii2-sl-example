<?php
namespace cacatuidae\ipRange\methods;

use cacatuidae\ipRange\interfaces\IMethod;
use yii\base\Object;

/**
 * Class BaseMethod
 * @package cacatuidae\ipRange\methods
 */
abstract class BaseMethod extends Object implements IMethod
{
    /**
     * @var array
     */
    protected $params = [];

    /**
     * @param array $params
     * @return bool
     */
    public function setParams(array $params)
    {
        $this->params = $params;
        return true;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }
}