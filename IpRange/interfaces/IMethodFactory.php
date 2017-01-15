<?php
namespace cacatuidae\ipRange\interfaces;

/**
 * Interface IMethodFactory
 * @package cacatuidae\ipRange\interfaces
 */
interface IMethodFactory
{
    /**
     * @param $method
     * @param array $config
     * @return mixed
     */
    public function factory($method, array $config = []);
}