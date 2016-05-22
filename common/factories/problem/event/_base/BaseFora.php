<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 23.05.2016
 */

namespace common\factories\problem\event\_base;

use common\models\EventProblem;

class BaseFora extends BaseProblemEvent
{
    protected function getProblemType()
    {
        return EventProblem::PROBLEM_FORA;
    }

    protected function prepare()
    {
        $fixed = 0;
        foreach ($this->event->eventFixedValues as $fixedValue) {
            if($fixedValue->filed == 'fora_val_1' || $fixedValue->filed == 'fora_val_2') {
                $fixed++;
            }
        }
        if($fixed == 2) {
            $this->is_problem = false;
            return;
        }

        $odds = $this->bookmaker_event->getOdds();
        $fora_val_1 = isset($odds['fora_val_1']) ? $odds['fora_val_1'] : false;
        $fora_val_2 = isset($odds['fora_val_2']) ? $odds['fora_val_2'] : false;
        if($fora_val_1 === false && $fora_val_2 !== false) {
            return;
        }
        if($fora_val_1 !== false && $fora_val_2 === false) {
            return;
        }

        $foraVal1 = $fora_val_1 < 0 ? (-1) * $fora_val_1 : $fora_val_1;
        $foraVal2 = $fora_val_2 < 0 ? (-1) * $fora_val_2 : $fora_val_2;
        if($foraVal1 != $foraVal2 || ($fora_val_1 > 0 && $fora_val_2 > 0)) {
            return;
        }

        $this->is_problem = false;
    }

    protected function checkSame($LastSameProblem)
    {
        $odds = $this->bookmaker_event->getOdds();
        foreach (['fora_val_1', 'fora_val_2'] as $item) {
            if(!isset($odds[$item])) {
                $odds[$item] = 'null';
            }
        }

        //is same problem?
        $custom = unserialize($LastSameProblem['custom']);
        if($odds['fora_val_1'] == $custom['fora_1'] && $odds['fora_val_2'] == $custom['fora_2']) {
            return true;
        }

        return false;
    }

    protected function getParams()
    {
        $odds = $this->bookmaker_event->getOdds();
        foreach (['fora_val_1', 'fora_val_2'] as $item) {
            if(!isset($odds[$item])) {
                $odds[$item] = 'null';
            }
        }

        return [
            'fora_val_1'    => $odds['fora_val_1'],
            'fora_val_2'    => $odds['fora_val_2']
        ];
    }

    protected function getMessage()
    {
        $odds = $this->bookmaker_event->getOdds();
        foreach (['fora_val_1', 'fora_val_2'] as $item) {
            if(!isset($odds[$item])) {
                $odds[$item] = 'null';
            }
        }

        return sprintf('Проблемы со значением фор. Фора 1: %s Фора 2: %s', $odds['fora_val_1'], $odds['fora_val_2']);
    }
}