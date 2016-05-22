<?php
/**
 * Created by PhpStorm.
 */

namespace common\components\bookmaker\parimatch\factories\parsers\football;

use common\components\bookmaker\parimatch\factories\parsers\Base;

class Custom3 extends Base
{
    protected $tdMapping = [
        0 => 'number',
        1 => 'date',
        2 => 'teams',
        3 => 'total_val',
        4 => 'total_more',
        5 => 'total_less',
        6 => 'ratio_p1',
        7 => 'ratio_x',
        8 => 'ratio_p2',
        9 => 'ratio_1x',
        10 => 'ratio_12',
        11 => 'ratio_x2',
    ];

    protected function getRatioField()
    {
        return [
            'total_more',
            'total_less',
            'ratio_p1',
            'ratio_x',
            'ratio_p2',
            'ratio_1x',
            'ratio_12',
            'ratio_x2',
        ];
    }

    /**
     * @return array
     */
    protected function getPlaceholder()
    {
        return [
            'date_string'   => null,
            'date_int'      => null,
            'number'        => null,
            'team_1'        => null,
            'team_2'        => null,
            'total_val'     => null,
            'total_more'    => null,
            'total_less'    => null,
            'ratio_p1'      => null,
            'ratio_x'       => null,
            'ratio_p2'      => null,
            'ratio_1x'      => null,
            'ratio_12'      => null,
            'ratio_x2'      => null,
        ];
    }

    public function getTdMapping()
    {
        return $this->tdMapping;
    }
}