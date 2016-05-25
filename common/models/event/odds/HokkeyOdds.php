<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 23.05.2016
 */

namespace common\models\event\odds;
use yii\helpers\ArrayHelper;

/**
 * Class HokkeyOdds
 * @package common\models\event\odds
 *
 * @property float ratio_1x
 * @property float ratio_12
 * @property float ratio_x2
 */
class HokkeyOdds extends BaseOdds
{
    /** @var float */
    public $ratio_1x;
    /** @var float */
    public $ratio_12;
    /** @var float */
    public $ratio_x2;

    public function scenarios()
    {
        return ArrayHelper::merge(
            parent::scenarios(), [self::SCENARIO_DEFAULT => [
                'ratio_1x', 'ratio_12', 'ratio_x2'
            ]]
        );
    }

    protected function getAutoRequireFields()
    {
        return [
            'fora_val_1'    => 0,
            'fora_val_2'    => 0,
            'fora_ratio_1'  => 1.01,
            'fora_ratio_2'  => 1.01,
            'total_val'     => 0,
            'total_more'    => 1.01,
            'total_less'    => 1.01,
            'ratio_p1'      => 1.01,
            'ratio_p2'      => 1.01,
            //'ratio_1x'      => 1.01,
            'ratio_12'      => 1.01,
            //'ratio_x2'      => 1.01,
        ];
    }
}