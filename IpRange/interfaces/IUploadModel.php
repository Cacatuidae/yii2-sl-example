<?php
namespace cacatuidae\ipRange\interfaces;

/**
 * Interface IUploadModel
 * @package cacatuidae\ipRange\interfaces
 * @mixin \yii\base\Model
 */
interface IUploadModel
{
    /**
     * IUploadModel constructor.
     * @param IStorage $storage
     * @param IReader $reader
     * @param array $config
     */
    public function __construct(IStorage $storage, IReader $reader, array $config = []);

    /**
     * @return string
     */
    public function getFileAttribute();

    /**
     * @return bool
     */
    public function saveFile();
}