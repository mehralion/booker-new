<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories\odds\_default\_base;


use common\factories\odds\_default\_interface\iRatio;
use common\models\event\Event;

abstract class BaseRatio implements iRatio
{
    /** @var Event */
    protected $Event;
    /** @var  \UserBetting */
    protected $Bet;
    protected $status;
    protected $ratio_value;
    protected $explain;

    public function getHint()
    {
        return [];
    }

    public function isGandikap()
    {
        return false;
    }

    /**
     * @return Event
     */
    public function getEvent()
    {
        return $this->Event;
    }

    /**
     * @param Event $Event
     * @return $this
     */
    public function setEvent($Event)
    {
        $this->Event = $Event;
        return $this;
    }

    /**
     * @return \UserBetting
     */
    public function getBet()
    {
        return $this->Bet;
    }

    /**
     * @param \UserBetting $Bet
     * @return $this
     */
    public function setBet($Bet)
    {
        $this->Bet = $Bet;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRatioValue()
    {
        return $this->ratio_value;
    }

    /**
     * @param mixed $ratio_value
     * @return $this
     */
    public function setRatioValue($ratio_value)
    {
        $this->ratio_value = $ratio_value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExplain()
    {
        return $this->explain;
    }

    /**
     * @param mixed $explain
     *
     * @return $this
     */
    public function setExplain($explain)
    {
        $this->explain = $explain;
        return $this;
    }

    /**
     * @param $explain
     * @return $this
     */
    public function addExplain($explain)
    {
        $this->explain[] = $explain;
        return $this;
    }
}