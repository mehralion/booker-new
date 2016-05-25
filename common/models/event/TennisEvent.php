<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 22.05.2016
 */

namespace common\models\event;


use common\helpers\SportHelper;
use common\models\event\odds\TennisOdds;
use common\models\event\result\TennisResult;
use common\models\query\EventQuery;

class TennisEvent extends Event
{
    public function init()
    {
        $this->sport_type = SportHelper::SPORT_TENNIS;
        parent::init();

        $this->old_odds = new TennisOdds();
        $this->new_odds = new TennisOdds();
        $this->event_result = new TennisResult();
    }

    public static function find()
    {
        return new EventQuery(get_called_class(), ['sport_type' => SportHelper::SPORT_TENNIS]);
    }

    public function beforeSave($insert)
    {
        $this->sport_type = SportHelper::SPORT_TENNIS;
        return parent::beforeSave($insert);
    }
}