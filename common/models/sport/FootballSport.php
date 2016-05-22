<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 22.05.2016
 */

namespace common\models\sport;


use common\helpers\SportHelper;
use common\models\query\SportQuery;

class FootballSport extends Sport
{
    public function init()
    {
        $this->sport_type = SportHelper::SPORT_FOOTBALL;
        parent::init();
    }

    public static function find()
    {
        return new SportQuery(get_called_class(), ['sport_type' => SportHelper::SPORT_FOOTBALL]);
    }

    public function beforeSave($insert)
    {
        $this->sport_type = SportHelper::SPORT_FOOTBALL;
        return parent::beforeSave($insert);
    }
}