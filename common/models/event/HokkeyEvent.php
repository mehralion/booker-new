<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 22.05.2016
 */

namespace common\models\event;


use common\helpers\SportHelper;
use common\models\event\odds\HokkeyOdds;
use common\models\event\result\HokkeyResult;
use common\models\query\EventQuery;

class HokkeyEvent extends Event
{
    public function init()
    {
        $this->sport_type = SportHelper::SPORT_HOKKEY;
        parent::init();

        $this->old_odds = new HokkeyOdds();
        $this->new_odds = new HokkeyOdds();
        $this->event_result = new HokkeyResult();
    }

    public static function find()
    {
        return new EventQuery(get_called_class(), ['sport_type' => SportHelper::SPORT_HOKKEY]);
    }

    public function beforeSave($insert)
    {
        $this->sport_type = SportHelper::SPORT_HOKKEY;
        return parent::beforeSave($insert);
    }
}