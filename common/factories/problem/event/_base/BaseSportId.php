<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 23.05.2016
 */

namespace common\factories\problem\event\_base;

use common\models\EventProblem;

class BaseSportId extends BaseProblemEvent
{
    protected function getProblemType()
    {
        return EventProblem::PROBLEM_SPORT_ID;
    }

    protected function prepare()
    {
        foreach ($this->event->eventFixedValues as $fixedValue) {
            if(in_array($fixedValue->filed, ['sport_id'])) {
                $this->is_problem = false;
                return;
            }
        }

        if($this->event->sport_id == $this->bookmaker_event->getSportId()) {
            $this->is_problem = false;
        }
    }

    protected function checkSame($LastSameProblem)
    {
        //is same problem?
        $custom = unserialize($LastSameProblem['custom']);
        if($custom['spot_id'] == $this->bookmaker_event->getSportId()) {
            return true;
        }

        return false;
    }

    protected function getParams()
    {
        return [
            'spot_id' => $this->bookmaker_event->getSportId()
        ];
    }

    protected function getMessage()
    {
        return sprintf('У события новая лига %s', $this->bookmaker_event->getSportId());
    }
}