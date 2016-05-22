<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 22.05.2016
 */

namespace common\components\bookmaker\_interface;


use common\models\TeamAlias;

interface iBookmakerEvent
{
    /**
     * @return int
     */
    public function getDate();

    /**
     * @param $date
     * @return self
     */
    public function setDate($date);

    /**
     * @return TeamAlias
     */
    public function getTeam1Alias();

    /**
     * @param $team_1
     * @return self
     */
    public function setTeam1Alias($team_1);

    /**
     * @return TeamAlias
     */
    public function getTeam2Alias();

    /**
     * @param $team_2
     * @return self
     */
    public function setTeam2Alias($team_2);

    /**
     * @return array
     */
    public function getOdds();

    /**
     * @param $ratio_list
     * @return self
     */
    public function setOdds($ratio_list);

    /**
     * @return mixed
     */
    public function getSportId();

    /**
     * @param $sport_id
     * @return self
     */
    public function setSportId($sport_id);
}