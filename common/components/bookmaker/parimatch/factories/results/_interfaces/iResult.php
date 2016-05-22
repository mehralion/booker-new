<?php
/**
 * Created by PhpStorm.
 */

namespace common\components\bookmaker\parimatch\factories\results\_interfaces;


interface iResult
{
    /**
     * @return \common\sport\result\iResult
     */
    public function getData();

    /**
     * @param $team1
     * @param $team2
     */
    public function newTeams($team1, $team2);
}