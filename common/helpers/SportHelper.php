<?php
namespace common\helpers;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 */
class SportHelper
{
    const SPORT_FOOTBALL    = 'football';
    const SPORT_TENNIS      = 'tennis';
    const SPORT_BASKETBALL  = 'basketball';
    const SPORT_HOKKEY      = 'hokkey';

    private static $sports = [
        self::SPORT_FOOTBALL,
        self::SPORT_TENNIS,
        self::SPORT_BASKETBALL,
        self::SPORT_HOKKEY,
    ];

    public static function checkSport($sport_key)
    {
        return ArrayHelper::getColumn(self::$sports, $sport_key);
    }
}