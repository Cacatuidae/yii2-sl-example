<?php
namespace cacatuidae\ipRange\controllers;

use cacatuidae\ipRange\assets\GridAsset;
use cacatuidae\ipRange\interfaces\ISearchModel;
use cacatuidae\ipRange\interfaces\IUploadModel;
use cacatuidae\ipRange\factory\MethodFactory;
use yii\base\Exception;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use cacatuidae\ipRange\Module;
use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;

/**
 * Class IndexController
 * @package cacatuidae\ipRange\controllers
 */
class IndexController extends Controller
{
    /**
     * @var IUploadModel
     */
    private $uploadModel;

    /**
     * @var ISearchModel
     */
    private $searchModel;

    /**
     * IpRangeController constructor.
     * @param string $id
     * @param Module $module
     * @param IUploadModel $uploadModel
     * @param ISearchModel $searchModel
     * @param array $config
     */
    public function __construct($id, Module $module, IUploadModel $uploadModel, ISearchModel $searchModel,
                                array $config = [])
    {
        $this->uploadModel = $uploadModel;
        $this->searchModel = $searchModel;
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
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->request->isPost) {
            $fileAttribute = $this->uploadModel->getFileAttribute();
            $this->uploadModel->{$fileAttribute} = UploadedFile::getInstance($this->uploadModel, $fileAttribute);
            if($this->uploadModel->validate()) {
                try {
                    $this->uploadModel->saveFile();
                    return $this->refresh();
                } catch (Exception $e) {
                    Yii::error($e);
                    $this->uploadModel->addError($fileAttribute, "Ошибка сохранения файла: " . $e->getMessage());
                }
            }
        }

        GridAsset::register($this->view);

        $this->searchModel->setFilterData(Yii::$app->request->get());
        $searchProvider = $this->searchModel->runProvider();
        $gridColumns = $this->searchModel->getGridColumns();
        $gridAttributeOptions = $this->searchModel->getOptions();
        $gridWidgets = $this->searchModel->getWidgets();

        $this->view->title = Module::t('INDEX_TITLE', 'ip_range');
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('index', ['uploadModel' => $this->uploadModel, 'searchModel' => $this->searchModel,
            'searchProvider' => $searchProvider, 'gridColumns' => $gridColumns,
            'gridAttributeOptions' => $gridAttributeOptions, 'gridWidgets' => $gridWidgets]);
    }

    /**
     * @param $method
     * @return mixed
     */
    public function actionMethod($method)
    {
        $params = ArrayHelper::merge(Yii::$app->request->getQueryParams(), Yii::$app->request->post());
        $object = MethodFactory::factory($method, ['params' => $params]);
        return $object->run();
    }
}