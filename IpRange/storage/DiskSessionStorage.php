<?php
namespace cacatuidae\ipRange\storage;

use cacatuidae\ipRange\interfaces\IStorage;
use cacatuidae\ipRange\Module;
use yii\base\Object;
use Yii;
use yii\base\Exception;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * Class DiskSessionStorage
 * @package cacatuidae\ipRange\storage
 */
class DiskSessionStorage extends Object implements IStorage
{
    /**
     * @var string|UploadedFile
     */
    protected $file;

    /**
     * @var string
     */
    protected $_storageFile;

    /**
     * @var bool
     */
    protected $isUploadedFile = false;

    /**
     * @var string
     */
    protected $path;

    /**
     * @throws Exception
     */
    public function init()
    {
        parent::init();

        if(!Module::getInstance()->diskStoragePath)
            throw new Exception("Необходимо установить значение 'diskStoragePath'");

        if(!Yii::$app->session)
            throw new Exception("Session компонент необходим для работы IpRange");

        if (!Yii::$app->session->isActive)
            Yii::$app->session->open();

        $path = $this->getPath();

        if(!is_dir($path))
            FileHelper::createDirectory($path);
    }

    /**
     * @param $file
     * @return bool
     * @throws Exception
     */
    public function setFile($file)
    {
        if(!is_string($file) && !$file instanceof UploadedFile)
            throw new Exception("Файл передан неверно");

        if($file instanceof UploadedFile) {
            $this->isUploadedFile = true;
        } else {
            $this->isUploadedFile = false;
            if(!file_exists($file))
                throw new Exception("Файл '{$file}' не найден");
        }

        $this->file = $file;
        return true;
    }


    /**
     * @return string
     */
    public function getStorageFile()
    {
        if(!$this->_storageFile) {
            $sessionFile = $this->getSessionId();
            $find = FileHelper::findFiles($this->getPath(), ['filter' => function($file) use($sessionFile) {
                return (pathinfo($file, PATHINFO_FILENAME) == $sessionFile);
            }]);
            $this->_storageFile = count($find) ? $find[0] : false;
        }

        return $this->_storageFile;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function saveFile()
    {
        if(!$this->file)
            throw new Exception("Необходимо установить файл");

        $path = $this->getPath();
        $pathFile = $this->getPathFile();
        $extension = $this->isUploadedFile ? $this->file->extension : pathinfo($this->file, PATHINFO_EXTENSION);
        $extension = trim(strtolower($extension));

        if(is_dir($path)) {
            if(!is_writable($path))
                throw new Exception("Директория '{$path}' недоступна для записи");
        }
        else {
            Yii::info("Создание директории '{$path}'");
            FileHelper::createDirectory($path);
        }
        $newPathFile = $pathFile . '.' . $extension;
        $this->_storageFile = $newPathFile;

        if($this->isUploadedFile)
            return $this->file->saveAs($newPathFile);
        return copy($this->file, $newPathFile);
    }

    /**
     * @return bool
     */
    public function hasFile()
    {
        $file = $this->getStorageFile();
        return ($file && is_file($file));
    }

    /**
     * @return string
     */
    public function getPathFile()
    {
        return $this->getPath() . $this->getSessionId();
    }

    /**
     * @return string
     */
    public function getSessionId()
    {
        return Yii::$app->session->id;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        if(!$this->path)
            $this->path = FileHelper::normalizePath(Yii::getAlias(Module::getInstance()->diskStoragePath)) .
                DIRECTORY_SEPARATOR;
        return $this->path;
    }
}