<?php

namespace common\factories\odds\_default;
use common\factories\odds\_default\_base\BaseFora;
use common\helpers\BetHelper;
use yii\bootstrap\Html;

/**
 * Class ForaRatio1
 * @package common\factories\ratio
 */
abstract class BaseForaRatio1 extends BaseFora
{
    abstract protected function getForaVal1();

    /**
     * @return int
     */
    public function check()
    {
        $this->setFora($this->getForaVal1());
        if(!$this->isGandikap())
            $this->defaultPrepare();
        else
            $this->decimalPrepare();
    }

    protected function prepare($foraVal)
    {
        $msg = Html::beginTag('ul', ['class' => 'log']);
        $msg .= Html::tag('li', [], sprintf('Результат 1: %s', $this->getEvent()->getResult()->getTeam1Result()));
        $msg .= Html::tag('li', [], sprintf('Результат 2: %s', $this->getEvent()->getResult()->getTeam2Result()));
        $msg .= Html::tag('li', [], sprintf('Фора: %s', $foraVal));

        $r = $this->getEvent()->getResult()->getTeam1Result() + $foraVal;
        $msg .= Html::tag('li', [], sprintf('Сумма: %s', $r));
        $msg .= Html::tag('li', [], sprintf('Операция: %s > %s = %s',
            $r,
            $this->getEvent()->getResult()->getTeam2Result(),
            $r > $this->getEvent()->getResult()->getTeam2Result() ? 'TRUE' : 'FALSE'
        ));

        if($r > $this->getEvent()->getResult()->getTeam2Result()) {
            $result = BetHelper::RESULT_WIN;
            $msg .= Html::tag('li', [], 'Итог: Сыграла');
        } elseif($r == $this->getEvent()->getResult()->getTeam2Result()) {
            $result = BetHelper::RESULT_SET_K_1;
            $msg .= Html::tag('li', [], 'Итог: Возврат');
        } else {
            $result = BetHelper::RESULT_LOSS;
            $msg .= Html::tag('li', [], 'Итог: Не сыграла');
        }

        $msg .= Html::endTag('ul');
        $this->addExplain($msg);
        return $result;
    }

    public function getHint()
    {
        $this->setFora($this->getForaVal1());
        if(!$this->isGandikap())
            return [];

        return $this->getHintTeam($this->getEvent()->getTeam1());
    }
}