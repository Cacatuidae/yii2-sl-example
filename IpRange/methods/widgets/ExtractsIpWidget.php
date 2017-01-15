<?php
namespace cacatuidae\ipRange\methods\widgets;

use cacatuidae\ipRange\interfaces\IMethodWidget;
use yii\base\Widget;
use yii\helpers\Url;

/**
 * Class ExtractsIpWidget
 * @package cacatuidae\ipRange\methods\widgets
 */
class ExtractsIpWidget extends Widget implements IMethodWidget
{
    /**
     * @var string
     */
    private $methodName = 'ExtractsIp';

    /**
     * @return string
     */
    final public function getMethodName()
    {
        return $this->methodName;
    }

    /**
     * @return string
     */
    public function run()
    {
        parent::run();

        return $this->render('extract_ip/index', [
            'url' => Url::toRoute(['method/index', 'method' => $this->getMethodName()])]);
    }
}