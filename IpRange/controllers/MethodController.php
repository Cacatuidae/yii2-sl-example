<?php
namespace cacatuidae\ipRange\controllers;

use cacatuidae\ipRange\interfaces\IMethodFactory;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use cacatuidae\ipRange\Module;
use Yii;
use yii\web\Controller;

/**
 * Class MethodController
 * @package cacatuidae\ipRange\controllers
 */
class MethodController extends Controller
{
    /**
     * @var IMethodFactory
     */
    private $methodFactory;

    /**
     * MethodController constructor.
     * @param string $id
     * @param Module $module
     * @param IMethodFactory $methodFactory
     * @param array $config
     */
    public function __construct($id, Module $module, IMethodFactory $methodFactory, array $config = [])
    {
        $this->methodFactory = $methodFactory;
        parent::__construct($id, $module, $config);
        ini_set('memory_limit', '512M');
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'index' => ['get', 'post'],
            ]
        ];

        return $behaviors;
    }

    /**
     * @param $method
     * @return mixed
     */
    public function actionIndex($method)
    {
        $params = ArrayHelper::merge(Yii::$app->request->getQueryParams(), Yii::$app->request->post());
        $object = $this->methodFactory->factory($method, ['params' => $params]);
        return $object->run();
    }
}