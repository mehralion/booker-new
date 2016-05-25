<?php

namespace common\factories\ratio\football;
use common\factories\ratio\_default\_interface\iRatio;
use common\factories\ratio\_default\BaseItotalMore2;

/**
 * Class ForaRatio1
 * @package common\factories\ratio
 *
 * @method \FootballEvent getEvent()
 */
class ItotalMore2 extends BaseItotalMore2 implements iRatio
{
    protected function getItotalVal2()
    {
        return $this->getEvent()->getNewRatio()->getItotalVal2();
    }
}