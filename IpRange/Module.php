<?php
namespace cacatuidae\ipRange;

use Yii;

/**
 * Class Module
 * @package cacatuidae\ipRange
 */
class Module extends \yii\base\Module
{
    /**
     * @var string
     */
    public $controllerNamespace = 'cacatuidae\ipRange\controllers';
    public $defaultRoute = 'index';
    public $diskStoragePath = null;

    public function init()
    {
        parent::init();
        $this->setViewPath('@ipRange/views');
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['cacatuidae/ipRange/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@ipRange/messages',
            'fileMap' => [
                'cacatuidae/ipRange/ip_range' => 'ip_range.php'
            ]
        ];
    }

    /**
     * @param $message
     * @param string $category
     * @param array $params
     * @param null $language
     * @return string
     */
    public static function t($message, $category = 'ip_range', $params = [], $language = null)
    {
        return Yii::t('cacatuidae/ipRange/' . $category, $message, $params, $language);
    }
}
