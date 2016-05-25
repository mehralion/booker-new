<?php

namespace common\factories\ratio\tennis;
use common\factories\ratio\_default\_interface\iRatio;
use common\factories\ratio\_default\BaseRatio12;
use common\interfaces\iStatus;

/**
 * Class ForaRatio1
 * @package common\factories\ratio
 *
 * @method \TennisEvent getEvent()
 */
class Ratio12 extends BaseRatio12 implements iRatio
{
    /**
     * @return int
     */
    public function check()
    {
        $msg = \CHtml::openTag('ul', ['class' => 'log']);
        $msg .= \CHtml::tag('li', [], sprintf('Результат 1: %s', $this->getEvent()->getResult()->getTeam1Result()));
        $msg .= \CHtml::tag('li', [], sprintf('Результат 2: %s', $this->getEvent()->getResult()->getTeam2Result()));

        $msg .= \CHtml::tag('li', [], sprintf('Операция: %s == 1 && %s == 2 = %s',
            $this->getEvent()->getResult()->getTeam1Result(),
            $this->getEvent()->getResult()->getTeam2Result(),
            $this->getEvent()->getResult()->getTeam1Result() == 1 && $this->getEvent()->getResult()->getTeam2Result() == 2 ? 'TRUE' : 'FALSE'
        ));

        if($this->getEvent()->getResult()->getTeam1Result() == 1 && $this->getEvent()->getResult()->getTeam2Result() == 2) {
            $this->setStatus(iStatus::RESULT_WIN);
            $msg .= \CHtml::tag('li', [], 'Результат: Сыграла');
        } else {
            $this->setStatus(iStatus::RESULT_LOSS);
            $msg .= \CHtml::tag('li', [], 'Результат: Не сыграла');
        }

        $msg .= \CHtml::closeTag('ul');
        $this->addExplain($msg);
    }
}