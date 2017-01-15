<?php
namespace cacatuidae\ipRange\interfaces;
use yii\base\Model;

/**
 * Interface ISearchModel
 * @package cacatuidae\ipRange\interfaces
 * @mixin Model
 */
interface ISearchModel
{
    /**
     * ISearchModel constructor.
     * @param IStorage $storage
     * @param IReader $reader
     * @param IProvider $provider
     * @param array $widgets
     * @param array $config
     */
    public function __construct(IStorage $storage, IReader $reader, IProvider $provider, array $widgets = [],
                                array $config = []);

    /**
     * @return array
     */
    public function attributeLabels();

    /**
     * @return IProvider
     */
    public function runProvider();

    /**
     * @return array
     */
    public function getGridColumns();

    /**
     * @param array $params
     * @return bool
     */
    public function setFilterData(array $params);

    /**
     * @return array
     */
    public function getOptions();

    /**
     * @return IMethodWidget
     */
    public function getWidgets();

    /**
     * @return int
     */
    public function getPageSize();

    /**
     * @param $pageSize int
     * @return bool
     */
    public function setPageSize($pageSize);
}