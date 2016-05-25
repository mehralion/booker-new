<?php
namespace common\helpers;

/**
 * Created by PhpStorm.
 * User: me
 * Date: 12.02.2015
 * Time: 3:12
 */

class Convert
{
    /**
     * @param $value
     * @return float
     */
    public static function getMoneyFormat($value)
    {
        return \Yii::$app->formatter->asDecimal($value, 2);

        $value = str_replace(',', '.', $value);
        $value = strval($value * 100);
        return number_format(floor($value) / 100, 2, '.', '');
    }

    /**
     * @param $value
     * @param int $countAfterDot
     * @return string
     */
    public static function getOddsFormat($value, $countAfterDot = 2)
    {
        return \Yii::$app->formatter->asDecimal($value, $countAfterDot);

        $value = str_replace(',', '.', $value);
        $value = strval($value * 100);
        return number_format(floor($value) / 100, $countAfterDot, '.', '');
    }

    public static function roundMaxBet($value)
    {
        $value = floor($value) / 10;
        $intVal = floor($value);
        if($value - $intVal >= 0.5) {
            $intVal += 0.5;
        }

        return $intVal * 10;
    }
}