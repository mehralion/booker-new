<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 23.05.2016
 */

namespace common\models\event\result;


class HokkeyResult extends BaseResult
{
    /** @var int */
    protected $team_1_part_1;
    /** @var int */
    protected $team_1_part_2;
    /** @var int */
    protected $team_1_part_3;

    /** @var int */
    protected $team_2_part_1;
    /** @var int */
    protected $team_2_part_2;
    /** @var int */
    protected $team_2_part_3;
}