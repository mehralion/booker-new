<?php

namespace common\factories\ratio\tennis;
use common\factories\ratio\_default\_interface\iRatio;
use common\factories\ratio\_default\BaseForaRatio1;
use common\interfaces\iStatus;

/**
 * Class ForaRatio1
 * @package common\factories\ratio
 *
 * @method \TennisEvent getEvent()
 */
class ForaRatio1 extends BaseForaRatio1 implements iRatio
{
    protected function getForaVal1()
    {
        return $this->getEvent()->getNewRatio()->getForaVal1();
    }

    protected function prepare($foraVal)
    {
        $msg = \CHtml::openTag('ul', ['class' => 'log']);
        $msg .= \CHtml::tag('li', [], sprintf('Сумма 1: %s', $this->getEvent()->getResult()->getDuringSumTeam1()));
        $msg .= \CHtml::tag('li', [], sprintf('Сумма 2: %s', $this->getEvent()->getResult()->getDuringSumTeam2()));
        $msg .= \CHtml::tag('li', [], sprintf('Фора: %s', $foraVal));

        $r = $this->getEvent()->getResult()->getDuringSumTeam1() + $foraVal;
        $msg .= \CHtml::tag('li', [], sprintf('Сумма: %s', $r));
        $msg .= \CHtml::tag('li', [], sprintf('Операция: %s > %s = %s',
            $r,
            $this->getEvent()->getResult()->getDuringSumTeam2(),
            $r > $this->getEvent()->getResult()->getDuringSumTeam2() ? 'TRUE' : 'FALSE'
        ));

        if($r > $this->getEvent()->getResult()->getDuringSumTeam2()) {
            $result = iStatus::RESULT_WIN;
            $msg .= \CHtml::tag('li', [], 'Результат: Сыграла');
        } elseif($r == $this->getEvent()->getResult()->getDuringSumTeam2()) {
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