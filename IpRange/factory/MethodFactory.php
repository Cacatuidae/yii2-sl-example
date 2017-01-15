<?php
namespace cacatuidae\ipRange\factory;

use cacatuidae\ipRange\interfaces\IMethod;
use cacatuidae\ipRange\interfaces\IMethodFactory;
use yii\base\Exception;
use Yii;
use yii\base\Object;
use yii\helpers\ArrayHelper;

/**
 * Class MethodFactory
 * @package cacatuidae\ipRange\factory
 */
class MethodFactory extends Object implements IMethodFactory
{
    /**
     * @var string
     */
    private static $namespace = 'cacatuidae\\ipRange\\methods\\';

    /**
     * @param $method
     * @param array $config
     * @return IMethod
     * @throws Exception
     */
    final public function factory($method, array $config = [])
    {
        $className = self::$namespace . trim($method) . 'Method';
        /* @var $object IMethod */
        $object =  Yii::createObject(ArrayHelper::merge(['class' => $className], $config));
        if(!$object instanceof IMethod)
            throw new Exception("Класс '{$className}' должен имплементировать итерфейс " . IMethod::class);
        return $object;
    }
}