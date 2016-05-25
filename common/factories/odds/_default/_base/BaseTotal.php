<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories\odds\_default\_base;

use common\helpers\BetHelper;

abstract class BaseTotal extends BaseRatio
{
    protected $total_val;
    protected $total_val_1;
    protected $total_val_2;

    abstract protected function getTotalVal();

    /**
     * @param $total_val
     * @return $this
     */
    public function setTotalVal($total_val)
    {
        $this->total_val = $total_val;

        return $this;
    }

    /**
     * @return int
     */
    public function check()
    {
        if(!$this->isGandikap())
            $this->defaultPrepare();
        else
            $this->decimalPrepare();
    }

    public function isGandikap()
    {
        $decimal = fmod($this->getTotalVal(), 1);
        if($decimal == 0 || $decimal == 0.5)
            return false;
        else {
            $value = floor($this->getTotalVal());
            if($decimal == 0.25) {
                $this->setTotalVal1($value)
                    ->setTotalVal2($value + 0.5);
            } elseif($decimal == 0.75) {
                $this->setTotalVal1($value + 0.5)
                    ->setTotalVal2($value + 1);
            }

            return true;
        }
    }

    protected function defaultPrepare()
    {
        $r = $this->prepare($this->getTotalVal());
        if($r == BetHelper::RESULT_WIN)
            $this->setStatus(BetHelper::RESULT_WIN);
        elseif($r == BetHelper::RESULT_LOSS)
            $this->setStatus(BetHelper::RESULT_LOSS);
        elseif($r == BetHelper::RESULT_SET_K_1)
            $this->setStatus(BetHelper::RESULT_WIN)
                ->setRatioValue(1.00);
    }

    protected function decimalPrepare()
    {
        $total1Result = $this->prepare($this->getTotalVal1());
        $total2Result = $this->prepare($this->getTotalVal2());

        if($total1Result == BetHelper::RESULT_WIN && $total2Result == BetHelper::RESULT_WIN)
            $this->setStatus(BetHelper::RESULT_WIN);
        elseif($total1Result == BetHelper::RESULT_WIN && $total2Result == BetHelper::RESULT_SET_K_1 || $total1Result == BetHelper::RESULT_SET_K_1 && $total2Result == BetHelper::RESULT_WIN)
            $this->setStatus(BetHelper::RESULT_WIN)
                ->setRatioValue(($this->getRatioValue() + 1)/2);
        elseif($total1Result == BetHelper::RESULT_LOSS && $total2Result == BetHelper::RESULT_SET_K_1 || $total1Result == BetHelper::RESULT_SET_K_1 && $total2Result == BetHelper::RESULT_LOSS)
            $this->setStatus(BetHelper::RESULT_WIN)
                ->setRatioValue(0.5);
        elseif($total1Result == BetHelper::RESULT_LOSS && $total2Result == BetHelper::RESULT_LOSS)
            $this->setStatus(BetHelper::RESULT_LOSS);
    }

    abstract protected function prepare($totalVal);

    /**
     * @return mixed
     */
    public function getTotalVal1()
    {
        return $this->total_val_1;
    }

    /**
     * @param mixed $total_val_1
     * @return $this
     */
    public function setTotalVal1($total_val_1)
    {
        $this->total_val_1 = $total_val_1;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotalVal2()
    {
        return $this->total_val_2;
    }

    /**
     * @param mixed $total_val_2
     * @return $this
     */
    public function setTotalVal2($total_val_2)
    {
        $this->total_val_2 = $total_val_2;
        return $this;
    }
}