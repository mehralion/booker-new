<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 23.05.2016
 */

namespace common\factories\problem\event;


use common\factories\problem\event\_interface\iProblemEvent;

class FactoryProblemEvent
{
    /**
     * @param $sport_type
     * @param $problem_type
     * @return iProblemEvent
     * @throws \Exception
     */
    public static function factory($sport_type, $problem_type)
    {
        $className = sprintf('\common\factories\problem\event\\%s\\%s', $sport_type, ucfirst($problem_type));

        try {
            return new $className();
        } catch (\Exception $ex) {
            throw new \Exception(sprintf('Неудалось найти класс в фабрике %s. Class: %s', 'FactoryProblemEvent', $className));
        }
    }
}