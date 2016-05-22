<?php
/**
 * Created by PhpStorm.
 */

namespace common\components\bookmaker\parimatch\factories;

use common\components\bookmaker\parimatch\factories\validators\_interface\iValidator;
use common\helpers\SportHelper;

class ParserValidate
{
    private static $validators = [
        SportHelper::SPORT_FOOTBALL => [
            '\common\components\bookmaker\parimatch\factories\validators\football\Main',
            '\common\components\bookmaker\parimatch\factories\validators\football\Statistic',
            '\common\components\bookmaker\parimatch\factories\validators\football\Custom1',
            '\common\components\bookmaker\parimatch\factories\validators\football\Custom2',
            '\common\components\bookmaker\parimatch\factories\validators\football\Custom3',
            '\common\components\bookmaker\parimatch\factories\validators\football\Custom4',
        ],
        SportHelper::SPORT_TENNIS => [
            '\common\components\bookmaker\parimatch\factories\validators\tennis\Main',
            '\common\components\bookmaker\parimatch\factories\validators\tennis\Custom1',
        ],
        SportHelper::SPORT_BASKETBALL => [
            '\common\components\bookmaker\parimatch\factories\validators\basketball\Main',
        ],
        SportHelper::SPORT_HOKKEY => [
            '\common\components\bookmaker\parimatch\factories\validators\hokkey\Main',
        ]
    ];

    public static function getValidator($sport_id, $html)
    {
        foreach (self::$validators[$sport_id] as $className) {
            try {
                /** @var iValidator $object */
                $object = new $className();
                $object->populate([
                    'html' => $html
                ]);
                if($object->check()) {
                    return $object;
                }
            } catch (\Exception $ex) {
                throw new \Exception(sprintf('Неудалось найти класс. Class: %s', 'getValidator', $className));
            }
        }

        return false;
    }
}