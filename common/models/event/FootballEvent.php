<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 22.05.2016
 */

namespace common\models\event;


use common\helpers\SportHelper;
use common\models\query\EventQuery;

class FootballEvent extends Event
{
    public function init()
    {
        $this->sport_type = SportHelper::SPORT_FOOTBALL;
        parent::init();
    }

    public static function find()
    {
        return new EventQuery(get_called_class(), ['sport_type' => SportHelper::SPORT_FOOTBALL]);
    }

    public function beforeSave($insert)
    {
        $this->sport_type = SportHelper::SPORT_FOOTBALL;
        return parent::beforeSave($insert);
    }
}