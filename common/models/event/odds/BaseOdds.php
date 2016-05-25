<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 23.05.2016
 */

namespace common\models\event\odds;

use yii\base\Model;

/**
 * Class BaseOdds
 * @package common\models\event\odds
 *
 */
abstract class BaseOdds extends Model implements iEventOdds
{
    /** @var float */
    public $itotal_val_1;
    /** @var float */
    public $itotal_val_2;
    /** @var float */
    public $itotal_more_1;
    /** @var float */
    public $itotal_more_2;
    /** @var float */
    public $itotal_less_1;
    /** @var float */
    public $itotal_less_2;
    /** @var float */
    public $fora_val_1;
    /** @var float */
    public $fora_val_2;
    /** @var float */
    public $fora_ratio_1;
    /** @var float */
    public $fora_ratio_2;
    /** @var float */
    public $total_val;
    /** @var float */
    public $total_more;
    /** @var float */
    public $total_less;
    /** @var float */
    public $ratio_p1;
    /** @var float */
    public $ratio_p2;
    /** @var float */
    public $ratio_x2;
    /** @var float */
    public $ratio_x;

    protected $not_auto_reason;

    abstract protected function getAutoRequireFields();
    
    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => [
                'itotal_val_1', 'itotal_val_2', 'itotal_more_1', 'itotal_more_2', 'itotal_less_1', 'itotal_less_2',
                'fora_val_1', 'fora_val_2', 'fora_ratio_1', 'fora_ratio_2', 'total_val', 'total_more', 'total_less',
                'ratio_p1', 'ratio_p2', 'ratio_x2', 'ratio_x'
            ]
        ];
    }

    /**
     * @return mixed
     */
    public function getNotAutoReason()
    {
        return $this->not_auto_reason;
    }

    public function canAuto()
    {
        foreach ($this->getAutoRequireFields() as $odds => $min_value) {
            $value = $this->{$odds};
            if($value === null || (empty($value) && $value != 0)) {
                $this->not_auto_reason = sprintf('Пустое значение в обязательном поле %s. Значение: %s', $odds, $value);
                return false;
            }

            if($value < 0) {
                $value *= (-1);
            }

            if($value < $min_value) {
                $this->not_auto_reason = sprintf('Значение меньше минимального. Поле: %s. Занчение: %s. Минимальное: %s', $odds, $value, $min_value);
                return false;
            }

            if($value > 15 && $min_value != 0) {
                $this->not_auto_reason = sprintf('Коэф. больше 15. Поле: %s. Значение: %s', $odds, $value);
                return false;
            }
        }

        return true;
    }
}