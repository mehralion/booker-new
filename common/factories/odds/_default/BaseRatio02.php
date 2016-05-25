<?php

namespace common\factories\odds\_default;
use common\factories\odds\_default\_base\BaseRatio;
use common\factories\odds\_default\_interface\iRatio;
use common\interfaces\iStatus;

/**
 * Class ForaRatio1
 * @package common\factories\ratio
 */
class BaseRatio02 extends BaseRatio implements iRatio
{
    /**
     * @return int
     */
    public function check()
    {
        $msg = \CHtml::openTag('ul', ['class' => 'log']);
        $msg .= \CHtml::tag('li', [], sprintf('Результат 1: %s', $this->getEvent()->getResult()->getTeam1Result()));
        $msg .= \CHtml::tag('li', [], sprintf('Результат 2: %s', $this->getEvent()->getResult()->getTeam2Result()));

        $msg .= \CHtml::tag('li', [], sprintf('Операция: %s == 0 && %s == 2 = %s',
            $this->getEvent()->getResult()->getTeam1Result(),
            $this->getEvent()->getResult()->getTeam2Result(),
            $this->getEvent()->getResult()->getTeam1Result() == 0 && $this->getEvent()->getResult()->getTeam2Result() == 2 ? 'TRUE' : 'FALSE'
        ));

        if($this->getEvent()->getResult()->getTeam1Result() == 0 && $this->getEvent()->getResult()->getTeam2Result() == 2) {
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