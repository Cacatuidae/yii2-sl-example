<?php
namespace cacatuidae\ipRange\interfaces;

/**
 * Interface IReader
 * @package cacatuidae\ipRange\interfaces
 */
interface IReader
{
    /**
     * @param $path
     * @return IReader
     */
    public function setPath($path);

    /**
     * @return array
     */
    public function result();

    /**
     * @return array
     */
    public function getValidExtensions();

    /**
     * @return array
     */
    public function getValidMimeTypes();

    /**
     * @return array
     */
    public function getAttributesLabels();
}