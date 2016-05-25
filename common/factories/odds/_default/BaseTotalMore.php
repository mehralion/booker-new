<?php

namespace common\factories\odds\_default;
use common\factories\odds\_default\_base\BaseTotal;
use common\factories\odds\_default\_interface\iRatio;
use common\helpers\Convert;
use common\helpers\StringHelper;
use common\interfaces\iStatus;

/**
 * Class TotalMore
 * @package common\factories\ratio
 */
abstract class BaseTotalMore extends BaseTotal implements iRatio
{
    protected function prepare($totalVal)
    {
        $msg = \CHtml::openTag('ul', ['class' => 'log']);
        $msg .= \CHtml::tag('li', [], sprintf('Результат 1: %s', $this->getEvent()->getResult()->getTeam1Result()));
        $msg .= \CHtml::tag('li', [], sprintf('Результат 2: %s', $this->getEvent()->getResult()->getTeam2Result()));
        $msg .= \CHtml::tag('li', [], sprintf('Занчение тотала: %s', $totalVal));

        $value = $this->getEvent()->getResult()->getTeam1Result() + $this->getEvent()->getResult()->getTeam2Result();
        $msg .= \CHtml::tag('li', [], sprintf('Сумма: %s', $value));
        $msg .= \CHtml::tag('li', [], sprintf('Операция: %s > %s = %s',
            $value,
            $totalVal,
            $value > $totalVal ? 'TRUE' : 'FALSE'
        ));

        if($value > $totalVal) {
            $return = iStatus::RESULT_WIN;
            $msg .= \CHtml::tag('li', [], 'Итог: Сыграла');
        } elseif($value == $totalVal) {
            $return = iStatus::RESULT_SET_K_1;
            $msg .= \CHtml::tag('li', [], 'Итог: Возврат');
        } else {
            $return = iStatus::RESULT_LOSS;
            $msg .= \CHtml::tag('li', [], 'Итог: Не сыграла');
        }

        $msg .= \CHtml::closeTag('ul');
        $this->addExplain($msg);
        return $return;
    }

    public function getHint()
    {
        if(!$this->isGandikap())
            return [];

        $ratio = $this->getRatioValue();
        $totalVal1 = $this->getTotalVal1();
        $totalVal2 = $this->getTotalVal2();

        $info[] = sprintf(
            '%s - %s БОЛЬШЕ (%s, %s) %s',
            $this->getEvent()->getTeam1(), $this->getEvent()->getTeam2(), $totalVal1, $totalVal2, $ratio);

        //Победа
        $value = ceil($totalVal1);
        $info[] = sprintf(
            'Вы <strong>выиграете ставку</strong> с коэф. %s если будет забито более %s %s',
            $ratio, $value, StringHelper::getNumEndingGoal($value)
        );

        //Пол коэф.
        $decimal = fmod($totalVal1, 1);
        if($decimal == 0) {
            $info[] = sprintf(
                'Вы <strong>потеряете половину ставки</strong> если будет забито %s %s',
                $value, StringHelper::getNumEndingGoal($value)
            );
        } else {
            $info[] = sprintf(
                'Вы <strong>выиграете ставку</strong> с коэф. %s если будет забито %s %s',
                Convert::getFormat(($ratio + 1)/2), $value, StringHelper::getNumEndingGoal($value)
            );
        }

        //Проигрыш
        $info[] = sprintf(
            'Вы <strong>проиграете ставку</strong> если будет забито менее %s %s',
            $value, StringHelper::getNumEndingGoal($value)
        );

        return $info;
    }
}