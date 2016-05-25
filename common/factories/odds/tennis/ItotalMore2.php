<?php

namespace common\factories\ratio\tennis;
use common\factories\ratio\_default\_interface\iRatio;
use common\factories\ratio\_default\BaseItotalMore2;
use common\interfaces\iStatus;

/**
 * Class ForaRatio1
 * @package common\factories\ratio
 *
 * @method \TennisEvent getEvent()
 */
class ItotalMore2 extends BaseItotalMore2 implements iRatio
{
    protected function getItotalVal2()
    {
         return $this->getEvent()->getNewRatio()->getItotalVal2();
    }

    /**
     * @return int
     */
    public function check()
    {
        $msg = \CHtml::openTag('ul', ['class' => 'log']);
        $msg .= \CHtml::tag('li', [], sprintf('Сумма 2: %s', $this->getEvent()->getResult()->getDuringSumTeam2()));
        $msg .= \CHtml::tag('li', [], sprintf('Значение инд. тотала: %s', $this->getItotalVal2()));

        $msg .= \CHtml::tag('li', [], sprintf('Операция: %s > %s = %s',
            $this->getEvent()->getResult()->getDuringSumTeam2(),
            $this->getItotalVal2(),
            $this->getEvent()->getResult()->getDuringSumTeam2() > $this->getItotalVal2() ? 'TRUE' : 'FALSE'
        ));

        if($this->getEvent()->getResult()->getDuringSumTeam2() > $this->getItotalVal2()) {
            $this->setStatus(iStatus::RESULT_WIN);
            $msg .= \CHtml::tag('li', [], 'Результат: Сыграла');
        } elseif($this->getEvent()->getResult()->getDuringSumTeam2() == $this->getItotalVal2()) {
            $this->setStatus(iStatus::RESULT_WIN)
                ->setRatioValue(1.00);
            $msg .= \CHtml::tag('li', [], 'Результат: Возврат');
        } else {
            $this->setStatus(iStatus::RESULT_LOSS);
            $msg .= \CHtml::tag('li', [], 'Результат: Не сыграла');
        }

        $msg .= \CHtml::closeTag('ul');
        $this->addExplain($msg);
    }
}