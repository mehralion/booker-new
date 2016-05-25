<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 23.05.2016
 */

namespace common\models\event\odds;


interface iEventOdds
{
    public function canAuto();

    public function getNotAutoReason();
}