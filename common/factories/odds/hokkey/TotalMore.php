<?php

namespace common\factories\ratio\hokkey;
use common\factories\ratio\_default\_interface\iRatio;
use common\factories\ratio\_default\BaseTotalMore;

/**
 * Class TotalMore
 * @package common\factories\ratio
 *
 * @method \HokkeyEvent getEvent()
 */
class TotalMore extends BaseTotalMore implements iRatio
{
    protected function getTotalVal()
    {
        return $this->total_val ? $this->total_val : $this->getEvent()->getNewRatio()->getTotalVal();
    }
}