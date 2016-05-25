<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories\odds\_default\_base;


use common\helpers\BetHelper;
use common\helpers\Convert;
use common\helpers\StringHelper;

abstract class BaseFora extends BaseRatio
{
    protected $fora;
    protected $fora1;
    protected $fora2;

    public function isGandikap()
    {
        $decimal = fmod($this->getFora(), 1);
        if($decimal == 0 || $decimal == 0.5 || $decimal == -0.5)
            return false;
        else {
            if($decimal > 0)
                $value = floor($this->getFora());
            else
                $value = ceil($this->getFora());
            if($decimal == 0.25 || $decimal == -0.25) {
                if($decimal > 0)
                    $this->setFora1($value)
                        ->setFora2($value + 0.5);
                else
                    $this->setFora1($value)
                        ->setFora2($value + -0.5);
            } elseif($decimal == -0.75 || $decimal == 0.75) {
                if($decimal > 0)
                    $this->setFora1($value + 0.5)
                        ->setFora2($value + 1);
                else
                    $this->setFora1($value + -0.5)
                        ->setFora2($value + -1);
            }

            return true;
        }
    }

    protected function defaultPrepare()
    {
        $r = $this->prepare($this->getFora());
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
        $fora1Result = $this->prepare($this->getFora1());
        $fora2Result = $this->prepare($this->getFora2());

        if($fora1Result == BetHelper::RESULT_WIN && $fora2Result == BetHelper::RESULT_WIN)
            $this->setStatus(BetHelper::RESULT_WIN);
        elseif($fora1Result == BetHelper::RESULT_WIN && $fora2Result == BetHelper::RESULT_SET_K_1 || $fora1Result == BetHelper::RESULT_SET_K_1 && $fora2Result == BetHelper::RESULT_WIN)
            $this->setStatus(BetHelper::RESULT_WIN)
                ->setRatioValue(($this->getRatioValue() + 1)/2);
        elseif($fora1Result == BetHelper::RESULT_LOSS && $fora2Result == BetHelper::RESULT_SET_K_1 || $fora1Result == BetHelper::RESULT_SET_K_1 && $fora2Result == BetHelper::RESULT_LOSS)
            $this->setStatus(BetHelper::RESULT_WIN)
                ->setRatioValue(0.5);
        elseif($fora1Result == BetHelper::RESULT_LOSS && $fora2Result == BetHelper::RESULT_LOSS)
            $this->setStatus(BetHelper::RESULT_LOSS);
    }

