<?php

namespace common\factories\ratio\basketball;
use common\factories\ratio\_default\_interface\iRatio;
use common\factories\ratio\_default\BaseForaRatio1;

/**
 * Class ForaRatio1
 * @package common\factories\ratio
 *
 * @method \BasketballEvent getEvent()
 */
class ForaRatio1 extends BaseForaRatio1 implements iRatio
{
    protected function getForaVal1()
    {
        return $this->getEvent()->getNewRatio()->getForaVal1();
    }
}