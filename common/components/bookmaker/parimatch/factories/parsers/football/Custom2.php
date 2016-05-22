<?php
/**
 * Created by PhpStorm.
 */

namespace common\components\bookmaker\parimatch\factories\parsers\football;

use common\components\bookmaker\parimatch\factories\parsers\Base;

class Custom2 extends Base
{
    protected $tdMapping = [
        0 => 'number',
        1 => 'date',
        2 => 'teams',
        3 => 'ratio_p1',
        4 => 'ratio_x',
        5 => 'ratio_p2',
    ];

    protected function getRatioField()
    {
        return [
            'ratio_p1',
            'ratio_x',
            'ratio_p2',
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
            'ratio_p1'      => null,
            'ratio_x'       => null,
            'ratio_p2'      => null,
        ];
    }

    public function getTdMapping()
    {
        return $this->tdMapping;
    }
}