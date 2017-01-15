<?php
namespace cacatuidae\ipRange\reader;

use cacatuidae\ipRange\interfaces\IReader;
use cacatuidae\ipRange\Module;
use yii\base\Exception;
use yii\base\Object;

/**
 * Class IpRangeCsvReader
 * @package cacatuidae\ipRange\reader
 */
class CsvReader extends Object implements IReader
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var array
     */
    protected $fileInfo;

    /**
     * @var array
     */
    protected $validExtensions = ['csv'];

    /**
     * @var array
     */
    protected $validMimeTypes = ['text/csv', 'text/plain', 'application/vnd.ms-excel'];

    /**
     * @return \Generator
     */
    protected function readFile()
    {
        $data = file($this->path);
        if(count($data)) {
            array_shift($data);
            foreach ($data as $row) {
                yield $row;
            }
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    public function result()
    {
        if(!$this->path)
            throw new Exception("Невозможно получить результаты, т.к. вы не указали файл");

        $rows = [];
        // Clicks,Conversions,Revenue,Cost,Profit,CPV,CTR,CR,CV,ROI,EPV
        $route_rows = [0 => 'ip', 1 => 'visits', 2 => 'clicks', 3 => 'conversions', 4 => 'revenue', 5 => 'cost',
            6 => 'profit', 7 => 'cpv', 8 => 'ctr', 9 => 'cr', 10 => 'cv', 11 => 'roi', 12 => 'epv'];

        foreach ($this->readFile() as $row) {
            $row = explode(',', $row);
            $ip = str_replace('"', '', $row[0]);
            $ip_explode = explode('.', $ip);
            array_pop($ip_explode);
            $ip_group = implode('.', $ip_explode);
            $group = [];
            foreach ($route_rows as $k => $name) {
                switch ($name) {
                    case 'ip':
                        $group[$name] = (string)$ip;
                    break;
                    case 'visits':
                    case 'click':
                    case 'conversions':
                        $group[$name] = (int)trim($row[$k]);
                    break;
                    case 'revenue':
                    case 'cost':
                    case 'profit':
                    case 'cpv':
                    case 'ctr':
                    case 'cr':
                    case 'cv':
                    case 'roi':
                    case 'epv':
                        $value = str_replace(',', '.', $row[$k]);
                        $value = preg_replace('/[^\d\.\-]/', '', $value);
                        $group[$name] = (double)$value;
                    break;
                    default:
                        $group[$name] = trim($row[$k]);
                    break;
                }
            }
            $group['group'] = $ip_group;
            $rows[] = $group;
        }

        return $rows;
    }

    /**
     * @param $path
     * @return IReader
     * @throws Exception
     */
    public function setPath($path)
    {
        if(!file_exists($path) || !is_writable($path))
            throw new Exception("Файл '{$path}' не найден либо недоступен для чтения");

        $this->path = $path;
        $this->fileInfo = pathinfo($this->path);

        if(!in_array($this->fileInfo['extension'], $this->validExtensions))
            throw new Exception("Файл '{$path}' имеет невалидное расширение. Допустимы: " .
                implode(', ', $this->validExtensions));

        return $this;
    }

    /**
     * @return array
     */
    public function getValidExtensions()
    {
        return $this->validExtensions;
    }

    /**
     * @return array
     */
    public function getValidMimeTypes()
    {
        return $this->validMimeTypes;
    }

    /**
     * @return array
     */
    public function getAttributesLabels()
    {
        return [
            'ip' => Module::t('ATTR_IP', 'ip_range'),
            'visits' => Module::t('ATTR_VISITS', 'ip_range'),
            'clicks' => Module::t('ATTR_CLICKS', 'ip_range'),
            'conversions' => Module::t('ATTR_CONVERSIONS', 'ip_range'),
            'revenue' => Module::t('ATTR_REVENUE', 'ip_range'),
            'cost' => Module::t('ATTR_COST', 'ip_range'),
            'profit' => Module::t('ATTR_PROFIT', 'ip_range'),
            'cpv' => Module::t('ATTR_CPV', 'ip_range'),
            'ctr' => Module::t('ATTR_CTR', 'ip_range'),
            'cr' => Module::t('ATTR_CR', 'ip_range'),
            'cv' => Module::t('ATTR_CV', 'ip_range'),
            'roi' => Module::t('ATTR_ROI', 'ip_range'),
            'epv' => Module::t('ATTR_EPV', 'ip_range')
        ];
    }
}