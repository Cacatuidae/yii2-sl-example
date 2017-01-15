<?php
namespace cacatuidae\ipRange\search;

use cacatuidae\ipRange\interfaces\IMethodWidget;
use cacatuidae\ipRange\interfaces\IProvider;
use cacatuidae\ipRange\interfaces\IReader;
use cacatuidae\ipRange\interfaces\ISearchModel;
use cacatuidae\ipRange\interfaces\IStorage;
use yii\base\Model;
use Yii;

/**
 * Class BaseSearchModel
 * @package cacatuidae\ipRange\search
 */
abstract class BaseSearchModel extends Model implements ISearchModel
{
    /**
     * @var bool
     */
    public $option_merge;

    /**
     * @var array
     */
    protected $rows = [];

    /**
     * @var array
     */
    protected $filteredRows = [];

    /**
     * @var IReader
     */
    private $reader;

    /**
     * @var IStorage
     */
    private $storage;

    /**
     * @var IProvider
     */
    private $provider;

    /**
     * @var array
     */
    protected $filterData = [];

    /**
     * @var array
     */
    protected $options_attribute = [];

    /**
     * @var array
     */
    protected $widgets = [];

    /**
     * @var int
     */
    protected $pageSize = 100;

    /**
     * ArraySearchModel constructor.
     * @param IStorage $storage
     * @param IReader $reader
     * @param IProvider $provider
     * @param array $widgets
     * @param array $config
     */
    public function __construct(IStorage $storage, IReader $reader, IProvider $provider, array $widgets = [],
                                array $config = [])
    {
        $this->reader = $reader;
        $this->storage = $storage;
        $this->provider = $provider;
        $this->widgets = $widgets;

        parent::__construct($config);
    }

    public function init()
    {
        parent::init();

        if($this->storage->hasFile())
            $this->rows = $this->reader->setPath($this->storage->getStorageFile())->result();
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return $this->reader->getAttributesLabels();
    }

    /**
     * @param array $params
     * @return bool
     */
    public function setFilterData(array $params)
    {
        return $this->load($params);
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        $options = [];
        foreach ($this->options_attribute as $attribute) {
            $options[$attribute] = $this->{$attribute};
        }
        return $options;
    }

    /**
     * @return IMethodWidget[]
     */
    public function getWidgets()
    {
        /* @var $widgets IMethodWidget[] */
        $widgets = [];
        foreach ($this->widgets as $k => $widget)
            $widgets[$k] = Yii::createObject($widget);
        return $widgets;
    }

    /**
     * @return int
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * @param int $pageSize
     * @return bool
     */
    public function setPageSize($pageSize)
    {
        $this->pageSize = (int)$pageSize;
        return true;
    }
}