<?php
namespace cacatuidae\ipRange\model;

use cacatuidae\ipRange\interfaces\IReader;
use cacatuidae\ipRange\interfaces\IStorage;
use cacatuidae\ipRange\interfaces\IUploadModel;
use yii\base\Exception;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Class BaseUploadModel
 * @package app\modules\tools\extension\IpRange\model
 */
class BaseUploadModel extends Model implements IUploadModel
{
    /**
     * @var string|UploadedFile
     */
    public $file;

    /**
     * @var IStorage
     */
    private $storage;

    /**
     * @var IReader
     */
    private $reader;

    /**
     * @var string
     */
    protected static $fileAttribute = 'file';

    /**
     * BaseUploadModel constructor.
     * @param IStorage $storage
     * @param IReader $reader
     * @param array $config
     */
    public function __construct(IStorage $storage, IReader $reader, array $config = [])
    {
        $this->storage = $storage;
        $this->reader = $reader;

        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => $this->reader->getValidExtensions(),
                'mimeTypes' => $this->reader->getValidMimeTypes(), 'checkExtensionByMimeType' => false],
        ];
    }

    /**
     * @return string
     */
    public function getFileAttribute()
    {
        return self::$fileAttribute;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function saveFile()
    {
        if(!$this->file instanceof UploadedFile)
            throw new Exception("Файл не загружен");
        $this->storage->setFile($this->file);
        return $this->storage->saveFile();
    }
}