    public function getHintTeam($team)
    {
        $ratio = $this->getRatioValue();
        $fora1 = $this->getFora1() > 0 ? '+'.$this->getFora1() : $this->getFora1();
        $fora2 = $this->getFora2() > 0 ? '+'.$this->getFora2() : $this->getFora2();

        $info[] = sprintf(
            '%s (%s, %s) %s',
            $team, $fora1, $fora2, $ratio);

        if($this->getFora() > 0) {
            //Победа
            if($this->getFora1() == 0)
                $info[] = sprintf(
                    'Вы <strong>выиграете ставку</strong> с коэф. %s если %s ВЫИГРАЕТ',
                    $ratio, $team
                );
            else {
                $value = floor($this->getFora2() - 1);
                if($value == 0)
                    $info[] = sprintf(
                        'Вы <strong>выиграете ставку</strong> с коэф. %s если %s НЕ ПРОИГРАЕТ',
                        $ratio, $team
                    );
                else
                    $info[] = sprintf(
                        'Вы <strong>выиграете ставку</strong> с коэф. %s если %s НЕ ПРОИГРАЕТ с разницей более % %',
                        $ratio, $team, $value, StringHelper::getNumEndingBall($value)
                    );
            }

            //Пол коэф.
            if($this->getFora1() == 0)
                $info[] = sprintf(
                    'Вы <strong>выиграете ставку</strong> с коэф. %s если %s сыграет ВНИЧЬЮ',
                    Convert::getOddsFormat(($ratio + 1)/2), $team
                );
            else {
                $decimal = fmod($this->getFora1(), 1);
                if($decimal == 0) {
                    $info[] = sprintf(
                        'Вы <strong>выиграете ставку</strong> с коэф. %s если %s ПРОИГРАЕТ с разницей %s %s',
                        Convert::getOddsFormat(($ratio + 1)/2), $team, $this->getFora1(), StringHelper::getNumEndingBall($this->getFora1())
                    );
                } else {
                    $info[] = sprintf(
                        'Вы <strong>потеряете половину ставки</strong> если %s ПРОИГРАЕТ с разницей %s %s',
                        $team, $this->getFora2(), StringHelper::getNumEndingBall($this->getFora2())
                    );
                }
            }

            //Проигрыш
            if($this->getFora1() == 0)
                $info[] = sprintf(
                    'Вы <strong>проиграете ставку</strong> если %s ПРОИГРАЕТ',
                    $team
                );
            else {
                $value = ceil($this->getFora1() + 1);
                $info[] = sprintf(
                    'Вы <strong>проиграете ставку</strong> если %s ПРОИГРАЕТ с разницей не менее %s %s',
                    $team, $value, StringHelper::getNumEndingBall($value)
                );
            }
        } else {
            //Победа
            if($this->getFora1() == 0)
                $info[] = sprintf(
                    'Вы <strong>выиграете ставку</strong> с коэф. %s если %s ВЫИГРАЕТ',
                    $ratio, $team
                );
            else {
                $value = floor($this->getFora1() + (-1)) * (-1);
                $info[] = sprintf(
                    'Вы <strong>выиграете ставку</strong> с коэф. %s если %s ВЫИГРАЕТ с разницей не менее %s %s',
                    $ratio, $team, $value, StringHelper::getNumEndingBall($value)
                );
            }

            //Полставки
            if($this->getFora1() == 0)
                $info[] = sprintf(
                    'Вы <strong>потеряете половину ставки</strong> если %s сыграет ВНИЧЬЮ',
                    $team
                );
            else {
                $decimal = fmod($this->getFora1(), 1);
                if($decimal < 0) {
                    $value = $this->getFora2() * (-1);
                    $info[] = sprintf(
                        'Вы <strong>выиграете ставку</strong> с коэф. %s если %s ВЫИГРАЕТ с разницей %s %s',
                        Convert::getOddsFormat(($ratio + 1)/2), $team, $value, StringHelper::getNumEndingBall($value)
                    );
                } else {
                    $value = $this->getFora1() * (-1);
                    $info[] = sprintf(
                        'Вы <strong>потеряете половину ставки</strong> если %s ВЫИГРАЕТ с разницей %s %s',
                        $team, $value, StringHelper::getNumEndingBall($value)
                    );
                }
            }


            //Проигрыш
            $value = ceil($this->getFora2() + 1) * (-1);
            if($this->getFora1() == 0 || $value == 0)
                $info[] = sprintf(
                    'Вы <strong>проиграете ставку</strong> если %s НЕ ВЫИГРАЕТ',
                    $team
                );
            else {
                $info[] = sprintf(
                    'Вы <strong>проиграете ставку</strong> если %s НЕ ВЫИГРАЕТ c разницей более %s %s',
                    $team, $value, StringHelper::getNumEndingBall($value)
                );
            }
        }

        return $info;
    }

    abstract protected function prepare($foraVal);

    /**
     * @return mixed
     */
    public function getFora()
    {
        return $this->fora;
    }

    /**
     * @param mixed $fora
     * @return $this
     */
    public function setFora($fora)
    {
        $this->fora = $fora;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFora1()
    {
        return $this->fora1;
    }

    /**
     * @param mixed $fora1
     * @return $this
     */
    public function setFora1($fora1)
    {
        $this->fora1 = $fora1;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFora2()
    {
        return $this->fora2;
    }

    /**
     * @param mixed $fora2
     * @return $this
     */
    public function setFora2($fora2)
    {
        $this->fora2 = $fora2;
        return $this;
    }
}