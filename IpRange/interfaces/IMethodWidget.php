<?php
namespace cacatuidae\ipRange\interfaces;
use yii\base\Widget;

/**
 * Interface IMethodWidget
 * @package cacatuidae\ipRange\interfaces
 * @mixin Widget
 */
interface IMethodWidget
{
    /**
     * @return string
     */
    public function getMethodName();
}