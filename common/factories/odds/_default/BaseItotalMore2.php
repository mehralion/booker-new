<?php

namespace common\factories\odds\_default;
use common\factories\odds\_default\_base\BaseRatio;
use common\factories\odds\_default\_interface\iRatio;
use common\interfaces\iStatus;

/**
 * Class ForaRatio1
 * @package common\factories\ratio
 */
abstract class BaseItotalMore2 extends BaseRatio implements iRatio
{
    abstract protected function getItotalVal2();

    /**
     * @return int
     */
    public function check()
    {
        $msg = \CHtml::openTag('ul', ['class' => 'log']);
        $msg .= \CHtml::tag('li', [], sprintf('Результат 2: %s', $this->getEvent()->getResult()->getTeam2Result()));
        $msg .= \CHtml::tag('li', [], sprintf('Значение инд. тотала: %s', $this->getItotalVal2()));

        $msg .= \CHtml::tag('li', [], sprintf('Операция: %s > %s = %s',
            $this->getEvent()->getResult()->getTeam2Result(),
            $this->getItotalVal2(),
            $this->getEvent()->getResult()->getTeam2Result() > $this->getItotalVal2() ? 'TRUE' : 'FALSE'
        ));

        if($this->getEvent()->getResult()->getTeam2Result() > $this->getItotalVal2()) {
            $this->setStatus(iStatus::RESULT_WIN);
            $msg .= \CHtml::tag('li', [], 'Итог: Сыграла');
        } elseif($this->getEvent()->getResult()->getTeam2Result() == $this->getItotalVal2()) {
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