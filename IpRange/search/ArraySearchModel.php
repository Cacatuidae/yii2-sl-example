<?php
namespace cacatuidae\ipRange\search;

use cacatuidae\ipRange\interfaces\IProvider;
use cacatuidae\ipRange\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use Yii;
use NumberFormatter;

/**
 * Class ArraySearchModel
 * @package cacatuidae\ipRange\search
 */
class ArraySearchModel extends BaseSearchModel
{
    public $ip;
    public $visits;
    public $clicks;
    public $conversions;
    public $revenue;
    public $cost;
    public $profit;
    public $cpv;
    public $ctr;
    public $cr;
    public $cv;
    public $roi;
    public $epv;

    /**
     * @var array
     */
    protected $options_attribute = ['option_merge'];

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['ip'], 'string', 'max' => 25],
            [['visits', 'clicks', 'conversions', 'revenue', 'cost', 'profit', 'cpv', 'ctr', 'cr', 'cv', 'roi', 'epv'],
                'string', 'max' => 10],
            [['option_merge'], 'boolean']
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge([
            'option_merge' => Module::t('ATTR_OPTION_MERGE', 'ip_range')
        ], parent::attributeLabels());
    }

    /**
     * @return array
     */
    public function getGridColumns()
    {
        $columns = [];
        foreach ($this->attributeLabels() as $attribute => $label) {
            if(in_array($attribute, $this->options_attribute))
                continue;
            $inputFilter = Html::input('text', $this->formName() . "[{$attribute}]", $this->{$attribute});
            $column = [
                'attribute' => $attribute,
                'label' => $label,
                'filter' => $inputFilter
            ];
            switch ($attribute) {
                case 'visits':
                case 'clicks':
                case 'conversions':
                    $column['value'] = function(array $row) use($attribute) {
                        return Yii::$app->formatter->asInteger($row[$attribute]);
                    };
                    $columns[] = $column;
                    break;
                case 'ctr':
                case 'cr':
                case 'cv':
                case 'roi':
                    $column['value'] = function(array $row) use($attribute) {
                        return Yii::$app->formatter->asPercent($row[$attribute], null, [
                            NumberFormatter::MIN_FRACTION_DIGITS =>  2,
                            NumberFormatter::MAX_FRACTION_DIGITS =>  2
                        ]);
                    };
                    $columns[] = $column;
                break;
                case 'profit':
                case 'revenue':
                case 'cost':
                    $column['value'] = function(array $row) use($attribute) {
                        return Yii::$app->formatter->asCurrency($row[$attribute], 'USD');
                    };
                    $columns[] = $column;
                break;
                case 'cpv':
                case 'epv':
                    $column['value'] = function(array $row) use($attribute) {
                        return Yii::$app->formatter->asCurrency($row[$attribute], 'USD', [
                            NumberFormatter::MIN_FRACTION_DIGITS =>  4,
                            NumberFormatter::MAX_FRACTION_DIGITS =>  4
                        ]);
                    };
                    $columns[] = $column;
                break;
                default:
                    $columns[] = $column;
                break;
            }
        }
        return $columns;
    }

    /**
     * @param array $rows
     * @return array
     */
    protected function handleOptions(array $rows)
    {
        foreach ($this->options_attribute as $attribute) {
            $value = $this->{$attribute};
            if(empty($value))
                continue;

            switch ($attribute) {
                case 'option_merge':
                    $rowsByGroup = [];
                    if($value) {
                        foreach ($rows as $row) {
                            if(!isset($rowsByGroup[$row['group']])) {
                                $rowsByGroup[$row['group']] = $row;
                                $rowsByGroup[$row['group']]['ip'] = "{$row['group']}.0-{$row['group']}.255";
                                $rowsByGroup[$row['group']]['count'] = 1;
                            }
                            else {
                                $rowsByGroup[$row['group']]['count']++;
                                foreach ($row as $attr => $val) {
                                    switch ($attr) {
                                        case 'visits':
                                        case 'clicks':
                                        case 'conversions':
                                        case 'revenue':
                                        case 'cost':
                                        case 'profit':
                                        case 'cpv':
                                        case 'ctr':
                                        case 'cr':
                                        case 'cv':
                                        case 'roi':
                                        case 'epv':
                                            $rowsByGroup[$row['group']][$attr] += $val;
                                        break;
                                    }
                                }
                            }
                        }

                        foreach ($rowsByGroup as $group => &$row) {
                            foreach ($row as $attr => &$val) {
                                switch ($attr) {
                                    case 'visits':
                                    case 'clicks':
                                    case 'conversions':
                                        $row[$attr] = (int)$val;
                                    break;
                                    case 'revenue':
                                    case 'cost':
                                    case 'profit':
                                        $row[$attr] = (double)$val;
                                    break;
                                    case 'cpv':
                                    case 'ctr':
                                    case 'cr':
                                    case 'cv':
                                    case 'roi':
                                    case 'epv':
                                        $row[$attr] = (double)($val / (int)$row['count']);
                                    break;
                                }
                            }
                        }
                    }
                    $rows = $rowsByGroup;
                break;
            }
        }
        return $rows;
    }


    /**
     * @param array $rows
     * @return array
     */
    protected function filteredRows(array $rows)
    {
        foreach ($this->getAttributes() as $attribute => $value) {
            if(empty($value))
                continue;
            if(in_array($attribute, $this->options_attribute))
                continue;
            $rows = array_filter($rows, function ($row) use ($attribute, $value) {
                switch ($attribute) {
                    case 'visits':
                    case 'click':
                    case 'conversions':
                    case 'revenue':
                    case 'cost':
                    case 'profit':
                    case 'cpv':
                    case 'ctr':
                    case 'cr':
                    case 'cv':
                    case 'roi':
                    case 'epv':
                        $value = str_replace(' ', '', $value);
                        $number = str_replace(',', '.', $value);
                        $number = preg_replace('/[^\d+\-\.]/', '', $number);
                        if(strpos($value, '>=') === 0)
                            return $row[$attribute] >= $number;
                        elseif(strpos($value, '>') === 0)
                            return $row[$attribute] > $number;
                        elseif(strpos($value, '<=') === 0)
                            return $row[$attribute] <= $number;
                        elseif(strpos($value, '<') === 0)
                            return $row[$attribute] < $number;
                        else
                            return $row[$attribute] == $number;
                    break;
                    default:
                        $value = mb_strtolower(trim($value));
                        return strpos($row[$attribute], $value) !== false;
                    break;
                }
            });
        }
        return $rows;
    }

    /**
     * @return IProvider
     */
    public function runProvider()
    {
        /* @var $provider IProvider */
        $provider = Yii::createObject([
            'class' => 'IpRangeProvider',
            'allModels' => $this->filteredRows($this->handleOptions($this->rows)),
            'pagination' => ['pageSize' => $this->pageSize],
            'sort' => [
                'defaultOrder' => ['ip' => SORT_ASC],
                'attributes' => ['ip', 'visits', 'clicks', 'conversions', 'revenue', 'cost', 'profit',
                    'cpv', 'ctr', 'cr', 'cv', 'roi', 'epv'],
            ],
        ]);

        return $provider;
    }
}