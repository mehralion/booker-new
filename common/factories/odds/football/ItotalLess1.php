<?php

namespace common\factories\ratio\football;
use common\factories\ratio\_default\_interface\iRatio;
use common\factories\ratio\_default\BaseItotalLess1;

/**
 * Class ForaRatio1
 * @package common\factories\ratio
 *
 * @method \FootballEvent getEvent()
 */
class ItotalLess1 extends BaseItotalLess1 implements iRatio
{
    protected function getItotalVal1()
    {
        return $this->getEvent()->getNewRatio()->getItotalVal1();
    }
}