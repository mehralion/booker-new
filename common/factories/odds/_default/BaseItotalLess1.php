<?php

namespace common\factories\odds\_default;
use common\factories\odds\_default\_base\BaseRatio;
use common\factories\odds\_default\_interface\iRatio;
use common\interfaces\iStatus;

/**
 * Class ForaRatio1
 * @package common\factories\ratio
 */
abstract class BaseItotalLess1 extends BaseRatio implements iRatio
{
    abstract protected function getItotalVal1();

    /**
     * @return int
     */
    public function check()
    {
        $msg = \CHtml::openTag('ul', ['class' => 'log']);
        $msg .= \CHtml::tag('li', [], sprintf('Результат 1: %s', $this->getEvent()->getResult()->getTeam1Result()));
        $msg .= \CHtml::tag('li', [], sprintf('Значение инд. тотала: %s', $this->getItotalVal1()));

        $msg .= \CHtml::tag('li', [], sprintf('Операция: %s < %s = %s',
            $this->getEvent()->getResult()->getTeam1Result(),
            $this->getItotalVal1(),
            $this->getEvent()->getResult()->getTeam1Result() < $this->getItotalVal1() ? 'TRUE' : 'FALSE'
        ));

        if($this->getEvent()->getResult()->getTeam1Result() < $this->getItotalVal1()) {
            $this->setStatus(iStatus::RESULT_WIN);
            $msg .= \CHtml::tag('li', [], 'Итог: Сыграла');
        } elseif($this->getEvent()->getResult()->getTeam1Result() == $this->getItotalVal1()) {
            $this->setStatus(iStatus::RESULT_WIN)
                ->setRatioValue(1.00);
            $msg .= \CHtml::tag('li', [], 'Итог: Возврат');
        } else {
            $this->setStatus(iStatus::RESULT_LOSS);
            $msg .= \CHtml::tag('li', [], 'Итог: Не сыграла');
        }

        $msg .= \CHtml::closeTag('ul');
        $this->addExplain($msg);
    }
}