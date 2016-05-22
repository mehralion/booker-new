<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 22.05.2016
 */

namespace common\models\sport;


use common\helpers\SportHelper;
use common\models\query\SportQuery;

class TennisSport extends Sport
{
    public function init()
    {
        $this->sport_type = SportHelper::SPORT_TENNIS;
        parent::init();
    }

    public static function find()
    {
        return new SportQuery(get_called_class(), ['sport_type' => SportHelper::SPORT_TENNIS]);
    }

    public function beforeSave($insert)
    {
        $this->sport_type = SportHelper::SPORT_TENNIS;
        return parent::beforeSave($insert);
    }
}