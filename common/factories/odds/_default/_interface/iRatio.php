<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories\odds\_default\_interface;


interface iRatio
{
    public function check();
    public function getHint();
    public function isGandikap();

    /**
     * @param $Event
     * @return $this
     */
    public function setEvent($Event);
    public function getEvent();

    /**
     * @param $Bet
     * @return $this
     */
    public function setBet($Bet);
    public function getBet();

    /**
     * @param $status
     * @return $this
     */
    public function setStatus($status);
    public function getStatus();

    /**
     * @param $ratio_value
     * @return $this
     */
    public function setRatioValue($ratio_value);
    public function getRatioValue();

    /**
     * @return string
     */
    public function getExplain();
}