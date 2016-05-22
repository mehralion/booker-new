<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 23.05.2016
 */

namespace common\factories\problem\event\_base;

use common\models\EventProblem;

abstract class BaseForaWin extends BaseProblemEvent
{
    protected function getProblemType()
    {
        return EventProblem::PROBLEM_FORA_WIN;
    }

    protected function prepare()
    {
        $fixed = 0;
        foreach ($this->event->eventFixedValues as $fixedValue) {
            if(in_array($fixedValue->filed, ['fora_ratio_1', 'fora_ratio_2', 'p1', 'p2'])) {
                $fixed++;
            }
        }
        if($fixed == 4) {
            $this->is_problem = false;
            return;
        }

        $odds = $this->bookmaker_event->getOdds();
        foreach (['fora_val_1', 'fora_val_2', 'fora_ratio_1', 'fora_ratio_2', 'p1', 'p2'] as $item) {
            if(!isset($odds[$item])) {
                $odds[$item] = 'null';
            }
        }
        if($odds['fora_val_1'] == 0.25 || $odds['fora_val_2'] == 0.25) {
            $this->is_problem = false;
            return;
        }
        if($odds['fora_val_1'] < 0) {
            if($odds['fora_ratio_1'] < $odds['p1']) {
                return;
            }
        } elseif($odds['fora_val_2'] < 0) {
            if($odds['fora_ratio_2'] < $odds['p2']) {
                return;
            }
        }

        $this->is_problem = false;
    }

    protected function checkSame($LastSameProblem)
    {
        $odds = $this->bookmaker_event->getOdds();
        foreach (['fora_ratio_1', 'fora_ratio_2', 'p1', 'p2'] as $item) {
            if(!isset($odds[$item])) {
                $odds[$item] = 'null';
            }
        }

        //is same problem?
        $custom = unserialize($LastSameProblem['custom']);
        $arr1 = [
            $custom['fora_ratio_1'],
            $custom['fora_ratio_2'],
            $custom['p1'],
            $custom['p2'],
        ];
        $arr2 = [
            $odds['fora_ratio_1'],
            $odds['fora_ratio_2'],
            $odds['p1'],
            $odds['p2'],
        ];
        if($arr1 == $arr2) {
            return true;
        }

        return false;
    }

    protected function getParams()
    {
        $odds = $this->bookmaker_event->getOdds();
        foreach (['fora_ratio_1', 'fora_ratio_2', 'p1', 'p2'] as $item) {
            if(!isset($odds[$item])) {
                $odds[$item] = 'null';
            }
        }


        return [
            'fora_ratio_1'  => $odds['fora_ratio_1'],
            'fora_ratio_2'  => $odds['fora_ratio_2'],
            'p1'            => $odds['p1'],
            'p2'            => $odds['p2'],
        ];
    }

    protected function getMessage()
    {
        $odds = $this->bookmaker_event->getOdds();
        foreach (['fora_val_1', 'fora_val_2', 'fora_ratio_1', 'fora_ratio_2', 'p1', 'p2'] as $item) {
            if(!isset($odds[$item])) {
                $odds[$item] = 'null';
            }
        }

        return sprintf('Форы => (%s)-(%s). Коэф. Фор => %s-%s. П1-П2 => %s-%s',
            $odds['fora_val_1'], $odds['fora_val_2'], $odds['fora_ratio_1'], $odds['fora_ratio_2'],
            $odds['p1'], $odds['p2']);
    }
}