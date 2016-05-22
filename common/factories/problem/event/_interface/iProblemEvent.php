<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 23.05.2016
 */

namespace common\factories\problem\event\_interface;


use common\components\bookmaker\_interface\iBookmakerEvent;
use \common\models\event\iEvent as iModelEvent;

/**
 * Interface iProblemEvent
 * @package common\factories\problem\event\_interface
 *
 */
interface iProblemEvent
{
    /**
     * @param iBookmakerEvent $BookmakerEvent
     * @return self
     */
    public function setBookmakerEvent($BookmakerEvent);

    /**
     * @param iModelEvent $event
     * @return self
     */
    public function setEvent($event);

    public function check();
}