<?php
namespace cacatuidae\ipRange\interfaces;

/**
 * Interface IStorage
 * @package cacatuidae\ipRange\interfaces
 */
interface IStorage
{
    /**
     * @return bool
     */
    public function hasFile();

    /**
     * @return string
     */
    public function getStorageFile();

    /**
     * @param $file string
     * @return bool
     */
    public function setFile($file);

    /**
     * @return bool
     */
    public function saveFile();
}