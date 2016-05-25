<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 23.05.2016
 */

namespace common\models\event\result;


abstract class BaseResult implements iEventResult
{
    /** @var boolean */
    protected $is_empty = true;
    /** @var int */
    protected $team_2_result;
    /** @var int */
    protected $team_1_result;
    /** @var bool */
    protected $is_cancel = false;
    /** @var int */
    protected $event_id;
}