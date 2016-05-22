<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 23.05.2016
 */

namespace common\factories\problem\event\_base;

use common\models\EventProblem;

class BaseNoResult extends BaseProblemEvent
{
    protected function getProblemType()
    {
        return EventProblem::PROBLEM_NO_RESULT;
    }

    protected function prepare()
    {

    }

    protected function checkSame($LastSameProblem)
    {

    }

    protected function getParams()
    {

    }

    protected function getMessage()
    {
        
    }
}