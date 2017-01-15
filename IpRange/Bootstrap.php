<?php
namespace cacatuidae\ipRange;

use cacatuidae\ipRange\controllers\IndexController;
use cacatuidae\ipRange\interfaces\IProvider;
use cacatuidae\ipRange\interfaces\ISearchModel;
use cacatuidae\ipRange\interfaces\IUploadModel;
use cacatuidae\ipRange\methods\widgets\ExtractsIpWidget;
use cacatuidae\ipRange\search\ArraySearchModel;
use cacatuidae\ipRange\interfaces\IReader;
use cacatuidae\ipRange\interfaces\IStorage;
use cacatuidae\ipRange\model\BaseUploadModel;
use cacatuidae\ipRange\provider\ArrayProvider;
use cacatuidae\ipRange\reader\CsvReader;
use cacatuidae\ipRange\storage\DiskSessionStorage;
use yii\base\BootstrapInterface;
use Yii;
use yii\base\Module;
use yii\di\Container;
use yii\web\Application as WebApp;

class Bootstrap implements BootstrapInterface
{
    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        // Устанавливаем алиас для путей к модулю
        //Yii::setAlias('ipRange', '@vendor/cacatuidae/ipRange');
        Yii::setAlias('ipRange', realpath(__DIR__));

        /* @var $module Module */
        if ($app instanceof WebApp && $module = Yii::$app->getModule('ipRange')) {
            $moduleId = $module->id;
            $app->getUrlManager()->addRules([
                'iprange' => $moduleId . '/index/index',
                'iprange/method/<method:\w+>' => $moduleId . '/index/method',
            ], false);
        }
        Yii::$container->set('IpRangeExtractsIpWidget', ExtractsIpWidget::class);
        Yii::$container->set('IpRangeProvider', ArrayProvider::class);
        Yii::$container->set('IpRangeStorage', DiskSessionStorage::class);
        Yii::$container->set('IpRangeReader', CsvReader::class);
        Yii::$container->set('IpRangeSearchModel', function(Container $container, array $params, array $config) {
            /* @var $reader IReader */
            $reader = $container->get('IpRangeReader');
            /* @var $storage IStorage */
            $storage = $container->get('IpRangeStorage');
            /* @var $provider IProvider */
            $provider = $container->get('IpRangeProvider');
            $widgets = ['IpRangeExtractsIpWidget'];
            return new ArraySearchModel($storage, $reader, $provider, $widgets, $config);
        });
        Yii::$container->set('IpRangeUploadModel', function(Container $container, array $params, array $config) {
            /* @var $reader IReader */
            $reader = $container->get('IpRangeReader');
            /* @var $storage IStorage */
            $storage = $container->get('IpRangeStorage');
            return new BaseUploadModel($storage, $reader, $config);
        });
        Yii::$container->set(IndexController::class, function(Container $container, array $params, array $config) {
            /* @var $uploadModel IUploadModel */
            /* @var $searchModel ISearchModel */
            /* @var $id string */
            /* @var $module \cacatuidae\ipRange\Module */
            list($id, $module) = $params;
            $uploadModel = $container->get('IpRangeUploadModel');
            $searchModel = $container->get('IpRangeSearchModel');
            return new IndexController($id, $module, $uploadModel, $searchModel, $config);
        });
    }
}
