<?php
namespace cacatuidae\ipRange\methods;

use cacatuidae\ipRange\interfaces\ISearchModel;
use Yii;

/**
 * Class ExtractsIpMethod
 * @package cacatuidae\ipRange\methods
 */
class ExtractsIpMethod extends BaseMethod
{
    /**
     * @return string
     */
    public function run()
    {
        /* @var $searchModel ISearchModel */
        $searchModel = Yii::$container->get('IpRangeSearchModel');
        $searchModel->setPageSize(false);
        $searchModel->setFilterData($this->params);
        $provider = $searchModel->runProvider();

        $ips = [];
        foreach ($provider->getModels() as $row)
            $ips[] = $row['ip'];

        $filename = 'ips_' . Yii::$app->formatter->asDatetime(time(), 'php:Y-m-d_H-i-s') . '.txt';
        Yii::$app->response->setDownloadHeaders($filename, 'text/plain');

        return implode("\r\n", $ips);
    }
}