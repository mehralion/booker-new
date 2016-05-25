<?php

namespace common\factories\odds\_default;
use common\factories\odds\_default\_base\BaseRatio;
use common\factories\odds\_default\_interface\iRatio;
use common\interfaces\iStatus;

/**
 * Class ForaRatio1
 * @package common\factories\ratio
 */
class BaseRatioX extends BaseRatio implements iRatio
{
    /**
     * @return int
     */
    public function check()
    {
        $msg = \CHtml::openTag('ul', ['class' => 'log']);
        $msg .= \CHtml::tag('li', [], sprintf('Результат 1: %s', $this->getEvent()->getResult()->getTeam1Result()));
        $msg .= \CHtml::tag('li', [], sprintf('Результат 2: %s', $this->getEvent()->getResult()->getTeam2Result()));

        $msg .= \CHtml::tag('li', [], sprintf('Операция: %s == %s = %s',
            $this->getEvent()->getResult()->getTeam1Result(),
            $this->getEvent()->getResult()->getTeam2Result(),
            $this->getEvent()->getResult()->getTeam1Result() == $this->getEvent()->getResult()->getTeam2Result() ? 'TRUE' : 'FALSE'
        ));

        if($this->getEvent()->getResult()->getTeam1Result() == $this->getEvent()->getResult()->getTeam2Result()) {
            $this->setStatus(iStatus::RESULT_WIN);
            $msg .= \CHtml::tag('li', [], 'Итог: Сыграла');
        } else {
            $this->setStatus(iStatus::RESULT_LOSS);
            $msg .= \CHtml::tag('li', [], 'Итог: Не сыграла');
        }

        $msg .= \CHtml::closeTag('ul');
        $this->addExplain($msg);
    }
}