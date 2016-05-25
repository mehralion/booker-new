<?php

namespace common\factories\ratio\tennis;
use common\factories\ratio\_default\_interface\iRatio;
use common\factories\ratio\_default\BaseTotalMore;
use common\interfaces\iStatus;

/**
 * Class TotalMore
 * @package common\factories\ratio
 *
 * @method \TennisEvent getEvent()
 */
class TotalMore extends BaseTotalMore implements iRatio
{
    protected function getTotalVal()
    {
        return $this->total_val ? $this->total_val : $this->getEvent()->getNewRatio()->getTotalVal();
    }

    protected function prepare($totalVal)
    {
        $msg = \CHtml::openTag('ul', ['class' => 'log']);
        $msg .= \CHtml::tag('li', [], sprintf('Сумма 1: %s', $this->getEvent()->getResult()->getDuringSumTeam1()));
        $msg .= \CHtml::tag('li', [], sprintf('Сумма 2: %s', $this->getEvent()->getResult()->getDuringSumTeam2()));
        $msg .= \CHtml::tag('li', [], sprintf('Значение тотала: %s', $totalVal));

        $value = $this->getEvent()->getResult()->getDuringSumTeam1() + $this->getEvent()->getResult()->getDuringSumTeam2();
        $msg .= \CHtml::tag('li', [], sprintf('Сумма: %s', $value));
        $msg .= \CHtml::tag('li', [], sprintf('Операция: %s > %s = %s',
            $value,
            $totalVal,
            $value > $totalVal ? 'TRUE' : 'FALSE'
        ));

        if($value > $totalVal) {
            $result = iStatus::RESULT_WIN;
            $msg .= \CHtml::tag('li', [], 'Результат: Сыграла');
        } elseif($value == $totalVal) {
            $result = iStatus::RESULT_SET_K_1;
            $msg .= \CHtml::tag('li', [], 'Результат: Возврат');
        } else {
            $result = iStatus::RESULT_LOSS;
            $msg .= \CHtml::tag('li', [], 'Результат: Не сыграла');
        }

        $msg .= \CHtml::closeTag('ul');
        $this->addExplain($msg);

        return $result;
    }
}