<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 22.05.2016
 */

namespace common\components\bookmaker\_base;


use common\components\bookmaker\_interface\iBookmakerEvent;

abstract class BaseEvent implements iBookmakerEvent
{
    protected $date;
    protected $team_1;
    protected $team_1_alias;
    protected $team_2;
    protected $team_2_alias;
    protected $odds = [];
    protected $sport_id;

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     *
     * @return $this
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTeam1Alias()
    {
        return $this->team_1;
    }

    /**
     * @param mixed $team_1
     *
     * @return $this
     */
    public function setTeam1Alias($team_1)
    {
        $this->team_1 = $team_1;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTeam2Alias()
    {
        return $this->team_2;
    }

    /**
     * @param mixed $team_2
     *
     * @return $this
     */
    public function setTeam2Alias($team_2)
    {
        $this->team_2 = $team_2;
        return $this;
    }

    /**
     * @return array
     */
    public function getOdds()
    {
        return $this->odds;
    }

    /**
     * @param array $odds
     *
     * @return $this
     */
    public function setOdds($odds)
    {
        $this->odds = $odds;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSportId()
    {
        return $this->sport_id;
    }

    /**
     * @param mixed $sport_id
     *
     * @return $this
     */
    public function setSportId($sport_id)
    {
        $this->sport_id = $sport_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTeam1()
    {
        return $this->team_1;
    }

    /**
     * @param mixed $team_1
     *
     * @return $this
     */
    public function setTeam1($team_1)
    {
        $this->team_1 = $team_1;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTeam2()
    {
        return $this->team_2;
    }

    /**
     * @param mixed $team_2
     *
     * @return $this
     */
    public function setTeam2($team_2)
    {
        $this->team_2 = $team_2;
        return $this;
    }
}