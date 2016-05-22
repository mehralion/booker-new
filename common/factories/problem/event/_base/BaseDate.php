<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 23.05.2016
 */

namespace common\factories\problem\event\_base;

use common\models\EventProblem;

class BaseDate extends BaseProblemEvent
{
    protected function getProblemType()
    {
        return EventProblem::PROBLEM_DATE;
    }

    protected function prepare()
    {
        foreach ($this->event->eventFixedValues as $fixedValue) {
            if($fixedValue->filed == 'started_at') {
                $this->is_problem = false;
                return;
            }
        }

        if($this->event->started_at == $this->bookmaker_event->getDate()) {
            $this->is_problem = false;
            return;
        }
    }

    protected function checkSame($LastSameProblem)
    {
        //is same problem?
        $custom = unserialize($LastSameProblem['custom']);
        if($custom['started_at'] == $this->bookmaker_event->getDate()) {
            return true;
        }

        return false;
    }

    protected function getParams()
    {
        return [
            'started_at'    => $this->bookmaker_event->getDate()
        ];
    }

    protected function getMessage()
    {
        return sprintf('Некорректная дата. Текущая %s. Пришла %s',
            date('d.m.Y H:i:s', $this->event->started_at), date('d.m.Y H:i:s', $this->bookmaker_event->getDate()));
    }
}